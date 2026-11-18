<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PraBedah;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class PraBedahController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai konteks KONHAKES
            $hasDate = Pendaftaran::whereDate('tanggal_daftar', '2026-10-14')->exists();
            if ($hasDate) {
                $tanggal = '2026-10-14';
                $query->whereDate('tanggal_daftar', '2026-10-14');
            } else {
                $tanggal = date('Y-m-d');
                $query->whereDate('tanggal_daftar', $tanggal);
            }
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function($q) use ($term) {
                $q->where('nomor_registrasi', 'like', "%{$term}%")
                  ->orWhere('no_rawat', 'like', "%{$term}%")
                  ->orWhereHas('pasien', function($qp) use ($term) {
                      $qp->where('nama_pasien', 'like', "%{$term}%")
                         ->orWhere('no_rm', 'like', "%{$term}%");
                  });
            });
        }

        $query->orderBy('nomor_registrasi', 'desc');

        $pendaftarans = $query->paginate(20)->withQueryString();
        $totalCount = $pendaftarans->total();

        $prabedahs = PraBedah::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.pra_bedah.index', compact('pendaftarans', 'prabedahs', 'tanggal', 'totalCount'));
    }

    public function createPrabedah(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/prabedah/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/prabedah/create/' . $request->pendaftaran_id);
        }

        return redirect('/prabedah/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Pengkajian Prabedah Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="prabedah_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Pengkajian Prabedah Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="prabedah_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $prabedah = PraBedah::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($prabedah) {
            return redirect('/prabedah/' . $prabedah->id . '/edit');
        }

        return view('rekam_medis.pra_bedah.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'diagnosa_pra_bedah' => 'required',
            'rencana_tindakan'   => 'required',
        ]);

        try {
            PraBedah::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'waktu_pengkajian'  => $request->waktu_pengkajian ?? now(),
                'diagnosa_pra_bedah'=> $request->diagnosa_pra_bedah,
                'rencana_tindakan'  => $request->rencana_tindakan,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'persiapan_darah'   => $request->persiapan_darah,
                'puasa_mulai'       => $request->puasa_mulai,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'resiko_operasi'    => $request->resiko_operasi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/prabedah/index')->with('success', 'Pengkajian Pra Bedah berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        $prabedah = PraBedah::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $prabedah->pendaftaran;
        
        return view('rekam_medis.pra_bedah.edit', compact('prabedah', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $prabedah = PraBedah::findOrFail($id);
            $prabedah->update([
                'dokter_id'         => $request->dokter_id,
                'waktu_pengkajian'  => $request->waktu_pengkajian ?? now(),
                'diagnosa_pra_bedah'=> $request->diagnosa_pra_bedah,
                'rencana_tindakan'  => $request->rencana_tindakan,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'persiapan_darah'   => $request->persiapan_darah,
                'puasa_mulai'       => $request->puasa_mulai,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'resiko_operasi'    => $request->resiko_operasi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/prabedah/index')->with('success', 'Pengkajian Pra Bedah berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            PraBedah::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Pra Bedah berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
