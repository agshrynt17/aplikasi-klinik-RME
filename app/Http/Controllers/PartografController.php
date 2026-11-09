<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Partograf;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class PartografController extends Controller
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

        $partografs = Partograf::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.partograf.index', compact('pendaftarans', 'partografs', 'tanggal', 'totalCount'));
    }

    public function createPartograf(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/partograf/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/partograf/create/' . $request->pendaftaran_id);
        }

        return redirect('/partograf/index');
    }

    public function show($pendaftaran_id)
    {
        $partograf = Partograf::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($partograf) {
            return redirect('/partograf/edit/' . $partograf->id);
        }
        return redirect('/partograf/create/' . $pendaftaran_id);
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $partograf = Partograf::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($partograf) {
            return redirect('/partograf/edit/' . $partograf->id);
        }

        $petugas = Pegawai::where('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('fungsional_medis', 'like', '%Bidan%')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->orWhere('jabatan', 'like', '%Bidan%')
            ->get();
        if ($petugas->isEmpty()) {
            $petugas = Pegawai::all();
        }

        return view('rekam_medis.partograf.create', compact('pendaftaran', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Partograf::create([
                'pendaftaran_id'      => $request->pendaftaran_id,
                'pasien_id'           => $request->pasien_id,
                'petugas_id'          => $request->petugas_id,
                'waktu_mulas'         => $request->waktu_mulas,
                'waktu_ketuban_pecah' => $request->waktu_ketuban_pecah,
                'gpa'                 => $request->gpa,
                'usia_kehamilan'      => $request->usia_kehamilan,
                'observasi'           => $request->observasi ? (is_array($request->observasi) ? $request->observasi : json_decode($request->observasi, true)) : null,
                'catatan_persalinan'  => $request->catatan_persalinan,
            ]);

            return redirect('/partograf/index')->with('success', 'Data Partograf berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $partograf = Partograf::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $partograf->pendaftaran;
        
        $petugas = Pegawai::where('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('fungsional_medis', 'like', '%Bidan%')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->orWhere('jabatan', 'like', '%Bidan%')
            ->get();
        if ($petugas->isEmpty()) {
            $petugas = Pegawai::all();
        }

        return view('rekam_medis.partograf.edit', compact('partograf', 'pendaftaran', 'petugas'));
    }

    public function update(Request $request, $id)
    {
        try {
            $partograf = Partograf::findOrFail($id);
            
            $partograf->update([
                'petugas_id'          => $request->petugas_id,
                'waktu_mulas'         => $request->waktu_mulas,
                'waktu_ketuban_pecah' => $request->waktu_ketuban_pecah,
                'gpa'                 => $request->gpa,
                'usia_kehamilan'      => $request->usia_kehamilan,
                'observasi'           => $request->observasi ? (is_array($request->observasi) ? $request->observasi : json_decode($request->observasi, true)) : $partograf->observasi,
                'catatan_persalinan'  => $request->catatan_persalinan,
            ]);

            return redirect('/partograf/index')->with('success', 'Data Partograf berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Partograf::findOrFail($id)->delete();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Partograf berhasil dihapus.'
                ]);
            }
            return redirect('/partograf/index')->with('success', 'Data Partograf berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

