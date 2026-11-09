<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiRadiologi;
use App\Models\MasterRadiologi;
use App\Models\TarifRadiologi;
use App\Models\Pegawai;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiRadiologiController extends Controller
{
    // 1. Index: Menampilkan daftar kunjungan pasien (pendaftaran) untuk order radiologi
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiRadiologis'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $tanggal = $request->tanggal;
            $query->whereDate('tanggal_daftar', $tanggal);
        } else {
            // Default tanggal 2026-10-14 sesuai screenshot PROFASKES (8 pasien)
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

        $totalCount = $query->count();
        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')->get();

        return view('radiologi.transaksi_radiologi_index', compact('pendaftarans', 'tanggal', 'totalCount'));
    }

    // 2. Show Detail Transaksi Radiologi per Pasien (Pendaftaran)
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        $transaksis = TransaksiRadiologi::with(['masterRadiologi', 'radiografer'])
            ->where('pendaftaran_id', $pendaftaran_id)
            ->latest()
            ->get();
            
        // Ambil daftar radiografer (pegawai aktif)
        $radiografers = Pegawai::where('is_active', true)
            ->orderBy('nama_pegawai')
            ->get();

        // Ambil semua master radiologi aktif grouped by kategori
        $master_radiologis = MasterRadiologi::where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama_pemeriksaan')
            ->get();

        // Ambil daftar tarif radiologi yang sesuai dengan penjamin pasien ini
        $penjaminId = $pendaftaran->penjamin_id;
        $tarifRadiologis = TarifRadiologi::with('masterRadiologi')
            ->where('penjamin_id', $penjaminId)
            ->where('is_active', true)
            ->get();

        return view('radiologi.transaksi_radiologi_detail', compact('pendaftaran', 'transaksis', 'radiografers', 'master_radiologis', 'tarifRadiologis'));
    }

    // 3. API: Dapatkan harga tarif berdasarkan Master Radiologi & Penjamin Pasien
    public function getTarif(Request $request)
    {
        $master_radiologi_id = $request->master_radiologi_id;
        $penjamin_id         = $request->penjamin_id;

        $tarif = null;

        // 1. Cari tarif untuk penjamin yang spesifik
        if ($penjamin_id) {
            $tarif = TarifRadiologi::where('master_radiologi_id', $master_radiologi_id)
                ->where('penjamin_id', $penjamin_id)
                ->where('is_active', true)
                ->first();
        }

        // 2. Fallback: Cari tarif penjamin UMUM
        if (!$tarif) {
            $umumPenjamin = Penjamin::where('nama_penjamin', 'LIKE', '%UMUM%')->first();
            if ($umumPenjamin) {
                $tarif = TarifRadiologi::where('master_radiologi_id', $master_radiologi_id)
                    ->where('penjamin_id', $umumPenjamin->id)
                    ->where('is_active', true)
                    ->first();
            }
        }

        // 3. Fallback: Ambil tarif apapun yang ada untuk master ini
        if (!$tarif) {
            $tarif = TarifRadiologi::where('master_radiologi_id', $master_radiologi_id)
                ->where('is_active', true)
                ->first();
        }

        $harga = $tarif ? (float)$tarif->harga : 0;
        if ($harga <= 0) {
            $master = MasterRadiologi::find($master_radiologi_id);
            $harga = $master && $master->unitcost > 0 ? (float)$master->unitcost : 130000;
        }

        return response()->json([
            'harga' => $harga
        ]);
    }

    // 4. Tambah Transaksi Radiologi Baru
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id'       => 'required|exists:pendaftarans,id',
            'master_radiologi_id'  => 'nullable|exists:master_radiologis,id',
            'tarif_radiologi_id'   => 'nullable|exists:tarif_radiologis,id',
            'radiografer_id'       => 'nullable|exists:pegawais,id',
            'tarif'                => 'nullable|numeric|min:0',
            'keterangan'           => 'nullable|string|max:500',
        ]);

        $subtotal = $request->tarif ?? 0;

        $transaksi = TransaksiRadiologi::create([
            'pendaftaran_id'      => $request->pendaftaran_id,
            'master_radiologi_id' => $request->master_radiologi_id,
            'radiografer_id'      => $request->radiografer_id,
            'subtotal'            => $subtotal,
            'status'              => 'Pending',
            'keterangan'          => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemeriksaan Radiologi berhasil ditambahkan.',
            'data'    => $transaksi->load(['masterRadiologi', 'radiografer'])
        ]);
    }

    // 5. Update Status Transaksi Radiologi (Pending -> Proses -> Selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Proses,Selesai,Batal'
        ]);

        $transaksi = TransaksiRadiologi::findOrFail($id);
        $transaksi->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan berhasil diperbarui menjadi ' . $request->status,
            'data'    => $transaksi
        ]);
    }

    // 6. Update Transaksi Radiologi (edit catatan/subtotal/radiografer)
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiRadiologi::findOrFail($id);
        
        $transaksi->update([
            'radiografer_id' => $request->radiografer_id ?? $transaksi->radiografer_id,
            'subtotal'       => $request->subtotal ?? $transaksi->subtotal,
            'keterangan'     => $request->keterangan ?? $transaksi->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi Radiologi berhasil diperbarui.',
            'data'    => $transaksi->load(['masterRadiologi', 'radiografer'])
        ]);
    }

    // 7. Hapus Transaksi Radiologi
    public function destroy($id)
    {
        $transaksi = TransaksiRadiologi::findOrFail($id);
        $transaksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemeriksaan Radiologi berhasil dihapus dari order.'
        ]);
    }

    // 8. Export Laporan XLS
    public function exportXls(Request $request)
    {
        $tanggal = $request->get('tanggal', '2026-10-14');
        $data = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin', 'transaksiRadiologis'])
            ->whereDate('tanggal_daftar', $tanggal)
            ->where('status_periksa', '!=', 'Batal')
            ->orderBy('nomor_registrasi', 'desc')
            ->get();

        $filename = "Laporan_Transaksi_Radiologi_" . date('Ymd_His') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr style='background-color:#00a65a; color:white;'>";
        echo "<th>#</th><th>No. Registrasi</th><th>No. RM</th><th>Nama Pasien</th><th>Jenis Kelamin</th><th>Tgl Lahir</th><th>Penjamin</th><th>Klinik & Dokter</th><th>Status</th>";
        echo "</tr>";

        foreach ($data as $i => $item) {
            $jk = ($item->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki';
            $tglLahir = $item->pasien && $item->pasien->tanggal_lahir ? date('d.M.Y', strtotime($item->pasien->tanggal_lahir)) : '-';
            echo "<tr>";
            echo "<td>" . ($i + 1) . "</td>";
            echo "<td>" . $item->nomor_registrasi . "</td>";
            echo "<td>" . ($item->pasien->no_rm ?? '-') . "</td>";
            echo "<td>" . ($item->pasien->nama_pasien ?? '-') . "</td>";
            echo "<td>" . $jk . "</td>";
            echo "<td>" . $tglLahir . "</td>";
            echo "<td>" . ($item->penjamin->nama_penjamin ?? 'Pribadi') . "</td>";
            echo "<td>" . ($item->poliklinik->nama_poli ?? '-') . " - " . ($item->dokter->nama_pegawai ?? '-') . "</td>";
            echo "<td>" . ($item->transaksiRadiologis->count() > 0 ? 'Sudah Ada Transaksi' : 'Belum Ada Transaksi') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit;
    }
}
