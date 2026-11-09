<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Triase;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class TriaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai konteks PROFASKES
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

        $triases = Triase::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.triase.index', compact('pendaftarans', 'triases', 'tanggal', 'totalCount'));
    }

    public function createTriase(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/triase/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/triase/create/' . $request->pendaftaran_id);
        }

        return redirect('/triase/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Triase Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="triase_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Triase Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="triase_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $triase = Triase::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($triase) {
            return redirect('/triase/' . $triase->id . '/edit');
        }

        return view('rekam_medis.triase.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'kategori_triase'=> 'required'
        ]);

        try {
            Triase::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'pasien_id'      => $request->pasien_id,
                'pegawai_id'     => $request->pegawai_id,
                'waktu_triase'   => $request->waktu_triase ?? now(),
                'cara_datang'    => $request->cara_datang,
                'asal_rujukan'   => $request->asal_rujukan,
                'keluhan_utama'  => $request->keluhan_utama,
                'kategori_triase'=> $request->kategori_triase,
                'jalan_napas'    => $request->jalan_napas,
                'pernapasan'     => $request->pernapasan,
                'sirkulasi'      => $request->sirkulasi,
                'kesadaran'      => $request->kesadaran,
                'tensi'          => $request->tensi,
                'nadi'           => $request->nadi,
                'suhu'           => $request->suhu,
                'rr'             => $request->rr,
                'spo2'           => $request->spo2,
                'gcs'            => $request->gcs,
                'keterangan'     => $request->keterangan,
            ]);

            return redirect('/triase/index')->with('success', 'Data Triase berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        $triase = Triase::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $triase->pendaftaran;
        
        return view('rekam_medis.triase.edit', compact('triase', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $triase = Triase::findOrFail($id);
            $triase->update([
                'pegawai_id'     => $request->pegawai_id,
                'waktu_triase'   => $request->waktu_triase ?? now(),
                'cara_datang'    => $request->cara_datang,
                'asal_rujukan'   => $request->asal_rujukan,
                'keluhan_utama'  => $request->keluhan_utama,
                'kategori_triase'=> $request->kategori_triase,
                'jalan_napas'    => $request->jalan_napas,
                'pernapasan'     => $request->pernapasan,
                'sirkulasi'      => $request->sirkulasi,
                'kesadaran'      => $request->kesadaran,
                'tensi'          => $request->tensi,
                'nadi'           => $request->nadi,
                'suhu'           => $request->suhu,
                'rr'             => $request->rr,
                'spo2'           => $request->spo2,
                'gcs'            => $request->gcs,
                'keterangan'     => $request->keterangan,
            ]);

            return redirect('/triase/index')->with('success', 'Data Triase berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Triase::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data triase berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
