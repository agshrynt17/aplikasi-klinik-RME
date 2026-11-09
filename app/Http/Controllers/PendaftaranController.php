<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Pegawai;
use App\Models\Penjamin;
use App\Models\Tpa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Tampilan Utama Data Pendaftaran Rawat Jalan (Screenshot 1)
     */
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'tpa']);

        // Filter Tanggal (jika diisi, default tidak membatasi atau hari ini jika dipilih)
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }

        // Filter Pencarian
        if ($request->filled('nomor_registrasi')) {
            $query->where('nomor_registrasi', 'like', '%' . trim($request->nomor_registrasi) . '%');
        }
        if ($request->filled('no_rm')) {
            $query->whereHas('pasien', function ($q) use ($request) {
                $q->where('no_rm', 'like', '%' . trim($request->no_rm) . '%');
            });
        }
        if ($request->filled('nama_pasien')) {
            $query->whereHas('pasien', function ($q) use ($request) {
                $q->where('nama_pasien', 'like', '%' . trim($request->nama_pasien) . '%');
            });
        }
        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }
        if ($request->filled('q')) {
            $kw = trim($request->q);
            $query->where(function ($q) use ($kw) {
                $q->where('nomor_registrasi', 'like', "%{$kw}%")
                  ->orWhere('no_rawat', 'like', "%{$kw}%")
                  ->orWhereHas('pasien', function ($p) use ($kw) {
                      $p->where('nama_pasien', 'like', "%{$kw}%")
                        ->orWhere('no_rm', 'like', "%{$kw}%")
                        ->orWhere('nik', 'like', "%{$kw}%")
                        ->orWhere('no_bpjs', 'like', "%{$kw}%")
                        ->orWhere('no_hp', 'like', "%{$kw}%");
                  });
            });
        }
        if ($request->filled('status_periksa')) {
            $query->where('status_periksa', $request->status_periksa);
        } elseif (!$request->hasAny(['nomor_registrasi', 'no_rm', 'nama_pasien', 'q'])) {
            // Default list: tampilkan semua yang bukan antrian murni tanpa data
            $query->where('status_periksa', '!=', 'Menunggu Antrian');
        }

        $pendaftarans = $query->orderBy('id', 'desc')
                              ->paginate(20)
                              ->withQueryString();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $dokters     = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();

        return view('front_office.pendaftaran', compact('pendaftarans', 'polikliniks', 'penjamins', 'dokters'));
    }

    /**
     * Halaman Tambah Pendaftaran Rawat Jalan (Screenshot 2)
     */
    public function create(Request $request)
    {
        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $selectedPasien = Pasien::find($request->pasien_id);
        }

        // Jika dipanggil dari Antrian Checkin
        $antrian = null;
        if ($request->filled('antrian_id')) {
            $antrian = Pendaftaran::find($request->antrian_id);
            if ($antrian && $antrian->pasien) {
                $selectedPasien = $antrian->pasien;
            }
        }

        // Generate No Registrasi otomatis RG{YYMMDD}{5-digit}
        $nextReg = $this->generateNextNomorRegistrasi();
        $nextAntrian = $antrian ? $antrian->no_antrian : $this->generateNextNoAntrian();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tpas        = Tpa::where('is_active', true)->orderBy('nama_tpa')->get();
        
        // Dokters & Perawats
        $dokters = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();
        $perawats = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();

        return view('front_office.pendaftaran_create', compact(
            'selectedPasien', 'antrian', 'nextReg', 'nextAntrian',
            'polikliniks', 'penjamins', 'tpas', 'dokters', 'perawats'
        ));
    }

    /**
     * Simpan Pendaftaran Baru (Screenshot 2)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'         => 'required|exists:pasiens,id',
            'tanggal_daftar'    => 'required|date',
            'poliklinik_id'     => 'required|exists:polikliniks,id',
            'dokter_id'         => 'required|exists:pegawais,id',
            'skrining_visual'   => 'required|string',
            'jenis_rawat'       => 'required|string',
            'jenis_kunjungan'   => 'required|string',
        ]);

        $noReg = $request->nomor_registrasi;
        if (empty($noReg)) {
            $noReg = $this->generateNextNomorRegistrasi();
        }

        $noRawat = $request->no_rawat ?: $noReg;

        // Ambil nama perawat jika perawat_id dipilih
        $namaPerawat = $request->nama_perawat;
        if ($request->filled('perawat_id') && empty($namaPerawat)) {
            $prw = Pegawai::find($request->perawat_id);
            $namaPerawat = $prw ? $prw->nama_pegawai : null;
        }

        $statusPeriksa = $request->status_periksa ?: 'Dalam Proses';

        $pendaftaran = Pendaftaran::create([
            'no_rawat'           => $noRawat,
            'nomor_registrasi'   => $noReg,
            'pasien_id'          => $request->pasien_id,
            'tanggal_daftar'     => $request->tanggal_daftar,
            'jam_daftar'         => Carbon::now()->format('H:i:s'),
            'skrining_visual'    => $request->skrining_visual,
            'jenis_rawat'        => $request->jenis_rawat,
            'is_kirim_pcare'     => $request->has('is_kirim_pcare') ? 1 : 0,
            'is_kirim_satusehat' => $request->has('is_kirim_satusehat') ? 1 : 0,
            'antrian_tipe'       => $request->antrian_tipe ?: 'Dengan Antrian',
            'no_antrian'         => $request->no_antrian,
            'penjamin_id'        => $request->penjamin_id,
            'tpa_id'             => $request->tpa_id,
            'no_bpjs_asuransi'   => $request->no_bpjs_asuransi,
            'jenis_kunjungan'    => $request->jenis_kunjungan,
            'poliklinik_id'      => $request->poliklinik_id,
            'ruang_pemeriksaan'  => $request->ruang_pemeriksaan ?: 'Ruang Rawat 1',
            'keluhan'            => $request->keluhan,
            'dokter_id'          => $request->dokter_id,
            'perawat_id'         => $request->perawat_id,
            'nama_perawat'       => $namaPerawat,
            'is_prb'             => $request->has('is_prb') ? 1 : 0,
            'is_online'          => $request->has('is_online') ? 1 : 0,
            'is_exclude_kasir'   => $request->has('is_exclude_kasir') ? 1 : 0,
            'status_periksa'     => $statusPeriksa,
            'status_antrol_bpjs' => $request->status_antrol_bpjs ?: 'Hadir',
            'status_pcare'       => $request->has('is_kirim_pcare') ? 'Baru Kirim' : 'Belum Kirim',
            'status_satusehat'   => $request->has('is_kirim_satusehat') ? 'In Progress' : 'Belum Kirim',
        ]);

        // Jika pendaftaran dibuat dari antrian yang ada, update status antrian
        if ($request->filled('antrian_id')) {
            $ant = Pendaftaran::find($request->antrian_id);
            if ($ant && $ant->id != $pendaftaran->id) {
                $ant->update([
                    'status_periksa' => 'Selesai',
                    'status_antrian' => 'Hadir',
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran rawat jalan berhasil disimpan.',
                'data'    => $pendaftaran,
            ]);
        }

        return redirect('/pendaftaran-rajal')->with('success', 'Pendaftaran No. ' . $pendaftaran->nomor_registrasi . ' berhasil disimpan.');
    }

    /**
     * Edit Pendaftaran
     */
    public function edit($id)
    {
        $item = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tpas        = Tpa::where('is_active', true)->orderBy('nama_tpa')->get();
        $dokters     = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();
        $perawats    = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();

        return view('front_office.pendaftaran_create', [
            'item'           => $item,
            'selectedPasien' => $item->pasien,
            'nextReg'        => $item->nomor_registrasi,
            'nextAntrian'    => $item->no_antrian,
            'polikliniks'    => $polikliniks,
            'penjamins'      => $penjamins,
            'tpas'           => $tpas,
            'dokters'        => $dokters,
            'perawats'       => $perawats,
            'isEdit'         => true,
        ]);
    }

    /**
     * Update Pendaftaran
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pasien_id'         => 'required|exists:pasiens,id',
            'tanggal_daftar'    => 'required|date',
            'poliklinik_id'     => 'required|exists:polikliniks,id',
            'dokter_id'         => 'required|exists:pegawais,id',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        $namaPerawat = $request->nama_perawat;
        if ($request->filled('perawat_id') && empty($namaPerawat)) {
            $prw = Pegawai::find($request->perawat_id);
            $namaPerawat = $prw ? $prw->nama_pegawai : null;
        }

        $pendaftaran->update([
            'pasien_id'          => $request->pasien_id,
            'tanggal_daftar'     => $request->tanggal_daftar,
            'skrining_visual'    => $request->skrining_visual ?: $pendaftaran->skrining_visual,
            'jenis_rawat'        => $request->jenis_rawat ?: $pendaftaran->jenis_rawat,
            'is_kirim_pcare'     => $request->has('is_kirim_pcare') ? 1 : 0,
            'is_kirim_satusehat' => $request->has('is_kirim_satusehat') ? 1 : 0,
            'antrian_tipe'       => $request->antrian_tipe ?: $pendaftaran->antrian_tipe,
            'no_antrian'         => $request->no_antrian ?: $pendaftaran->no_antrian,
            'penjamin_id'        => $request->penjamin_id,
            'tpa_id'             => $request->tpa_id,
            'no_bpjs_asuransi'   => $request->no_bpjs_asuransi,
            'jenis_kunjungan'    => $request->jenis_kunjungan ?: $pendaftaran->jenis_kunjungan,
            'poliklinik_id'      => $request->poliklinik_id,
            'ruang_pemeriksaan'  => $request->ruang_pemeriksaan ?: $pendaftaran->ruang_pemeriksaan,
            'keluhan'            => $request->keluhan,
            'dokter_id'          => $request->dokter_id,
            'perawat_id'         => $request->perawat_id,
            'nama_perawat'       => $namaPerawat,
            'is_prb'             => $request->has('is_prb') ? 1 : 0,
            'is_online'          => $request->has('is_online') ? 1 : 0,
            'is_exclude_kasir'   => $request->has('is_exclude_kasir') ? 1 : 0,
            'status_periksa'     => $request->status_periksa ?: $pendaftaran->status_periksa,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil diperbarui.',
                'data'    => $pendaftaran->fresh(),
            ]);
        }

        return redirect('/pendaftaran-rajal')->with('success', 'Pendaftaran ' . $pendaftaran->nomor_registrasi . ' berhasil diperbarui.');
    }

    /**
     * Hapus / Batalkan Pendaftaran
     */
    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $noReg = $pendaftaran->nomor_registrasi;
        $pendaftaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran ' . $noReg . ' berhasil dibatalkan / dihapus.',
        ]);
    }

    /**
     * Halaman Checkin Antrian Pendaftaran (Screenshot 3)
     */
    public function antrian(Request $request)
    {
        $tanggal = $request->get('tanggal_antrian', Carbon::now()->toDateString());

        // Ambil daftar antrian pada tanggal tersebut
        $antrians = Pendaftaran::with(['pasien', 'penjamin'])
            ->whereNotNull('no_antrian')
            ->whereDate('tanggal_daftar', $tanggal)
            ->orderBy('id', 'asc')
            ->get();

        // Antrian yang sedang dipanggil / aktif
        $activeAntrian = $antrians->where('status_antrian', 'Dipanggil')->first() 
                         ?? $antrians->where('status_antrian', 'Menunggu')->first()
                         ?? $antrians->first();

        return view('front_office.checkin_antrian', compact('antrians', 'activeAntrian', 'tanggal'));
    }

    /**
     * Aksi Panggil Antrian (Screenshot 3)
     */
    public function panggilAntrian(Request $request, $id)
    {
        $antrian = Pendaftaran::findOrFail($id);
        $antrian->update([
            'waktu_panggil'  => Carbon::now()->format('H:i:s'),
            'status_antrian' => 'Dipanggil',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Antrian ' . $antrian->no_antrian . ' berhasil dipanggil.',
            'data'    => $antrian->load('pasien'),
        ]);
    }

    /**
     * Aksi Pasien Hadir (Screenshot 3)
     */
    public function hadirAntrian(Request $request, $id)
    {
        $antrian = Pendaftaran::findOrFail($id);
        $antrian->update([
            'status_antrian'     => 'Hadir',
            'status_antrol_bpjs' => 'Hadir',
        ]);

        // Arahkan ke create pendaftaran dengan data antrian ini
        return redirect('/pendaftaran/create?antrian_id=' . $antrian->id . '&pasien_id=' . $antrian->pasien_id);
    }

    /**
     * Search Pasien via AJAX Modal (untuk create form)
     */
    public function searchPasien(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        $query = Pasien::where(function($q) {
            $q->where('is_active', true)->orWhereNull('is_active');
        });

        if (strlen($keyword) >= 1) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_pasien', 'like', "%{$keyword}%")
                  ->orWhere('no_rm', 'like', "%{$keyword}%")
                  ->orWhere('nik', 'like', "%{$keyword}%")
                  ->orWhere('no_bpjs', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%")
                  ->orWhere('no_telp', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        $pasiens = $query->orderBy('nama_pasien', 'asc')->limit(40)->get();

        return response()->json($pasiens);
    }

    /**
     * Cetak Laporan PDF / Print Preview
     */
    public function cetakPdf(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());
        $pendaftarans = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])
            ->whereDate('tanggal_daftar', $tanggal)
            ->orderBy('id', 'asc')
            ->get();

        return view('front_office.pendaftaran_pdf', compact('pendaftarans', 'tanggal'));
    }

    /**
     * Helper: Generate Nomor Registrasi Format RG{YYMMDD}{5-digit}
     * Contoh: RG26101400115
     */
    private function generateNextNomorRegistrasi(): string
    {
        $today = Carbon::now();
        $datePrefix = 'RG' . $today->format('ymd');

        $last = Pendaftaran::where('nomor_registrasi', 'like', $datePrefix . '%')
            ->orderBy('nomor_registrasi', 'desc')
            ->first();

        if ($last) {
            $seq = intval(substr($last->nomor_registrasi, strlen($datePrefix))) + 1;
        } else {
            $seq = 1;
        }

        return $datePrefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Helper: Generate Next No Antrian Cth: A001
     */
    private function generateNextNoAntrian(): string
    {
        $today = Carbon::now()->toDateString();
        $count = Pendaftaran::whereDate('tanggal_daftar', $today)->whereNotNull('no_antrian')->count();
        return 'A' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
