<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AsesmenIgd;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;

class AsesmenIgdController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai context PROFASKES
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

        $asesmens = AsesmenIgd::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.asesmen_igd.index', compact('pendaftarans', 'asesmens', 'tanggal', 'totalCount'));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $asesmen = AsesmenIgd::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($asesmen) {
            return redirect('/asigd/' . $asesmen->id . '/edit');
        }

        return view('rekam_medis.asesmen_igd.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            AsesmenIgd::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'tanggal_asesmen'   => now(),
                'skala_triage'      => $request->skala_triage,
                'jalan_napas'       => $request->jalan_napas,
                'pernapasan'        => $request->pernapasan,
                'sirkulasi'         => $request->sirkulasi,
                'kesadaran'         => $request->kesadaran,
                'keluhan_utama'     => $request->keluhan_utama,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'diagnosis'         => $request->diagnosis,
                'tindakan_rencana'  => $request->tindakan_rencana,
                'dokter_id'         => $request->dokter_id,
            ]);

            return redirect('/asigd/index')->with('success', 'Asesmen IGD berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $asesmen = AsesmenIgd::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $asesmen->pendaftaran;
        
        return view('rekam_medis.asesmen_igd.edit', compact('asesmen', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $asesmen = AsesmenIgd::findOrFail($id);
            $asesmen->update([
                'skala_triage'      => $request->skala_triage,
                'jalan_napas'       => $request->jalan_napas,
                'pernapasan'        => $request->pernapasan,
                'sirkulasi'         => $request->sirkulasi,
                'kesadaran'         => $request->kesadaran,
                'keluhan_utama'     => $request->keluhan_utama,
                'riwayat_penyakit'  => $request->riwayat_penyakit,
                'tensi'             => $request->tensi,
                'nadi'              => $request->nadi,
                'suhu'              => $request->suhu,
                'rr'                => $request->rr,
                'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
                'diagnosis'         => $request->diagnosis,
                'tindakan_rencana'  => $request->tindakan_rencana,
                'dokter_id'         => $request->dokter_id,
            ]);

            return redirect('/asigd/index')->with('success', 'Asesmen IGD berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            AsesmenIgd::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data asesmen IGD berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
