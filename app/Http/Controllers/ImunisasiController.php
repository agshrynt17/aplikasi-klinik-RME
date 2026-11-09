<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Imunisasi;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use App\Models\Penjamin;

class ImunisasiController extends Controller
{
    public function index(Request $request)
    {
        $rawTanggal = $request->input('tanggal', '2026-10-14');
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $rawTanggal)) {
            $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $rawTanggal)->format('Y-m-d');
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawTanggal)) {
            $tanggal = $rawTanggal;
        } else {
            $tanggal = date('Y-m-d', strtotime($rawTanggal));
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereDate('tanggal_daftar', $tanggal);

        if ($request->filled('keyword')) {
            $kw = $request->input('keyword');
            $query->where(function($q) use ($kw) {
                $q->where('nomor_registrasi', 'like', "%{$kw}%")
                  ->orWhere('no_rawat', 'like', "%{$kw}%")
                  ->orWhereHas('pasien', function($pq) use ($kw) {
                      $pq->where('nama_pasien', 'like', "%{$kw}%")
                         ->orWhere('no_rm', 'like', "%{$kw}%")
                         ->orWhere('nik', 'like', "%{$kw}%");
                  });
            });
        }

        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->input('poliklinik_id'));
        }

        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->input('penjamin_id'));
        }

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $imunisasis = Imunisasi::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->get()
            ->keyBy('pendaftaran_id');

        $listKlinik = Poliklinik::orderBy('nama_poli')->get();
        $listPenjamin = Penjamin::orderBy('nama_penjamin')->get();

        return view('rekam_medis.imunisasi.index', compact(
            'pendaftarans',
            'imunisasis',
            'tanggal',
            'listKlinik',
            'listPenjamin'
        ));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        $imunisasi = Imunisasi::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($imunisasi) {
            return redirect('/imunisasi/edit/' . $imunisasi->id);
        }

        $pegawais = Pegawai::where('is_active', true)->get();

        return view('rekam_medis.imunisasi.create', compact('pendaftaran', 'pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id'    => 'required|exists:pendaftarans,id',
            'pasien_id'         => 'required|exists:pasiens,id',
            'tanggal_imunisasi' => 'required|date',
            'jenis_imunisasi'   => 'required',
        ]);

        try {
            Imunisasi::create($request->all());
            return redirect('/imunisasi/index')->with('success', 'Data Imunisasi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $imunisasi = Imunisasi::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter', 'pendaftaran.penjamin'])->findOrFail($id);
        $pendaftaran = $imunisasi->pendaftaran;
        
        $pegawais = Pegawai::where('is_active', true)->get();

        return view('rekam_medis.imunisasi.edit', compact('imunisasi', 'pendaftaran', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_imunisasi' => 'required|date',
            'jenis_imunisasi'   => 'required',
        ]);

        try {
            $imunisasi = Imunisasi::findOrFail($id);
            $imunisasi->update($request->all());
            
            return redirect('/imunisasi/index')->with('success', 'Data Imunisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Imunisasi::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Imunisasi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
