<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Anc;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class AncController extends Controller
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

        $ancs = Anc::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))->get()->keyBy('pendaftaran_id');

        return view('rekam_medis.anc.index', compact('pendaftarans', 'ancs', 'tanggal', 'totalCount'));
    }

    public function createAnc(Request $request)
    {
        if ($request->filled('kode_registrasi')) {
            $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])
                ->where('nomor_registrasi', $request->kode_registrasi)
                ->firstOrFail();
            return redirect('/anc/create/' . $pendaftaran->id);
        } elseif ($request->filled('pendaftaran_id')) {
            return redirect('/anc/create/' . $request->pendaftaran_id);
        }

        return redirect('/anc/index');
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $anc = Anc::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($anc) {
            return redirect('/anc/' . $anc->id . '/edit');
        }

        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();

        return view('rekam_medis.anc.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Anc::create([
                'pendaftaran_id'    => $request->pendaftaran_id,
                'pasien_id'         => $request->pasien_id,
                'dokter_id'         => $request->dokter_id,
                'hpht'              => $request->hpht,
                'hpl'               => $request->hpl,
                'usia_kehamilan'    => $request->usia_kehamilan,
                'gpa'               => $request->gpa,
                'tensi'             => $request->tensi,
                'bb'                => $request->bb,
                'tb'                => $request->tb,
                'lila'              => $request->lila,
                'tfu'               => $request->tfu,
                'djj'               => $request->djj,
                'letak_janin'       => $request->letak_janin,
                'imunisasi_tt'      => $request->imunisasi_tt,
                'keluhan'           => $request->keluhan,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/anc/index')->with('success', 'Data Ante Natal Care (ANC) berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $anc = Anc::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $anc->pendaftaran;
        $dokters = Pegawai::where('jenis_pegawai', 'Dokter')->get();
        
        return view('rekam_medis.anc.edit', compact('anc', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $anc = Anc::findOrFail($id);
            
            $anc->update([
                'dokter_id'         => $request->dokter_id,
                'hpht'              => $request->hpht,
                'hpl'               => $request->hpl,
                'usia_kehamilan'    => $request->usia_kehamilan,
                'gpa'               => $request->gpa,
                'tensi'             => $request->tensi,
                'bb'                => $request->bb,
                'tb'                => $request->tb,
                'lila'              => $request->lila,
                'tfu'               => $request->tfu,
                'djj'               => $request->djj,
                'letak_janin'       => $request->letak_janin,
                'imunisasi_tt'      => $request->imunisasi_tt,
                'keluhan'           => $request->keluhan,
                'keterangan'        => $request->keterangan,
            ]);

            return redirect('/anc/index')->with('success', 'Data Ante Natal Care (ANC) berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Anc::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ANC berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
