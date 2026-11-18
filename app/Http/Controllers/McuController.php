<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mcu;
use App\Models\Pendaftaran;
use App\Models\Pegawai;
use App\Models\Penjamin;
use App\Models\Poliklinik;
use App\Models\Ruangan;
use Carbon\Carbon;

class McuController extends Controller
{
    /**
     * Halaman Modul PPA Medical Checkup (1:1 KONHAKES /moduldokter/index-mcu)
     */
    public function index(Request $request)
    {
        $defaultTgl = '2026-10-14';
        
        $tanggal = $request->input('tanggal', '14-10-2026');
        $sdTanggal = $request->input('sd_tanggal', '14-10-2026');

        try {
            $parsedTglAwal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            $parsedTglAwal = $defaultTgl;
            $tanggal = '14-10-2026';
        }

        try {
            $parsedTglAkhir = Carbon::createFromFormat('d-m-Y', $sdTanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            $parsedTglAkhir = $defaultTgl;
            $sdTanggal = '14-10-2026';
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereBetween('tanggal_daftar', [$parsedTglAwal, $parsedTglAkhir]);

        // Filter Penjamin
        if ($request->filled('penjamin')) {
            $query->where('penjamin_id', $request->penjamin);
        }

        // Filter Poli
        if ($request->filled('poli')) {
            $query->where('poliklinik_id', $request->poli);
        }

        // Filter Ruangan
        if ($request->filled('ruangan')) {
            $query->where('ruang_pemeriksaan', $request->ruangan);
        }

        // Filter No Registrasi
        if ($request->filled('no_registrasi')) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_registrasi', 'like', '%' . $request->no_registrasi . '%')
                  ->orWhere('no_rawat', 'like', '%' . $request->no_registrasi . '%');
            });
        }

        // Filter Kode RM
        if ($request->filled('kode_rm')) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->where('no_rm', 'like', '%' . $request->kode_rm . '%');
            });
        }

        // Filter Nama Pasien
        if ($request->filled('nama_pasien')) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->nama_pasien . '%');
            });
        }

        // Filter Batal
        if ($request->filled('pendaftaran_batal')) {
            $query->where('status_periksa', 'Batal');
        } else {
            $query->where('status_periksa', '!=', 'Batal');
        }

        // Jika tidak ada pencarian spesifik nomor registrasi/RM/nama,
        // batasi ke kunjungan MCU atau pendaftaran yang memiliki asesmen MCU
        $hasSpecificSearch = $request->filled('no_registrasi') || $request->filled('kode_rm') || $request->filled('nama_pasien');
        if (!$hasSpecificSearch) {
            $query->where(function($q) {
                $q->where('jenis_kunjungan', 'like', '%MCU%')
                  ->orWhereHas('poliklinik', function($qp) {
                      $qp->where('nama_poli', 'like', '%MCU%');
                  })
                  ->orWhereIn('id', Mcu::pluck('pendaftaran_id'));
            });
        }

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')->paginate(20)->withQueryString();

        // Ambil data MCU
        $mcus = Mcu::whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->get()
            ->keyBy('pendaftaran_id');

        $listPenjamin = Penjamin::where('is_active', 1)->orWhereNull('is_active')->get();
        if ($listPenjamin->isEmpty()) {
            $listPenjamin = Penjamin::all();
        }
        $listPoli = Poliklinik::all();
        $listRuangan = Ruangan::all();

        return view('rekam_medis.mcu.index', compact(
            'pendaftarans',
            'mcus',
            'tanggal',
            'sdTanggal',
            'listPenjamin',
            'listPoli',
            'listRuangan'
        ));
    }

    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $mcu = Mcu::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($mcu) {
            return redirect('/rekam-medis/mcu/edit/' . $mcu->id);
        }

        $dokters = Pegawai::where('is_active', true)->where('nama_pegawai', 'like', '%dr.%')->get();
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::where('is_active', true)->get();
        }

        return view('rekam_medis.mcu.create', compact('pendaftaran', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id'      => 'required|exists:pasiens,id',
        ]);

        try {
            Mcu::create($request->all());
            return redirect('/moduldokter/index-mcu')->with('success', 'Data Pemeriksaan MCU berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $mcu = Mcu::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $mcu->pendaftaran;
        
        $dokters = Pegawai::where('is_active', true)->where('nama_pegawai', 'like', '%dr.%')->get();
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::where('is_active', true)->get();
        }

        return view('rekam_medis.mcu.edit', compact('mcu', 'pendaftaran', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        try {
            $mcu = Mcu::findOrFail($id);
            $mcu->update($request->all());
            
            return redirect('/moduldokter/index-mcu')->with('success', 'Data Pemeriksaan MCU berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
