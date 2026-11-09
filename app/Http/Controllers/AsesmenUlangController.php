<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AsesmenUlang;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AsesmenUlangController extends Controller
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

        // Get asesmen ulang count per pendaftaran
        $asulCounts = AsesmenUlang::select('pendaftaran_id', DB::raw('count(*) as total'))
            ->whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->groupBy('pendaftaran_id')
            ->pluck('total', 'pendaftaran_id');

        return view('rekam_medis.asesmen_ulang.index', compact('pendaftarans', 'asulCounts', 'tanggal', 'totalCount'));
    }

    public function show($pendaftaran_id)
    {
        // Menampilkan daftar CPPT untuk satu pendaftaran
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        $asesmens = AsesmenUlang::with('pegawai')->where('pendaftaran_id', $pendaftaran_id)->orderBy('tanggal_asesmen', 'desc')->get();
        
        return view('rekam_medis.asesmen_ulang.show', compact('pendaftaran', 'asesmens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'subjektif'      => 'required',
            'objektif'       => 'required',
            'asesmen'        => 'required',
            'plan'           => 'required',
        ]);

        try {
            $asul = AsesmenUlang::create([
                'pendaftaran_id'  => $request->pendaftaran_id,
                'pasien_id'       => $request->pasien_id,
                'tanggal_asesmen' => now(),
                'subjektif'       => $request->subjektif,
                'objektif'        => $request->objektif,
                'asesmen'         => $request->asesmen,
                'plan'            => $request->plan,
                'instruksi_medis' => $request->instruksi_medis,
                'pegawai_id'      => $request->pegawai_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan Perkembangan Pasien berhasil disimpan.',
                'data'    => $asul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $asul = AsesmenUlang::findOrFail($id);
        return response()->json($asul);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subjektif' => 'required',
            'objektif'  => 'required',
            'asesmen'   => 'required',
            'plan'      => 'required',
        ]);

        try {
            $asul = AsesmenUlang::findOrFail($id);
            $asul->update([
                'subjektif'       => $request->subjektif,
                'objektif'        => $request->objektif,
                'asesmen'         => $request->asesmen,
                'plan'            => $request->plan,
                'instruksi_medis' => $request->instruksi_medis,
                'pegawai_id'      => $request->pegawai_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan Perkembangan Pasien berhasil diperbarui.',
                'data'    => $asul
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            AsesmenUlang::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data asesmen ulang berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
