<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PraAnestesi;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class PraAnestesiController extends Controller
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

        $praanestesis = PraAnestesi::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.pra_anestesi.index', compact('pendaftarans', 'praanestesis', 'tanggal', 'totalCount'));
    }

    public function createPraAnestesi(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/praanestesi/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/praanestesi/create/' . $request->pendaftaran_id);
        }

        return redirect('/praanestesi/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Pengkajian Pra-Anestesi Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="praanestesi_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Pengkajian Pra-Anestesi Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="praanestesi_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $praanestesi = PraAnestesi::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($praanestesi) {
            return redirect('/praanestesi/' . $praanestesi->id . '/edit');
        }

        return view('rekam_medis.pra_anestesi.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'asa_score'      => 'required',
            'rencana_anestesi'=> 'required',
        ]);

        try {
            PraAnestesi::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'waktu_pengkajian'  => $request->waktu_pengkajian ?? now(),
                'riwayat_alergi'    => $request->riwayat_alergi,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'riwayat_operasi'   => $request->riwayat_operasi,
                'riwayat_anestesi'  => $request->riwayat_anestesi,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'tb'                => $request->tb,
                'bb'                => $request->bb,
                'jalan_napas'       => $request->jalan_napas,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'asa_score'         => $request->asa_score,
                'rencana_anestesi'  => $request->rencana_anestesi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/praanestesi/index')->with('success', 'Pengkajian Pra Anestesi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        $praanestesi = PraAnestesi::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $praanestesi->pendaftaran;
        
        return view('rekam_medis.pra_anestesi.edit', compact('praanestesi', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $praanestesi = PraAnestesi::findOrFail($id);
            $praanestesi->update([
                'dokter_id'         => $request->dokter_id,
                'waktu_pengkajian'  => $request->waktu_pengkajian ?? now(),
                'riwayat_alergi'    => $request->riwayat_alergi,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'riwayat_operasi'   => $request->riwayat_operasi,
                'riwayat_anestesi'  => $request->riwayat_anestesi,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'tb'                => $request->tb,
                'bb'                => $request->bb,
                'jalan_napas'       => $request->jalan_napas,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'pemeriksaan_penunjang' => $request->pemeriksaan_penunjang,
                'asa_score'         => $request->asa_score,
                'rencana_anestesi'  => $request->rencana_anestesi,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/praanestesi/index')->with('success', 'Pengkajian Pra Anestesi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            PraAnestesi::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Pra Anestesi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
