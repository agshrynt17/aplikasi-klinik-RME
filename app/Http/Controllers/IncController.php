<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inc;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class IncController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin'])
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

        $incs = Inc::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
                    ->get()
                    ->keyBy('pendaftaran_id');

        return view('rekam_medis.inc.index', compact(
            'pendaftarans',
            'incs',
            'tanggal',
            'totalCount'
        ));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $inc = Inc::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($inc) {
            return redirect('/inc/edit/' . $inc->id);
        }

        $petugass = Pegawai::where('jenis_pegawai', 'Dokter')
            ->orWhere('jenis_pegawai', 'Bidan')
            ->orWhere('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('fungsional_medis', 'like', '%Bidan%')
            ->get();
        if ($petugass->isEmpty()) {
            $petugass = Pegawai::all();
        }

        return view('rekam_medis.inc.create', compact('pendaftaran', 'petugass'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Inc::create($request->all());
            return redirect('/inc/index')->with('success', 'Data Intra Natal Care (INC) berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $inc = Inc::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $inc->pendaftaran;
        
        $petugass = Pegawai::where('jenis_pegawai', 'Dokter')
            ->orWhere('jenis_pegawai', 'Bidan')
            ->orWhere('fungsional_medis', 'like', '%Dokter%')
            ->orWhere('fungsional_medis', 'like', '%Bidan%')
            ->get();
        if ($petugass->isEmpty()) {
            $petugass = Pegawai::all();
        }

        return view('rekam_medis.inc.edit', compact('inc', 'pendaftaran', 'petugass'));
    }

    public function update(Request $request, $id)
    {
        try {
            $inc = Inc::findOrFail($id);
            $inc->update($request->all());
            
            return redirect('/inc/index')->with('success', 'Data Intra Natal Care (INC) berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Inc::findOrFail($id)->delete();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Intra Natal Care (INC) berhasil dihapus.'
                ]);
            }
            return redirect('/inc/index')->with('success', 'Data Intra Natal Care (INC) berhasil dihapus.');
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
