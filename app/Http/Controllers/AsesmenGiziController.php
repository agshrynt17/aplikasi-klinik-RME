<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AsesmenGizi;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class AsesmenGiziController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai context KONHAKES
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

        // Get asesmen gizi count per pendaftaran
        $asesmens = AsesmenGizi::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.asesmen_gizi.index', compact('pendaftarans', 'asesmens', 'tanggal', 'totalCount'));
    }

    public function createAsgizi(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
        } elseif ($request->filled('pendaftaran_id')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->findOrFail($request->pendaftaran_id);
        } else {
            return redirect('/asgizi/index');
        }

        $asesmen = AsesmenGizi::where('pendaftaran_id', $pendaftaran->id)->first();
        if ($asesmen) {
            return redirect('/asgizi/edit/' . $asesmen->id);
        }

        return view('rekam_medis.asesmen_gizi.create', compact('pendaftaran'));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $asesmen = AsesmenGizi::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($asesmen) {
            return redirect('/asgizi/edit/' . $asesmen->id);
        }

        return view('rekam_medis.asesmen_gizi.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            AsesmenGizi::create([
                'pendaftaran_id'  => $request->pendaftaran_id,
                'pasien_id'       => $request->pasien_id,
                'tanggal_asesmen' => now(),
                'antropometri'    => $request->antropometri,
                'biokimia'        => $request->biokimia,
                'klinis_fisik'    => $request->klinis_fisik,
                'riwayat_makan'   => $request->riwayat_makan,
                'riwayat_personal'=> $request->riwayat_personal,
                'diagnosis_gizi'  => $request->diagnosis_gizi,
                'intervensi_gizi' => $request->intervensi_gizi,
                'monitoring_evaluasi' => $request->monitoring_evaluasi,
                'pegawai_id'      => $request->pegawai_id,
            ]);

            return redirect('/asgizi/index')->with('success', 'Asesmen Gizi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $asesmen = AsesmenGizi::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $asesmen->pendaftaran;
        
        return view('rekam_medis.asesmen_gizi.edit', compact('asesmen', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $asesmen = AsesmenGizi::findOrFail($id);
            $asesmen->update([
                'antropometri'    => $request->antropometri,
                'biokimia'        => $request->biokimia,
                'klinis_fisik'    => $request->klinis_fisik,
                'riwayat_makan'   => $request->riwayat_makan,
                'riwayat_personal'=> $request->riwayat_personal,
                'diagnosis_gizi'  => $request->diagnosis_gizi,
                'intervensi_gizi' => $request->intervensi_gizi,
                'monitoring_evaluasi' => $request->monitoring_evaluasi,
                'pegawai_id'      => $request->pegawai_id,
            ]);

            return redirect('/asgizi/index')->with('success', 'Asesmen Gizi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            AsesmenGizi::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data asesmen gizi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
