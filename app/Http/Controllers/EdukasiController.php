<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Edukasi;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use App\Models\Penjamin;

class EdukasiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', '');
        $keyword = $request->input('keyword', '');
        $poliklinikId = $request->input('poliklinik_id', '');
        $penjaminId = $request->input('penjamin_id', '');

        // Secara default pada PROFASKES jika dibuka tanpa filter pencarian, tampil kosong
        if (!$request->has('tanggal') && !$request->has('keyword') && !$request->has('poliklinik_id') && !$request->has('penjamin_id')) {
            $pendaftarans = Pendaftaran::whereRaw('1 = 0')->paginate(20);
            $edukasis = collect();
        } else {
            $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin']);

            if (!empty($tanggal)) {
                $rawTanggal = $tanggal;
                if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $rawTanggal)) {
                    $tgl = \Carbon\Carbon::createFromFormat('d-m-Y', $rawTanggal)->format('Y-m-d');
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawTanggal)) {
                    $tgl = $rawTanggal;
                } else {
                    $tgl = date('Y-m-d', strtotime($rawTanggal));
                }
                $query->whereDate('tanggal_daftar', $tgl);
            }

            if (!empty($keyword)) {
                $query->where(function($q) use ($keyword) {
                    $q->where('nomor_registrasi', 'like', "%{$keyword}%")
                      ->orWhere('no_rawat', 'like', "%{$keyword}%")
                      ->orWhereHas('pasien', function($pq) use ($keyword) {
                          $pq->where('nama_pasien', 'like', "%{$keyword}%")
                             ->orWhere('no_rm', 'like', "%{$keyword}%");
                      });
                });
            }

            if (!empty($poliklinikId)) {
                $query->where('poliklinik_id', $poliklinikId);
            }

            if (!empty($penjaminId)) {
                $query->where('penjamin_id', $penjaminId);
            }

            $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(20)
                ->withQueryString();

            $edukasis = Edukasi::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
                ->get()
                ->keyBy('pendaftaran_id');
        }

        $listKlinik = Poliklinik::orderBy('nama_poli')->get();
        $listPenjamin = Penjamin::orderBy('nama_penjamin')->get();

        return view('rekam_medis.edukasi.index', compact(
            'pendaftarans',
            'edukasis',
            'tanggal',
            'keyword',
            'poliklinikId',
            'penjaminId',
            'listKlinik',
            'listPenjamin'
        ));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $edukasi = Edukasi::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($edukasi) {
            return redirect('/edukasi/edit/' . $edukasi->id);
        }

        $pegawais = Pegawai::where('is_active', true)->get();

        return view('rekam_medis.edukasi.create', compact('pendaftaran', 'pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
            'waktu_edukasi'  => 'required',
            'materi_edukasi' => 'required'
        ]);

        try {
            Edukasi::create($request->all());
            return redirect('/edukasi/index')->with('success', 'Data Edukasi Pasien berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $edukasi = Edukasi::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $edukasi->pendaftaran;
        
        $pegawais = Pegawai::where('is_active', true)->get();

        return view('rekam_medis.edukasi.edit', compact('edukasi', 'pendaftaran', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'waktu_edukasi'  => 'required',
            'materi_edukasi' => 'required'
        ]);

        try {
            $edukasi = Edukasi::findOrFail($id);
            $edukasi->update($request->all());
            
            return redirect('/edukasi/index')->with('success', 'Data Edukasi Pasien berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Edukasi::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Edukasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
