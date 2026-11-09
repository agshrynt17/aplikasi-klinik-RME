<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LaporanOperasi;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class LaporanOperasiController extends Controller
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

        $laporans = LaporanOperasi::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.laporan_operasi.index', compact('pendaftarans', 'laporans', 'tanggal', 'totalCount'));
    }

    public function createLapTindakan(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/laptindakan/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/laptindakan/create/' . $request->pendaftaran_id);
        }

        return redirect('/laptindakan/index');
    }

    public function exportXls(Request $request)
    {
        return response("Data Laporan Tindakan/Operasi Export XLS", 200)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="laporan_tindakan_operasi_' . ($request->tanggal ?? date('Y-m-d')) . '.xls"');
    }

    public function exportPdf(Request $request)
    {
        return response("Data Laporan Tindakan/Operasi Export PDF", 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan_tindakan_operasi_' . ($request->tanggal ?? date('Y-m-d')) . '.pdf"');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $laporan = LaporanOperasi::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($laporan) {
            return redirect('/laptindakan/' . $laporan->id . '/edit');
        }

        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();

        return view('rekam_medis.laporan_operasi.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'tindakan_operasi'=> 'required',
            'laporan_operasi'=> 'required',
        ]);

        try {
            LaporanOperasi::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_operator_id'=> $request->dokter_operator_id,
                'asisten_operator'  => $request->asisten_operator,
                'perawat_instrumen' => $request->perawat_instrumen,
                'dokter_anestesi_id'=> $request->dokter_anestesi_id,
                'jenis_anestesi'    => $request->jenis_anestesi,
                'diagnosa_pra_bedah'=> $request->diagnosa_pra_bedah,
                'diagnosa_pasca_bedah'=> $request->diagnosa_pasca_bedah,
                'tindakan_operasi'  => $request->tindakan_operasi,
                'jaringan_dieksisi' => $request->jaringan_dieksisi,
                'waktu_mulai'       => $request->waktu_mulai,
                'waktu_selesai'     => $request->waktu_selesai,
                'laporan_operasi'   => $request->laporan_operasi,
                'instruksi_pasca_bedah' => $request->instruksi_pasca_bedah,
            ]);

            return redirect('/laptindakan/index')->with('success', 'Laporan Operasi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $laporan = LaporanOperasi::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $laporan->pendaftaran;
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.laporan_operasi.edit', compact('laporan', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $laporan = LaporanOperasi::findOrFail($id);
            $laporan->update([
                'dokter_operator_id'=> $request->dokter_operator_id,
                'asisten_operator'  => $request->asisten_operator,
                'perawat_instrumen' => $request->perawat_instrumen,
                'dokter_anestesi_id'=> $request->dokter_anestesi_id,
                'jenis_anestesi'    => $request->jenis_anestesi,
                'diagnosa_pra_bedah'=> $request->diagnosa_pra_bedah,
                'diagnosa_pasca_bedah'=> $request->diagnosa_pasca_bedah,
                'tindakan_operasi'  => $request->tindakan_operasi,
                'jaringan_dieksisi' => $request->jaringan_dieksisi,
                'waktu_mulai'       => $request->waktu_mulai,
                'waktu_selesai'     => $request->waktu_selesai,
                'laporan_operasi'   => $request->laporan_operasi,
                'instruksi_pasca_bedah' => $request->instruksi_pasca_bedah,
            ]);

            return redirect('/laptindakan/index')->with('success', 'Laporan Operasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            LaporanOperasi::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Laporan Operasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
