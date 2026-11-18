<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skrining;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Carbon\Carbon;

class SkriningController extends Controller
{
    /**
     * Halaman Utama: Pendaftaran Skrining (Screenshot 1: /skrining/index)
     */
    public function index(Request $request)
    {
        $query = Skrining::with(['pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_skrining', 'like', "%{$keyword}%")
                  ->orWhereHas('pasien', function($qp) use ($keyword) {
                      $qp->where('nama_pasien', 'like', "%{$keyword}%")
                         ->orWhere('no_rm', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $skrinings = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('rekam_medis.skrining.index', compact('skrinings'));
    }

    /**
     * Halaman: Pilih Peserta Skrining dari Pendaftaran Front Office (Screenshot 3: /skrining/index-register)
     */
    public function indexRegister(Request $request)
    {
        // Default tanggal 14-10-2026 jika tidak ada input
        $defaultTgl = '2026-10-14';
        
        $tglAwal = $request->input('tgl_awal', '14-10-2026');
        $tglAkhir = $request->input('tgl_akhir', '14-10-2026');

        try {
            $parsedTglAwal = Carbon::createFromFormat('d-m-Y', $tglAwal)->format('Y-m-d');
        } catch (\Exception $e) {
            $parsedTglAwal = $defaultTgl;
            $tglAwal = '14-10-2026';
        }

        try {
            $parsedTglAkhir = Carbon::createFromFormat('d-m-Y', $tglAkhir)->format('Y-m-d');
        } catch (\Exception $e) {
            $parsedTglAkhir = $defaultTgl;
            $tglAkhir = '14-10-2026';
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereBetween('tanggal_daftar', [$parsedTglAwal, $parsedTglAkhir]);

        // Filter Penjamin
        if ($request->filled('penjamin')) {
            $query->where(function($q) use ($request) {
                $q->where('penjamin_id', $request->penjamin)
                  ->orWhereHas('penjamin', function($qp) use ($request) {
                      $qp->where('nama_penjamin', 'like', '%' . $request->penjamin . '%');
                  });
            });
        }

        // Filter Klinik
        if ($request->filled('klinik')) {
            $query->where(function($q) use ($request) {
                $q->where('poliklinik_id', $request->klinik)
                  ->orWhereHas('poliklinik', function($qp) use ($request) {
                      $qp->where('nama_poli', 'like', '%' . $request->klinik . '%');
                  });
            });
        }

        // Filter Jenis Kunjungan
        if ($request->filled('jenis_kunjungan')) {
            $query->where('jenis_kunjungan', $request->jenis_kunjungan);
        }

        // Filter Reg Online
        if ($request->filled('reg_online')) {
            $query->where('is_online', 1);
        }

        // Filter Kode Reg
        if ($request->filled('kode_reg')) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_registrasi', 'like', '%' . $request->kode_reg . '%')
                  ->orWhere('no_rawat', 'like', '%' . $request->kode_reg . '%');
            });
        }

        // Filter Kode EMR / No RM
        if ($request->filled('kode_emr')) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->where('no_rm', 'like', '%' . $request->kode_emr . '%');
            });
        }

        // Filter Nama Pasien
        if ($request->filled('nama_pasien')) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . $request->nama_pasien . '%');
            });
        }

        // Filter Tgl.Lahir
        if ($request->filled('tgl_lahir')) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->where('tanggal_lahir', 'like', '%' . $request->tgl_lahir . '%');
            });
        }

        // Sort descending by nomor_registrasi agar urutan RG26101400118 dll rapi
        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')->paginate(20)->withQueryString();

        $listPenjamin = Penjamin::where('is_active', 1)->orWhereNull('is_active')->get();
        if ($listPenjamin->isEmpty()) {
            $listPenjamin = Penjamin::all();
        }
        $listKlinik = Poliklinik::all();

        return view('rekam_medis.skrining.index_register', compact(
            'pendaftarans',
            'listPenjamin',
            'listKlinik',
            'tglAwal',
            'tglAkhir'
        ));
    }

    /**
     * Halaman: Pilih Peserta Skrining Tanpa Front Office (/skrining/index-pasien)
     */
    public function indexPasien(Request $request)
    {
        $query = Pasien::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('nama_pasien', 'like', "%{$s}%")
                  ->orWhere('no_rm', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('no_bpjs', 'like', "%{$s}%");
            });
        }

        $pasiens = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('rekam_medis.skrining.index_pasien', compact('pasiens'));
    }

    /**
     * Form Tambah Skrining berdasarkan Pendaftaran
     */
    public function create($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter'])->findOrFail($pendaftaran_id);
        
        $skrining = Skrining::where('pendaftaran_id', $pendaftaran_id)->first();
        if ($skrining) {
            return redirect('/skrining/edit/' . $skrining->id);
        }

        return view('rekam_medis.skrining.create', compact('pendaftaran'));
    }

    /**
     * Form Tambah Skrining langsung untuk Pasien (tanpa pendaftaran FO)
     */
    public function createPasien($pasien_id)
    {
        $pasien = Pasien::findOrFail($pasien_id);
        $pendaftaran = null;

        return view('rekam_medis.skrining.create', compact('pasien', 'pendaftaran'));
    }

    /**
     * Simpan Data Skrining
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
        ]);

        try {
            $data = $request->all();
            if (empty($data['nama_skrining'])) {
                $data['nama_skrining'] = 'Skrining Visual / Umum';
            }
            if (empty($data['status'])) {
                $data['status'] = 'Selesai';
            }

            Skrining::create($data);
            return redirect('/skrining/index')->with('success', 'Data Pendaftaran Skrining berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Form Edit Skrining
     */
    public function edit($id)
    {
        $skrining = Skrining::with(['pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->findOrFail($id);
        $pendaftaran = $skrining->pendaftaran;
        $pasien = $skrining->pasien;

        return view('rekam_medis.skrining.edit', compact('skrining', 'pendaftaran', 'pasien'));
    }

    /**
     * Update Data Skrining
     */
    public function update(Request $request, $id)
    {
        try {
            $skrining = Skrining::findOrFail($id);
            $skrining->update($request->all());
            
            return redirect('/skrining/index')->with('success', 'Data Skrining Pasien berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Data Skrining
     */
    public function destroy($id)
    {
        try {
            $skrining = Skrining::findOrFail($id);
            $skrining->delete();
            return redirect('/skrining/index')->with('success', 'Data Skrining berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Export Excel Data Skrining
     */
    public function exportExcel(Request $request)
    {
        $skrinings = Skrining::with(['pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter'])->orderBy('id', 'desc')->get();

        $filename = 'data_skrining_' . date('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['No', 'Tgl & Jam', 'Nama Skrining', 'No. RM', 'Nama Pasien', 'J.K', 'Tgl Lahir', 'Nyeri', 'Resiko Jatuh', 'Gizi', 'Alergi', 'Status']);

        foreach ($skrinings as $idx => $s) {
            $p = $s->pasien;
            fputcsv($handle, [
                $idx + 1,
                $s->created_at ? $s->created_at->format('d/m/Y H:i') : '-',
                $s->nama_skrining ?: 'Skrining Visual / Umum',
                $p->no_rm ?? '-',
                $p->nama_pasien ?? '-',
                $p->jenis_kelamin ?? '-',
                $p->tanggal_lahir ?? '-',
                $s->skrining_nyeri ? ($s->skrining_nyeri . ' (Skor: ' . $s->skor_nyeri . ')') : '-',
                $s->resiko_jatuh ?? '-',
                $s->skrining_gizi ?? '-',
                $s->status_alergi ?? '-',
                $s->status ?? 'Selesai',
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Halaman: Pengecekan Skrining Pasien (Screenshot KONHAKES: /skrining/index-cek)
     */
    public function indexCek(Request $request)
    {
        $query = Pasien::with('skrinings');

        if ($request->filled('kode_rm')) {
            $query->where('no_rm', 'like', '%' . $request->kode_rm . '%');
        }
        if ($request->filled('nama_pasien')) {
            $query->where('nama_pasien', 'like', '%' . $request->nama_pasien . '%');
        }
        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . $request->nik . '%');
        }
        if ($request->filled('tanggal_lahir')) {
            $query->where('tanggal_lahir', 'like', '%' . $request->tanggal_lahir . '%');
        }
        if ($request->filled('nomor_kis')) {
            $query->where('no_bpjs', 'like', '%' . $request->nomor_kis . '%');
        }
        if ($request->filled('provinsi')) {
            $query->where('provinsi', 'like', '%' . $request->provinsi . '%');
        }
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
        }
        if ($request->filled('kel_desa')) {
            $query->where('desa', 'like', '%' . $request->kel_desa . '%');
        }
        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', '%' . $request->alamat . '%');
        }
        if ($request->filled('sasaran_kluster')) {
            $kluster = $request->sasaran_kluster;
            if (stripos($kluster, 'Balita') !== false || stripos($kluster, 'Anak') !== false && stripos($kluster, 'Remaja') === false) {
                $query->whereDate('tanggal_lahir', '>=', Carbon::now()->subYears(6));
            } elseif (stripos($kluster, 'Remaja') !== false || stripos($kluster, 'Sekolah') !== false) {
                $query->whereDate('tanggal_lahir', '<', Carbon::now()->subYears(6))
                      ->whereDate('tanggal_lahir', '>=', Carbon::now()->subYears(18));
            } elseif (stripos($kluster, 'Dewasa') !== false) {
                $query->whereDate('tanggal_lahir', '<', Carbon::now()->subYears(18))
                      ->whereDate('tanggal_lahir', '>=', Carbon::now()->subYears(60));
            } elseif (stripos($kluster, 'Lansia') !== false) {
                $query->whereDate('tanggal_lahir', '<', Carbon::now()->subYears(60));
            }
        }

        $sortField = $request->input('sort', 'nama_pasien');
        $sortOrder = $request->input('order', 'asc');
        if (!in_array($sortField, ['nama_pasien', 'no_rm', 'tanggal_lahir'])) {
            $sortField = 'nama_pasien';
        }
        $query->orderBy($sortField, $sortOrder);

        $pasiens = $query->paginate(20)->withQueryString();

        $provinces = Pasien::whereNotNull('provinsi')->where('provinsi', '!=', '')->distinct()->pluck('provinsi');
        $kabupatens = Pasien::whereNotNull('kabupaten')->where('kabupaten', '!=', '')->distinct()->pluck('kabupaten');
        $kecamatans = Pasien::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->pluck('kecamatan');
        $desas = Pasien::whereNotNull('desa')->where('desa', '!=', '')->distinct()->pluck('desa');

        return view('rekam_medis.skrining.index_cek', compact(
            'pasiens', 'provinces', 'kabupatens', 'kecamatans', 'desas'
        ));
    }

    /**
     * Ringkasan & Cek Status Skrining Pasien (Legacy Overview)
     */
    public function status(Request $request)
    {
        return $this->indexCek($request);
    }
}
