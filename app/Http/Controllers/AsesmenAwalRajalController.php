<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AsesmenAwalRajal;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class AsesmenAwalRajalController extends Controller
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

        // Get all assessments to map status
        $asesmens = AsesmenAwalRajal::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.asesmen_awal_rajal.index', compact('pendaftarans', 'asesmens', 'tanggal', 'totalCount'));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        // Cek apakah sudah ada asesmen
        $asesmen = AsesmenAwalRajal::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($asesmen) {
            return redirect('/asrajal/' . $asesmen->id . '/edit');
        }

        return view('rekam_medis.asesmen_awal_rajal.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        DB::beginTransaction();
        try {
            $asesmen = AsesmenAwalRajal::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'pasien_id'      => $request->pasien_id,
                'tanggal_asesmen' => now(),
                'keluhan_utama'  => $request->keluhan_utama,
                'riwayat_penyakit_sekarang' => $request->riwayat_penyakit_sekarang,
                'riwayat_penyakit_dahulu' => $request->riwayat_penyakit_dahulu,
                'riwayat_alergi' => $request->riwayat_alergi,
                
                'tensi'          => $request->tensi,
                'nadi'           => $request->nadi,
                'suhu'           => $request->suhu,
                'rr'             => $request->rr,
                'berat_badan'    => $request->berat_badan,
                'tinggi_badan'   => $request->tinggi_badan,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                
                'diagnosis'      => $request->diagnosis,
                'tindakan_rencana'=> $request->tindakan_rencana,
                
                'dokter_id'      => $request->dokter_id, // Opsional
            ]);

            // Update status periksa
            Pendaftaran::where('id', $request->pendaftaran_id)->update(['status_periksa' => 'Diperiksa']);

            DB::commit();
            return redirect('/asrajal/index')->with('success', 'Asesmen awal berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $asesmen = AsesmenAwalRajal::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $asesmen->pendaftaran;
        
        return view('rekam_medis.asesmen_awal_rajal.edit', compact('asesmen', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        $asesmen = AsesmenAwalRajal::findOrFail($id);

        try {
            $asesmen->update([
                'keluhan_utama'  => $request->keluhan_utama,
                'riwayat_penyakit_sekarang' => $request->riwayat_penyakit_sekarang,
                'riwayat_penyakit_dahulu' => $request->riwayat_penyakit_dahulu,
                'riwayat_alergi' => $request->riwayat_alergi,
                
                'tensi'          => $request->tensi,
                'nadi'           => $request->nadi,
                'suhu'           => $request->suhu,
                'rr'             => $request->rr,
                'berat_badan'    => $request->berat_badan,
                'tinggi_badan'   => $request->tinggi_badan,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                
                'diagnosis'      => $request->diagnosis,
                'tindakan_rencana'=> $request->tindakan_rencana,
            ]);

            return redirect('/asrajal/index')->with('success', 'Asesmen awal berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $asesmen = AsesmenAwalRajal::findOrFail($id);
        $asesmen->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Data asesmen berhasil dihapus.'
        ]);
    }
}
