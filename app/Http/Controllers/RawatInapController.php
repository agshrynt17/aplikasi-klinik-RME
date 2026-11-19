<?php

namespace App\Http\Controllers;

use App\Models\KamarRanap;
use App\Models\Pendaftaran;
use App\Models\RawatInapTransfer;
use App\Models\Ruangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RawatInapController extends Controller
{
    /**
     * Dashboard Bed Management & Visual Grid
     */
    public function index(Request $request)
    {
        $this->seedDefaultBedsIfEmpty();

        $query = KamarRanap::with(['pendaftaran.pasien', 'pendaftaran.dokter', 'ruangan']);

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beds = $query->orderBy('kelas')->orderBy('nama_kamar')->orderBy('nomor_bed')->get();

        // Statistik Bed
        $totalBed   = KamarRanap::count();
        $bedTerisi  = KamarRanap::where('status', 'Terisi')->count();
        $bedKosong  = KamarRanap::where('status', 'Tersedia')->count();
        $bedRepair  = KamarRanap::where('status', 'Perbaikan')->orWhere('status', 'Pembersihan')->count();
        $bor        = $totalBed > 0 ? round(($bedTerisi / $totalBed) * 100, 1) : 0;

        // Daftar Pendaftaran yang bisa di-admisi (belum masuk kamar atau sedang proses)
        $pasiensSiapAdmisi = Pendaftaran::with('pasien')
            ->where('status_periksa', '!=', 'Batal')
            ->whereDoesntHave('kamarRanaps', function($q) {
                $q->where('status', 'Terisi');
            })
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $dokters = Pegawai::where('fungsional_medis', 'DOKTER')
            ->orWhere('jabatan', 'like', '%Dokter%')
            ->orderBy('nama_pegawai')
            ->get();

        $kamarKosongList = KamarRanap::where('status', 'Tersedia')->orderBy('nama_kamar')->get();

        return view('rawat_inap.bed_management', compact(
            'beds', 'totalBed', 'bedTerisi', 'bedKosong', 'bedRepair', 'bor',
            'pasiensSiapAdmisi', 'dokters', 'kamarKosongList'
        ));
    }

    /**
     * Daftar Pasien Rawat Inap Aktif
     */
    public function daftarPasien(Request $request)
    {
        $query = KamarRanap::with(['pendaftaran.pasien', 'pendaftaran.dokter', 'pendaftaran.penjamin', 'ruangan'])
            ->where('status', 'Terisi')
            ->whereNotNull('pendaftaran_id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('pendaftaran.pasien', function($qp) use ($q) {
                $qp->where('nama_pasien', 'like', "%{$q}%")
                   ->orWhere('no_rm', 'like', "%{$q}%");
            });
        }

        $pasienRanap = $query->orderBy('waktu_masuk', 'desc')->paginate(15);
        $kamarKosongList = KamarRanap::where('status', 'Tersedia')->orderBy('nama_kamar')->get();

        return view('rawat_inap.daftar_pasien', compact('pasienRanap', 'kamarKosongList'));
    }

    /**
     * Admisi Pasien Masuk Kamar Rawat Inap
     */
    public function storeAdmisi(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'kamar_ranap_id' => 'required|exists:kamar_ranaps,id',
            'waktu_masuk'    => 'required|date',
            'dokter_id'      => 'nullable|exists:pegawais,id',
            'catatan'        => 'nullable|string',
        ]);

        $bed = KamarRanap::findOrFail($request->kamar_ranap_id);
        if ($bed->status === 'Terisi') {
            return response()->json(['success' => false, 'message' => 'Tempat tidur ini sedang terisi oleh pasien lain!'], 422);
        }

        DB::beginTransaction();
        try {
            // Update bed
            $bed->update([
                'status'         => 'Terisi',
                'pendaftaran_id' => $request->pendaftaran_id,
                'waktu_masuk'    => Carbon::parse($request->waktu_masuk),
                'keterangan'     => $request->catatan,
            ]);

            // Update Pendaftaran
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            $updateData = [
                'jenis_rawat'    => 'Rawat Inap',
                'status_periksa' => 'Sedang Diperiksa',
            ];
            if ($request->filled('dokter_id')) {
                $updateData['dokter_id'] = $request->dokter_id;
            }
            $pendaftaran->update($updateData);

            // Buat Riwayat Transfer / Masuk Kamar
            RawatInapTransfer::create([
                'pendaftaran_id'    => $pendaftaran->id,
                'kamar_ranap_id'    => $bed->id,
                'waktu_masuk'       => Carbon::parse($request->waktu_masuk),
                'lama_hari'         => 1,
                'tarif_per_hari'    => $bed->tarif_per_hari,
                'total_biaya_kamar' => $bed->tarif_per_hari,
                'catatan'           => $request->catatan ?: 'Admisi Rawat Inap Awal',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil di-admisi ke ' . $bed->nama_kamar . ' - ' . $bed->nomor_bed . '.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal melakukan admisi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Pindah Kamar / Bed (Transfer)
     */
    public function storeTransfer(Request $request)
    {
        $request->validate([
            'bed_asal_id'    => 'required|exists:kamar_ranaps,id',
            'bed_tujuan_id'  => 'required|exists:kamar_ranaps,id|different:bed_asal_id',
            'catatan'        => 'nullable|string',
        ]);

        $bedAsal   = KamarRanap::findOrFail($request->bed_asal_id);
        $bedTujuan = KamarRanap::findOrFail($request->bed_tujuan_id);

        if ($bedTujuan->status === 'Terisi') {
            return response()->json(['success' => false, 'message' => 'Kamar/Bed tujuan sedang terisi!'], 422);
        }

        DB::beginTransaction();
        try {
            $pendaftaranId = $bedAsal->pendaftaran_id;
            $waktuPindah   = Carbon::now();

            // Tutup riwayat kamar asal
            $lastTransfer = RawatInapTransfer::where('pendaftaran_id', $pendaftaranId)
                ->where('kamar_ranap_id', $bedAsal->id)
                ->whereNull('waktu_keluar')
                ->latest()
                ->first();

            if ($lastTransfer) {
                $lamaHari = max(1, (int)$waktuPindah->diffInDays($lastTransfer->waktu_masuk));
                $lastTransfer->update([
                    'waktu_keluar'      => $waktuPindah,
                    'lama_hari'         => $lamaHari,
                    'total_biaya_kamar' => $lamaHari * $lastTransfer->tarif_per_hari,
                ]);
            }

            // Kosongkan bed lama
            $bedAsal->update([
                'status'         => 'Tersedia',
                'pendaftaran_id' => null,
                'waktu_masuk'    => null,
                'keterangan'     => null,
            ]);

            // Isi bed baru
            $bedTujuan->update([
                'status'         => 'Terisi',
                'pendaftaran_id' => $pendaftaranId,
                'waktu_masuk'    => $waktuPindah,
                'keterangan'     => $request->catatan,
            ]);

            // Buka riwayat kamar baru
            RawatInapTransfer::create([
                'pendaftaran_id'    => $pendaftaranId,
                'kamar_ranap_id'    => $bedTujuan->id,
                'waktu_masuk'       => $waktuPindah,
                'lama_hari'         => 1,
                'tarif_per_hari'    => $bedTujuan->tarif_per_hari,
                'total_biaya_kamar' => $bedTujuan->tarif_per_hari,
                'catatan'           => 'Transfer dari ' . $bedAsal->nama_kamar . ': ' . ($request->catatan ?? ''),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pasien berhasil dipindahkan ke ' . $bedTujuan->nama_kamar . ' - ' . $bedTujuan->nomor_bed . '.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal pindah kamar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Checkout / Pasien Pulang dari Rawat Inap
     */
    public function discharge(Request $request, $id)
    {
        $bed = KamarRanap::findOrFail($id);
        if ($bed->status !== 'Terisi' || !$bed->pendaftaran_id) {
            return response()->json(['success' => false, 'message' => 'Tempat tidur ini tidak memiliki pasien aktif.'], 422);
        }

        DB::beginTransaction();
        try {
            $pendaftaranId = $bed->pendaftaran_id;
            $waktuKeluar   = Carbon::now();

            // Hitung hari rawat
            $lastTransfer = RawatInapTransfer::where('pendaftaran_id', $pendaftaranId)
                ->where('kamar_ranap_id', $bed->id)
                ->whereNull('waktu_keluar')
                ->latest()
                ->first();

            $waktuMasuk = $lastTransfer ? $lastTransfer->waktu_masuk : ($bed->waktu_masuk ?: $waktuKeluar);
            $lamaHari   = max(1, (int)$waktuKeluar->diffInDays($waktuMasuk));

            if ($lastTransfer) {
                $lastTransfer->update([
                    'waktu_keluar'      => $waktuKeluar,
                    'lama_hari'         => $lamaHari,
                    'total_biaya_kamar' => $lamaHari * $lastTransfer->tarif_per_hari,
                    'catatan'           => 'Discharge Pasien Pulang: ' . ($request->catatan ?? ''),
                ]);
            }

            // Kosongkan bed
            $bed->update([
                'status'         => 'Tersedia',
                'pendaftaran_id' => null,
                'waktu_masuk'    => null,
                'keterangan'     => null,
            ]);

            // Update status pendaftaran
            Pendaftaran::where('id', $pendaftaranId)->update([
                'status_pulang'  => $request->status_pulang ?? 'Sembuh',
                'status_periksa' => 'Menunggu Pembayaran',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pasien rawat inap berhasil di-discharge / pulang. Total hari inap: ' . $lamaHari . ' hari. Tagihan siap diproses di Kasir.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal discharge pasien: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tambah Master Tempat Tidur Baru
     */
    public function storeBed(Request $request)
    {
        $request->validate([
            'nama_kamar'     => 'required|string|max:100',
            'nomor_bed'      => 'required|string|max:50',
            'kelas'          => 'required|string|in:VIP,Kelas 1,Kelas 2,Kelas 3,Isolasi',
            'tarif_per_hari' => 'required|numeric|min:0',
        ]);

        KamarRanap::create([
            'nama_kamar'     => $request->nama_kamar,
            'nomor_bed'      => $request->nomor_bed,
            'kelas'          => $request->kelas,
            'tarif_per_hari' => $request->tarif_per_hari,
            'status'         => 'Tersedia',
        ]);

        return response()->json(['success' => true, 'message' => 'Tempat tidur baru berhasil ditambahkan.']);
    }

    /**
     * Seed Default Clinic Beds if Table is Empty
     */
    private function seedDefaultBedsIfEmpty()
    {
        if (KamarRanap::count() === 0) {
            $defaultBeds = [
                ['nama_kamar' => 'Kamar VIP 01', 'nomor_bed' => 'Bed VIP-1', 'kelas' => 'VIP', 'tarif' => 650000],
                ['nama_kamar' => 'Kamar Mawar 01', 'nomor_bed' => 'Bed 1A', 'kelas' => 'Kelas 1', 'tarif' => 350000],
                ['nama_kamar' => 'Kamar Mawar 01', 'nomor_bed' => 'Bed 1B', 'kelas' => 'Kelas 1', 'tarif' => 350000],
                ['nama_kamar' => 'Kamar Melati 01', 'nomor_bed' => 'Bed 2A', 'kelas' => 'Kelas 2', 'tarif' => 250000],
                ['nama_kamar' => 'Kamar Melati 01', 'nomor_bed' => 'Bed 2B', 'kelas' => 'Kelas 2', 'tarif' => 250000],
                ['nama_kamar' => 'Kamar Anggrek 01', 'nomor_bed' => 'Bed 3A', 'kelas' => 'Kelas 3', 'tarif' => 150000],
                ['nama_kamar' => 'Kamar Anggrek 01', 'nomor_bed' => 'Bed 3B', 'kelas' => 'Kelas 3', 'tarif' => 150000],
                ['nama_kamar' => 'Kamar Anggrek 01', 'nomor_bed' => 'Bed 3C', 'kelas' => 'Kelas 3', 'tarif' => 150000],
                ['nama_kamar' => 'Ruang Isolasi 01', 'nomor_bed' => 'Bed ISO-1', 'kelas' => 'Isolasi', 'tarif' => 450000],
            ];

            foreach ($defaultBeds as $b) {
                KamarRanap::create([
                    'nama_kamar'     => $b['nama_kamar'],
                    'nomor_bed'      => $b['nomor_bed'],
                    'kelas'          => $b['kelas'],
                    'tarif_per_hari' => $b['tarif'],
                    'status'         => 'Tersedia',
                ]);
            }
        }
    }
}

