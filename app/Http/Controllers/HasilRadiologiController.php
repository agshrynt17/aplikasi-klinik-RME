<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiRadiologi;
use App\Models\HasilRadiologi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HasilRadiologiController extends Controller
{
    // 1. Menampilkan daftar order radiologi (index - KONHAKES /entryhasilrad/index)
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiRadiologis.masterRadiologi', 'transaksiRadiologis.hasilRadiologi'])
            ->whereHas('transaksiRadiologis')
            ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default 2026-10-14 sesuai konteks tanggal KONHAKES
            $tanggal = '2026-10-14';
            $query->whereDate('tanggal_daftar', $tanggal);
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

        $totalCount = $query->count();
        $data = $query->orderBy('nomor_registrasi', 'desc')->get();
        $pendaftarans = $data;

        return view('radiologi.hasil_radiologi_index', compact('data', 'pendaftarans', 'tanggal', 'totalCount'));
    }

    // Terima Order Radiologi
    public function terimaOrder($pendaftaran_id)
    {
        $transaksis = TransaksiRadiologi::where('pendaftaran_id', $pendaftaran_id)->get();
        foreach ($transaksis as $tx) {
            if ($tx->status === 'Pending' || empty($tx->status)) {
                $tx->update(['status' => 'Proses']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order radiologi berhasil diterima.'
        ]);
    }

    // 2. Menampilkan detail pendaftaran & daftar hasil radiologi
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        $umur = null;
        $kategoriUmur = 'Dewasa';
        if ($pendaftaran->pasien && $pendaftaran->pasien->tanggal_lahir) {
            $umur = Carbon::parse($pendaftaran->pasien->tanggal_lahir)->age;
            if ($umur < 1) {
                $kategoriUmur = 'Bayi';
            } elseif ($umur < 18) {
                $kategoriUmur = 'Anak-anak';
            } elseif ($umur >= 60) {
                $kategoriUmur = 'Lansia';
            }
        }

        $transaksis = TransaksiRadiologi::with(['masterRadiologi', 'radiografer', 'hasilRadiologi.dokterPemeriksa'])
            ->where('pendaftaran_id', $pendaftaran_id)
            ->get();

        // Ambil daftar dokter (termasuk spesialis radiologi)
        $dokterList = Pegawai::where('is_active', true)
            ->orderBy('nama_pegawai')
            ->get();

        return view('radiologi.hasil_radiologi_detail', compact('pendaftaran', 'transaksis', 'dokterList', 'umur', 'kategoriUmur'));
    }

    // 3. Simpan Draft Hasil (Upload file gambar & Ekspertise)
    public function simpan(Request $request)
    {
        $request->validate([
            'transaksi_radiologi_id' => 'required|exists:transaksi_radiologis,id',
            'dokter_pemeriksa_id'    => 'nullable|exists:pegawais,id',
            'hasil_bacaan'           => 'required|string',
            'kesan'                  => 'nullable|string',
            'saran'                  => 'nullable|string',
            'file_gambar'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
        ]);

        $transaksi = TransaksiRadiologi::findOrFail($request->transaksi_radiologi_id);

        $dataHasil = [
            'transaksi_radiologi_id' => $transaksi->id,
            'dokter_pemeriksa_id'    => $request->dokter_pemeriksa_id,
            'tanggal_pemeriksaan'    => $request->tanggal_pemeriksaan ?? now(),
            'hasil_bacaan'           => $request->hasil_bacaan,
            'kesan'                  => $request->kesan,
            'saran'                  => $request->saran,
        ];

        // Jika upload gambar baru
        if ($request->hasFile('file_gambar')) {
            $file = $request->file('file_gambar');
            $filename = 'rad_' . $transaksi->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/radiologi', $filename);
            $dataHasil['file_gambar'] = Storage::url($path);
        }

        $hasil = HasilRadiologi::updateOrCreate(
            ['transaksi_radiologi_id' => $transaksi->id],
            $dataHasil
        );

        // Update status transaksi menjadi Proses jika belum Selesai
        if ($transaksi->status === 'Pending') {
            $transaksi->update(['status' => 'Proses']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hasil radiologi berhasil disimpan sebagai draf.',
            'data'    => $hasil->load('dokterPemeriksa')
        ]);
    }

    // 4. Verifikasi Hasil Radiologi
    public function verifikasi(Request $request, $id)
    {
        $hasil = HasilRadiologi::findOrFail($id);
        $hasil->update([
            'status_verifikasi' => 'Terverifikasi',
            'waktu_verifikasi'  => now(),
        ]);

        // Update transaksi terkait menjadi 'Selesai'
        if ($hasil->transaksiRadiologi) {
            $hasil->transaksiRadiologi->update(['status' => 'Selesai']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hasil radiologi berhasil diverifikasi.',
            'data'    => $hasil
        ]);
    }

    // 5. Batal Verifikasi
    public function batalVerifikasi($id)
    {
        $hasil = HasilRadiologi::findOrFail($id);
        $hasil->update([
            'status_verifikasi' => 'Draft',
            'waktu_verifikasi'  => null,
        ]);

        if ($hasil->transaksiRadiologi) {
            $hasil->transaksiRadiologi->update(['status' => 'Proses']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi hasil radiologi dibatalkan.',
            'data'    => $hasil
        ]);
    }

    // 6. Hapus Hasil Radiologi
    public function hapus($id)
    {
        $hasil = HasilRadiologi::findOrFail($id);
        if ($hasil->file_gambar) {
            $relativePath = str_replace('/storage/', 'public/', $hasil->file_gambar);
            Storage::delete($relativePath);
        }
        $hasil->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hasil radiologi berhasil dihapus.'
        ]);
    }

    // 7. Export Laporan XLS
    public function exportXls(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $data = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiRadiologis.masterRadiologi', 'transaksiRadiologis.hasilRadiologi'])
            ->whereHas('transaksiRadiologis')
            ->whereDate('tanggal_daftar', $tanggal)
            ->where('status_periksa', '!=', 'Batal')
            ->orderBy('nomor_registrasi', 'desc')
            ->get();

        $filename = "Laporan_Order_Radiologi_" . date('Ymd_His') . ".xls";
        
        $html = "<table border='1'>";
        $html .= "<tr style='background-color:#00a65a; color:white;'>";
        $html .= "<th>#</th><th>No. Registrasi</th><th>No. RM</th><th>Nama Pasien</th><th>Pemeriksaan</th><th>Dokter</th><th>Status</th>";
        $html .= "</tr>";

        if ($data->isEmpty()) {
            $html .= "<tr><td colspan='7'>Tidak ada data order radiologi ditemukan.</td></tr>";
        } else {
            foreach ($data as $i => $item) {
                $pemeriksaanList = $item->transaksiRadiologis->map(fn($t) => $t->masterRadiologi->nama_pemeriksaan ?? '-')->implode(', ');
                $html .= "<tr>";
                $html .= "<td>" . ($i + 1) . "</td>";
                $html .= "<td>" . $item->nomor_registrasi . "</td>";
                $html .= "<td>" . ($item->pasien->no_rm ?? '-') . "</td>";
                $html .= "<td>" . ($item->pasien->nama_pasien ?? '-') . "</td>";
                $html .= "<td>" . $pemeriksaanList . "</td>";
                $html .= "<td>" . ($item->dokter->nama_pegawai ?? '-') . "</td>";
                $html .= "<td>" . $item->status_periksa . "</td>";
                $html .= "</tr>";
            }
        }
        $html .= "</table>";

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
