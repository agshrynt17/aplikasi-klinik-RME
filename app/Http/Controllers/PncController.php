<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pnc;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Penjamin;

class PncController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan sample baseline data 14-10-2026 tersedia jika belum ada (sesuai PROFASKES)
        if (!Pendaftaran::where('nomor_registrasi', 'RG26101400118')->exists()) {
            $pasien1 = Pasien::firstOrCreate(['no_rm' => 'S2023-000183'], [
                'nama_pasien' => 'SDR IRFANSYAH NAUFAL AEPPUTRA',
                'tanggal_lahir' => '2000-05-21',
                'jenis_kelamin' => 'L',
                'no_bpjs' => '0001811692824',
                'alamat' => 'Karawang',
                'alergi_obat' => null
            ]);

            Pendaftaran::create([
                'no_rawat' => 'RG26101400118',
                'nomor_registrasi' => 'RG26101400118',
                'pasien_id' => $pasien1->id,
                'tanggal_daftar' => '2026-10-14',
                'jam_daftar' => '14:45:00',
                'poliklinik_id' => 6,
                'dokter_id' => 25,
                'nama_perawat' => 'LALA WIJAYANTI AMD.KEB',
                'penjamin_id' => 10,
                'jenis_kunjungan' => 'Kunjungan Sakit',
                'status_periksa' => 'Dalam Proses',
                'is_online' => true,
                'keluhan' => 'Kontrol PNC'
            ]);
        }

        if (!Pendaftaran::where('nomor_registrasi', 'RG26101400117')->exists()) {
            $pasien2 = Pasien::firstOrCreate(['no_rm' => 'N2025-001265'], [
                'nama_pasien' => 'NY NENG YANAH',
                'tanggal_lahir' => '1971-06-12',
                'jenis_kelamin' => 'P',
                'no_bpjs' => '0001811692825',
                'alamat' => 'Karawang',
                'alergi_obat' => 'RANITIDINE,'
            ]);

            Pendaftaran::create([
                'no_rawat' => 'RG26101400117',
                'nomor_registrasi' => 'RG26101400117',
                'pasien_id' => $pasien2->id,
                'tanggal_daftar' => '2026-10-14',
                'jam_daftar' => '14:40:00',
                'poliklinik_id' => 6,
                'dokter_id' => 25,
                'nama_perawat' => 'LALA WIJAYANTI AMD.KEB',
                'penjamin_id' => 10,
                'jenis_kunjungan' => 'Kunjungan Sakit',
                'status_periksa' => 'Dalam Proses',
                'is_online' => true,
                'keluhan' => 'Pemeriksaan Nifas'
            ]);
        }

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

        $pncs = Pnc::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->get()
            ->keyBy('pendaftaran_id');

        $listKlinik = Poliklinik::orderBy('nama_poli')->get();
        $listPenjamin = Penjamin::orderBy('nama_penjamin')->get();

        return view('rekam_medis.pnc.index', compact(
            'pendaftarans',
            'pncs',
            'tanggal',
            'listKlinik',
            'listPenjamin'
        ));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        $pnc = Pnc::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($pnc) {
            return redirect('/pnc/edit/' . $pnc->id);
        }

        return view('rekam_medis.pnc.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Pnc::create($request->all());
            return redirect('/pnc/index')->with('success', 'Data Pelayanan Nifas (PNC) berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $pnc = Pnc::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter', 'pendaftaran.penjamin'])->findOrFail($id);
        $pendaftaran = $pnc->pendaftaran;

        return view('rekam_medis.pnc.edit', compact('pnc', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        try {
            $pnc = Pnc::findOrFail($id);
            $pnc->update($request->all());
            
            return redirect('/pnc/index')->with('success', 'Data Pelayanan Nifas (PNC) berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Pnc::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data PNC berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

