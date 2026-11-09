<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ssc;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class SscController extends Controller
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

        $sscs = Ssc::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.ssc.index', compact('pendaftarans', 'sscs', 'tanggal', 'totalCount'));
    }

    public function createSurgical(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/surgical/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/surgical/create/' . $request->pendaftaran_id);
        }

        return redirect('/surgical/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Surgical Safety Ceklist Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="surgical_safety_ceklist_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Surgical Safety Ceklist Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="surgical_safety_ceklist_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $ssc = Ssc::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($ssc) {
            return redirect('/surgical/' . $ssc->id . '/edit');
        }

        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();

        return view('rekam_medis.ssc.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Ssc::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'sign_in_json'      => $request->sign_in ?? [],
                'time_out_json'     => $request->time_out ?? [],
                'sign_out_json'     => $request->sign_out ?? [],
                'perawat_sirkuler'  => $request->perawat_sirkuler,
            ]);

            return redirect('/surgical/index')->with('success', 'Surgical Safety Ceklist berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $ssc = Ssc::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $ssc->pendaftaran;
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.ssc.edit', compact('ssc', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $ssc = Ssc::findOrFail($id);
            
            $ssc->update([
                'dokter_id'         => $request->dokter_id,
                'sign_in_json'      => $request->sign_in ?? [],
                'time_out_json'     => $request->time_out ?? [],
                'sign_out_json'     => $request->sign_out ?? [],
                'perawat_sirkuler'  => $request->perawat_sirkuler,
            ]);

            return redirect('/surgical/index')->with('success', 'Surgical Safety Ceklist berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Ssc::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Surgical Safety Ceklist berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
