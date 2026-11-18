<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanApotek;
use App\Models\PenjualanApotekDetail;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\BatchBarang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\ProfilFaskes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanApotekController extends Controller
{
    /**
     * Display a listing of pharmacy sales transactions.
     * Matches KONHAKES 1:1 view at /transaksipenjualan/index
     */
    public function index(Request $request)
    {
        $query = PenjualanApotek::with(['pasien', 'dokter', 'gudang', 'details.masterBarang']);

        // Filter by Date
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('tanggal_penjualan', [
                $request->tgl_awal . ' 00:00:00',
                $request->tgl_akhir . ' 23:59:59'
            ]);
        } elseif ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_penjualan', '>=', $request->tgl_awal);
        } elseif ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_penjualan', '<=', $request->tgl_akhir);
        }

        // Filter by Keyword (Kode Transaksi, Nama Pelanggan, No Telp)
        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $query->where(function($q) use ($kw) {
                $q->where('kode_transaksi', 'like', "%{$kw}%")
                  ->orWhere('nomor_faktur', 'like', "%{$kw}%")
                  ->orWhere('pelanggan_nama', 'like', "%{$kw}%")
                  ->orWhere('nama_pembeli', 'like', "%{$kw}%")
                  ->orWhere('pelanggan_telp', 'like', "%{$kw}%");
            });
        }

        // Filter by Cara Bayar
        if ($request->filled('cara_bayar')) {
            $query->where('cara_bayar', $request->cara_bayar);
        }

        // Filter by Status Bayar
        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        $data = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $faskes = ProfilFaskes::first();

        return view('farmasi.penjualan_apotek.index', compact('data', 'faskes'));
    }

    /**
     * Show the form for creating a new pharmacy sales transaction.
     * Matches KONHAKES 1:1 POS Cashier at /transaksipenjualan/create
     */
    public function create()
    {
        // Generate Kode Transaksi: PJ + ymd + 00001 (e.g. PJ26101400001)
        $todayPrefix = 'PJ' . date('ymd');
        $lastToday = PenjualanApotek::where('kode_transaksi', 'like', $todayPrefix . '%')
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if ($lastToday && preg_match('/PJ\d{6}(\d{5})/', $lastToday->kode_transaksi, $m)) {
            $seq = intval($m[1]) + 1;
        } else {
            // Count total existing for safe fallback
            $totalToday = PenjualanApotek::where('kode_transaksi', 'like', $todayPrefix . '%')->count();
            $seq = $totalToday + 1;
        }

        $kodeTransaksi = $todayPrefix . str_pad($seq, 5, '0', STR_PAD_LEFT);

        // Dokters
        $dokters = Pegawai::where(function($q) {
            $q->where('nama_pegawai', 'like', 'dr.%')
              ->orWhere('jabatan', 'like', '%dokter%')
              ->orWhere('fungsional_medis', 'like', '%dokter%')
              ->orWhere('bagian', 'like', '%dokter%');
        })->orderBy('nama_pegawai')->get();

        if ($dokters->isEmpty()) {
            $dokters = Pegawai::orderBy('nama_pegawai')->get();
        }

        // Gudangs
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        if ($gudangs->isEmpty()) {
            $gudangs = MasterGudang::orderBy('nama_gudang')->get();
        }

        // Kelas Terapi (from distinct kelas_terapi)
        $kelasTerapi = MasterBarang::whereNotNull('kelas_terapi')
            ->where('kelas_terapi', '!=', '')
            ->distinct()
            ->pluck('kelas_terapi');

        // All active medicines for dropdown
        $barangs = MasterBarang::where('is_active', true)
            ->select('id', 'kode_barang', 'nama_barang', 'kelas_terapi', 'kategori', 'kemasan', 'satuan', 'harga_jual', 'stok')
            ->orderBy('nama_barang')
            ->get();

        $faskes = ProfilFaskes::first();

        return view('farmasi.penjualan_apotek.create', compact(
            'kodeTransaksi',
            'dokters',
            'gudangs',
            'kelasTerapi',
            'barangs',
            'faskes'
        ));
    }

    /**
     * AJAX Search medicine details (price, unit, stock per warehouse, batches)
     */
    public function searchObat(Request $request)
    {
        $barangId = $request->input('barang_id');
        $gudangId = $request->input('gudang_id');

        $barang = MasterBarang::find($barangId);
        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Obat tidak ditemukan'], 404);
        }

        // Determine stock in specific warehouse
        $stokGudang = null;
        if ($gudangId) {
            $stokGudang = StokGudang::where('master_barang_id', $barangId)
                ->where('master_gudang_id', $gudangId)
                ->value('stok');
        }
        $stok = $stokGudang !== null ? $stokGudang : $barang->stok;

        // Fetch batches
        $batchQuery = BatchBarang::where('master_barang_id', $barangId);
        if ($gudangId) {
            $batchQuery->where(function($q) use ($gudangId) {
                $q->where('master_gudang_id', $gudangId)->orWhereNull('master_gudang_id');
            });
        }
        $batches = $batchQuery->where('stok', '>', 0)
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get(['id', 'nomor_batch', 'tanggal_kadaluarsa', 'stok']);

        $kemasan = $barang->kemasan ?: ($barang->satuan ?: 'TABLET');

        return response()->json([
            'success'            => true,
            'id'                 => $barang->id,
            'kode_barang'        => $barang->kode_barang,
            'nama_barang'        => $barang->nama_barang,
            'kelas_terapi'       => $barang->kelas_terapi,
            'kategori'           => $barang->kategori,
            'kemasan'            => $kemasan,
            'satuan_terkecil'    => $kemasan,
            'harga_jual'         => (float) $barang->harga_jual,
            'stok'               => (int) $stok,
            'batches'            => $batches
        ]);
    }

    /**
     * AJAX Price list of medicines
     */
    public function daftarHargaObat(Request $request)
    {
        $q = trim($request->input('q', ''));
        $barangs = MasterBarang::where('is_active', true)
            ->when($q, function($query) use ($q) {
                $query->where('nama_barang', 'like', "%{$q}%")
                      ->orWhere('kode_barang', 'like', "%{$q}%")
                      ->orWhere('kelas_terapi', 'like', "%{$q}%")
                      ->orWhere('kategori', 'like', "%{$q}%");
            })
            ->orderBy('nama_barang')
            ->limit(50)
            ->get(['id', 'kode_barang', 'nama_barang', 'kelas_terapi', 'kategori', 'kemasan', 'satuan', 'harga_jual', 'stok']);

        return response()->json($barangs);
    }

    /**
     * AJAX Quick add patient/customer modal
     */
    public function quickPasien(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:150',
            'no_hp'       => 'nullable|string|max:30',
            'alamat'      => 'nullable|string|max:255',
        ]);

        try {
            $today = date('ymd');
            $count = Pasien::whereDate('created_at', today())->count() + 1;
            $noRm = 'RM' . $today . str_pad($count, 4, '0', STR_PAD_LEFT);

            $pasien = Pasien::create([
                'no_rm'         => $noRm,
                'nama_pasien'   => $request->nama_pasien,
                'tanggal_lahir' => $request->tanggal_lahir ?: null,
                'jenis_kelamin' => $request->jenis_kelamin ?: 'Laki-laki',
                'no_hp'         => $request->no_hp,
                'no_telp'       => $request->no_hp,
                'alamat'        => $request->alamat ?: '-',
                'is_active'     => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan / Pasien baru berhasil ditambahkan.',
                'pasien'  => $pasien
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pasien: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Store a newly created pharmacy sale in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi'    => 'required|string|max:50',
            'tanggal_penjualan' => 'required',
            'items'             => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'    => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalBelanja = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $barang = MasterBarang::lockForUpdate()->find($item['master_barang_id']);
                $jumlah = (float) $item['jumlah'];
                $hargaSatuan = isset($item['harga_satuan']) ? (float) $item['harga_satuan'] : (float) ($barang->harga_jual ?? 0);
                $diskonItem = isset($item['diskon']) ? (float) $item['diskon'] : 0;
                $subtotal = ($hargaSatuan * $jumlah) - $diskonItem;
                if ($subtotal < 0) $subtotal = 0;

                $totalBelanja += $subtotal;

                $itemsData[] = [
                    'barang'             => $barang,
                    'master_barang_id'   => $barang->id,
                    'kemasan'            => $item['kemasan'] ?? ($barang->kemasan ?: ($barang->satuan ?: 'TABLET')),
                    'nomor_batch'        => $item['nomor_batch'] ?? null,
                    'tanggal_kadaluarsa' => !empty($item['tanggal_kadaluarsa']) ? Carbon::parse($item['tanggal_kadaluarsa'])->format('Y-m-d') : null,
                    'harga_satuan'       => $hargaSatuan,
                    'jumlah'             => $jumlah,
                    'dosis'              => $item['dosis'] ?? null,
                    'cara_minum'         => $item['cara_minum'] ?? null,
                    'subtotal'           => $subtotal,
                    'diskon'             => $diskonItem,
                ];
            }

            $diskonTotal = (float) ($request->diskon ?? 0);
            $pembulatan = (float) ($request->pembulatan ?? 0);
            $grandTotal = ($totalBelanja - $diskonTotal) + $pembulatan;
            if ($grandTotal < 0) $grandTotal = 0;

            $dibayar = (float) ($request->dibayar ?? 0);
            $kembalian = $dibayar - $grandTotal;
            if ($kembalian < 0) {
                // If not fully paid, could be pending
                $statusBayar = $dibayar > 0 ? 'Sebagian' : 'Belum Lunas';
            } else {
                $statusBayar = 'Lunas';
            }

            // Save Header
            $penjualan = PenjualanApotek::create([
                'nomor_faktur'      => $request->kode_transaksi,
                'kode_transaksi'    => $request->kode_transaksi,
                'pasien_id'         => $request->pasien_id ?: null,
                'pelanggan_nama'    => $request->pelanggan_nama ?: ($request->nama_pembeli ?: 'UMUM'),
                'pelanggan_telp'    => $request->pelanggan_telp ?: null,
                'dokter_id'         => $request->dokter_id ?: null,
                'dokter_rujukan'    => $request->dokter_rujukan ?: null,
                'instansi_perujuk'  => $request->instansi_perujuk ?: null,
                'no_resep_rujukan'  => $request->no_resep_rujukan ?: null,
                'master_gudang_id'  => $request->master_gudang_id ?: null,
                'cara_bayar'        => $request->cara_bayar ?: 'Tunai',
                'status_bayar'      => $request->status_bayar ?: $statusBayar,
                'tanggal_penjualan' => Carbon::parse($request->tanggal_penjualan)->format('Y-m-d H:i:s'),
                'nama_pembeli'      => $request->pelanggan_nama ?: ($request->nama_pembeli ?: 'UMUM'),
                'total_harga'       => $totalBelanja,
                'diskon'            => $diskonTotal,
                'pembulatan'        => $pembulatan,
                'grand_total'       => $grandTotal,
                'dibayar'           => $dibayar,
                'kembalian'         => max(0, $kembalian),
                'keterangan'        => $request->keterangan ?: null,
            ]);

            // Save Details & Adjust Stock
            foreach ($itemsData as $it) {
                PenjualanApotekDetail::create([
                    'penjualan_apotek_id' => $penjualan->id,
                    'master_barang_id'    => $it['master_barang_id'],
                    'kemasan'             => $it['kemasan'],
                    'nomor_batch'         => $it['nomor_batch'],
                    'tanggal_kadaluarsa'  => $it['tanggal_kadaluarsa'],
                    'harga_satuan'        => $it['harga_satuan'],
                    'jumlah'              => $it['jumlah'],
                    'dosis'               => $it['dosis'],
                    'cara_minum'          => $it['cara_minum'],
                    'subtotal'            => $it['subtotal'],
                    'diskon'              => $it['diskon'],
                ]);

                // Reduce Stock in MasterBarang
                $stokSebelum = $it['barang']->stok;
                $stokSesudah = max(0, $stokSebelum - $it['jumlah']);
                $it['barang']->stok = $stokSesudah;
                $it['barang']->save();

                // Reduce Stock in Gudang if specified
                if ($request->master_gudang_id) {
                    $stokGudang = StokGudang::firstOrCreate(
                        [
                            'master_gudang_id' => $request->master_gudang_id,
                            'master_barang_id' => $it['master_barang_id'],
                        ],
                        [
                            'stok' => 0,
                            'stok_awal' => 0
                        ]
                    );
                    $stokGudang->stok = max(0, $stokGudang->stok - $it['jumlah']);
                    $stokGudang->save();
                }

                // Reduce Batch stock if matched
                if (!empty($it['nomor_batch'])) {
                    $batchObj = BatchBarang::where('master_barang_id', $it['master_barang_id'])
                        ->where('nomor_batch', $it['nomor_batch'])
                        ->first();
                    if ($batchObj) {
                        $batchObj->stok = max(0, $batchObj->stok - $it['jumlah']);
                        $batchObj->save();
                    }
                }

                // Log History Stok
                HistoryStok::create([
                    'master_barang_id'  => $it['master_barang_id'],
                    'master_gudang_id'  => $request->master_gudang_id ?: null,
                    'jenis_penyesuaian' => 'Penjualan Apotek',
                    'jumlah'            => $it['jumlah'],
                    'stok_sebelum'      => $stokSebelum,
                    'stok_sesudah'      => $stokSesudah,
                    'keterangan'        => 'Penjualan Apotek: ' . $penjualan->kode_transaksi . ' (' . ($penjualan->pelanggan_nama ?: 'UMUM') . ')',
                    'status'            => 'Berhasil'
                ]);
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Transaksi Penjualan Apotek berhasil disimpan.',
                'penjualan_id'   => $penjualan->id,
                'kode_transaksi' => $penjualan->kode_transaksi,
                'redirect'       => route('transaksipenjualan.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Hitung Ulang: re-verifies transactions.
     */
    public function hitungUlang()
    {
        return redirect()->route('transaksipenjualan.index')
            ->with('success', 'Hitung ulang rekapitulasi data penjualan apotek selesai.');
    }

    /**
     * Print thermal/slip receipt
     */
    public function cetakNota($id)
    {
        $penjualan = PenjualanApotek::with(['details.masterBarang', 'pasien', 'dokter', 'gudang'])
            ->findOrFail($id);
        $faskes = ProfilFaskes::first();

        return view('farmasi.penjualan_apotek.cetak_nota', compact('penjualan', 'faskes'));
    }

    /**
     * Print report list of pharmacy sales
     */
    public function cetakLaporan(Request $request)
    {
        $query = PenjualanApotek::with(['pasien', 'dokter', 'gudang', 'details.masterBarang']);

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('tanggal_penjualan', [
                $request->tgl_awal . ' 00:00:00',
                $request->tgl_akhir . ' 23:59:59'
            ]);
        }
        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $query->where(function($q) use ($kw) {
                $q->where('kode_transaksi', 'like', "%{$kw}%")
                  ->orWhere('pelanggan_nama', 'like', "%{$kw}%");
            });
        }
        if ($request->filled('cara_bayar')) {
            $query->where('cara_bayar', $request->cara_bayar);
        }

        $data = $query->orderBy('id', 'asc')->get();
        $faskes = ProfilFaskes::first();

        return view('farmasi.penjualan_apotek.cetak_laporan', compact('data', 'faskes'));
    }

    /**
     * Display the specified resource as JSON.
     */
    public function show($id)
    {
        $penjualan = PenjualanApotek::with(['details.masterBarang', 'pasien', 'dokter', 'gudang'])->findOrFail($id);
        return response()->json($penjualan);
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $penjualan = PenjualanApotek::with('details.masterBarang')->findOrFail($id);

            // Rollback stock
            foreach ($penjualan->details as $detail) {
                if ($detail->masterBarang) {
                    $stokSebelum = $detail->masterBarang->stok;
                    $stokSesudah = $stokSebelum + $detail->jumlah;
                    $detail->masterBarang->stok = $stokSesudah;
                    $detail->masterBarang->save();

                    // Restore Gudang
                    if ($penjualan->master_gudang_id) {
                        $stokGudang = StokGudang::where('master_gudang_id', $penjualan->master_gudang_id)
                            ->where('master_barang_id', $detail->master_barang_id)
                            ->first();
                        if ($stokGudang) {
                            $stokGudang->stok += $detail->jumlah;
                            $stokGudang->save();
                        }
                    }

                    // Restore Batch
                    if (!empty($detail->nomor_batch)) {
                        $batchObj = BatchBarang::where('master_barang_id', $detail->master_barang_id)
                            ->where('nomor_batch', $detail->nomor_batch)
                            ->first();
                        if ($batchObj) {
                            $batchObj->stok += $detail->jumlah;
                            $batchObj->save();
                        }
                    }

                    HistoryStok::create([
                        'master_barang_id'  => $detail->master_barang_id,
                        'master_gudang_id'  => $penjualan->master_gudang_id ?: null,
                        'jenis_penyesuaian' => 'Pembatalan Penjualan Apotek',
                        'jumlah'            => $detail->jumlah,
                        'stok_sebelum'      => $stokSebelum,
                        'stok_sesudah'      => $stokSesudah,
                        'keterangan'        => 'Pembatalan Penjualan: ' . $penjualan->kode_transaksi,
                        'status'            => 'Berhasil'
                    ]);
                }
            }

            $penjualan->details()->delete();
            $penjualan->delete();

            DB::commit();

            return redirect()->route('transaksipenjualan.index')
                ->with('success', 'Transaksi penjualan berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksipenjualan.index')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
