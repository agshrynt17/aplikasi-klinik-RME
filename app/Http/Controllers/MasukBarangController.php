<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPembelian;
use App\Models\MasukBarang;
use App\Models\MasukBarangDetail;
use App\Models\MasterVendor;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\HistoryStok;
use App\Models\BatchBarang;
use App\Models\StokGudang;
use App\Models\Hutang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasukBarangController extends Controller
{
    /**
     * Display a listing of Penerimaan Barang (KONHAKES 1:1).
     */
    public function index(Request $request)
    {
        $query = MasukBarang::with(['masterVendor', 'orderPembelian', 'details.masterBarang'])
            ->orderBy('id', 'desc');

        // Filter: Nomor Penerimaan / Kode
        if ($request->filled('nomor_penerimaan')) {
            $query->where('nomor_penerimaan', 'like', '%' . $request->nomor_penerimaan . '%');
        }

        // Filter: No Faktur
        if ($request->filled('nomor_faktur')) {
            $query->where('nomor_faktur', 'like', '%' . $request->nomor_faktur . '%');
        }

        // Filter: Vendor
        if ($request->filled('master_vendor_id')) {
            $query->where('master_vendor_id', $request->master_vendor_id);
        }

        // Filter: Status Pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter: Rentang Tanggal Terima
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_terima', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_terima', '<=', $request->tanggal_akhir);
        }

        $data = $query->paginate(20)->withQueryString();
        $vendors = MasterVendor::where('is_active', true)->orderBy('nama_vendor')->get();

        return view('farmasi.masuk_barang.index', compact('data', 'vendors'));
    }

    /**
     * Show the form for creating a new Penerimaan Barang.
     */
    public function create(Request $request)
    {
        // Format Kode Penerimaan: BP + YYMMDD + 5-digit nomor urut (e.g. BP26101400001)
        $todayPrefix = 'BP' . date('ymd');
        $lastTerima = MasukBarang::where('nomor_penerimaan', 'like', "{$todayPrefix}%")
                                 ->orderBy('nomor_penerimaan', 'desc')
                                 ->first();

        if ($lastTerima) {
            $lastSeq = (int) substr($lastTerima->nomor_penerimaan, -5);
            $nextSeq = str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '00001';
        }
        $nomorPenerimaan = $todayPrefix . $nextSeq;

        $vendors = MasterVendor::where('is_active', true)->orderBy('nama_vendor')->get();
        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        
        // Default gudang farmasi
        if ($gudangs->isEmpty()) {
            MasterGudang::create([
                'kode_gudang' => 'GUD-01',
                'nama_gudang' => 'Gudang Farmasi Utama',
                'lokasi'      => 'Instalasi Farmasi',
                'is_active'   => true
            ]);
            $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        }

        // List PO yang aktif / belum sepenuhnya selesai
        $pos = OrderPembelian::with(['masterVendor', 'details.masterBarang'])
                             ->whereIn('status', ['Draft', 'Pending', 'Disetujui'])
                             ->orderBy('id', 'desc')
                             ->get();

        $selectedPoId = $request->get('po_id');

        return view('farmasi.masuk_barang.create', compact(
            'nomorPenerimaan',
            'vendors',
            'barangs',
            'gudangs',
            'pos',
            'selectedPoId'
        ));
    }

    /**
     * AJAX endpoint: Get PO Details by ID.
     */
    public function getPoDetails($id)
    {
        $po = OrderPembelian::with(['masterVendor', 'details.masterBarang'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'po'      => $po
        ]);
    }

    /**
     * Quick create vendor via AJAX.
     */
    public function quickVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:150',
            'telepon'     => 'nullable|string|max:50',
            'alamat'      => 'nullable|string'
        ]);

        $lastVendor = MasterVendor::latest('id')->first();
        $nextNum = $lastVendor ? ($lastVendor->id + 1) : 1;
        $kodeVendor = 'VND' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $vendor = MasterVendor::create([
            'kode_vendor' => $kodeVendor,
            'nama_vendor' => $request->nama_vendor,
            'telepon'     => $request->telepon,
            'alamat'      => $request->alamat,
            'is_active'   => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vendor berhasil ditambahkan.',
            'vendor'  => $vendor
        ]);
    }

    /**
     * Store a newly created Penerimaan Barang in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_penerimaan'         => 'required|unique:masuk_barangs,nomor_penerimaan',
            'tanggal_terima'           => 'required|date',
            'master_vendor_id'         => 'required|exists:master_vendors,id',
            'nomor_faktur'             => 'required|string|max:100',
            'jenis_pembayaran'         => 'required|string',
            'items'                    => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'           => 'required|numeric|min:0.01',
            'items.*.harga'            => 'required|numeric|min:0'
        ], [
            'nomor_penerimaan.unique'  => 'Kode Penerimaan sudah terdaftar.',
            'master_vendor_id.required'=> 'Vendor wajib dipilih.',
            'nomor_faktur.required'    => 'Nomor Faktur Penerimaan wajib diisi.',
            'items.required'           => 'Minimal harus ada 1 item barang yang diterima.',
            'items.min'                => 'Minimal harus ada 1 item barang yang diterima.'
        ]);

        DB::beginTransaction();
        try {
            // Hitung subtotal dan total
            $subtotalItems = 0;
            foreach ($request->items as $item) {
                $subtotalItems += floatval($item['subtotal'] ?? (floatval($item['jumlah']) * floatval($item['harga'])));
            }

            $diskonTotalPersen = floatval($request->diskon_total_persen ?? 0);
            $diskonTotalNominal = floatval($request->diskon_total_nominal ?? 0);
            if ($diskonTotalPersen > 0 && $diskonTotalNominal == 0) {
                $diskonTotalNominal = ($subtotalItems * $diskonTotalPersen) / 100;
            }

            $afterDiskon = max(0, $subtotalItems - $diskonTotalNominal);

            $ppnPersen = floatval($request->ppn_persen ?? 0);
            $ppnNominal = floatval($request->ppn_nominal ?? 0);
            if ($ppnPersen > 0 && $ppnNominal == 0) {
                $ppnNominal = ($afterDiskon * $ppnPersen) / 100;
            }

            $grandTotal = $afterDiskon + $ppnNominal;

            $jenisPembayaran = $request->jenis_pembayaran ?? 'Kredit';
            $totalBayar = floatval($request->total_bayar ?? ($jenisPembayaran === 'Tunai' ? $grandTotal : 0));
            $sisaBayar = max(0, $grandTotal - $totalBayar);
            $statusBayar = ($sisaBayar <= 0) ? 'Lunas' : 'Belum Lunas';

            // Simpan Header MasukBarang
            $masuk = MasukBarang::create([
                'nomor_penerimaan'     => $request->nomor_penerimaan,
                'nomor_faktur'         => $request->nomor_faktur,
                'order_pembelian_id'   => $request->order_pembelian_id ?: null,
                'kode_po'              => $request->kode_po ?: null,
                'master_vendor_id'     => $request->master_vendor_id,
                'tanggal_terima'       => $request->tanggal_terima,
                'tanggal_jatuh_tempo'  => $request->tanggal_jatuh_tempo ?: null,
                'jenis_pembayaran'     => $jenisPembayaran,
                'subtotal'             => $subtotalItems,
                'diskon_total_persen'  => $diskonTotalPersen,
                'diskon_total_nominal' => $diskonTotalNominal,
                'ppn_persen'           => $ppnPersen,
                'ppn_nominal'          => $ppnNominal,
                'grand_total'          => $grandTotal,
                'total_tagihan'        => $grandTotal,
                'total_bayar'          => $totalBayar,
                'sisa_bayar'           => $sisaBayar,
                'status_pembayaran'    => $statusBayar,
                'keterangan'           => $request->keterangan
            ]);

            // Default master gudang jika belum dipilih di baris
            $defaultGudang = MasterGudang::first();

            // Simpan Details & Update Stok
            foreach ($request->items as $item) {
                $gudangId = !empty($item['master_gudang_id']) ? $item['master_gudang_id'] : ($defaultGudang ? $defaultGudang->id : null);
                $lokasiRak = $item['lokasi_rak'] ?? null;
                $kemasan = $item['kemasan'] ?? null;
                $jumlah = floatval($item['jumlah']);
                $harga = floatval($item['harga']);
                $discPersen = floatval($item['disc_persen'] ?? 0);
                $discNominal = floatval($item['disc_nominal'] ?? 0);
                if ($discPersen > 0 && $discNominal == 0) {
                    $discNominal = ($jumlah * $harga * $discPersen) / 100;
                }
                $itemSubtotal = max(0, ($jumlah * $harga) - $discNominal);

                $noBatch = !empty($item['no_batch']) ? trim($item['no_batch']) : null;
                $tglExp = !empty($item['tgl_exp']) ? $item['tgl_exp'] : null;
                $noBpom = !empty($item['no_bpom']) ? trim($item['no_bpom']) : null;

                MasukBarangDetail::create([
                    'masuk_barang_id'    => $masuk->id,
                    'master_gudang_id'   => $gudangId,
                    'lokasi_rak'         => $lokasiRak,
                    'master_barang_id'   => $item['master_barang_id'],
                    'kemasan'            => $kemasan,
                    'jumlah_terima'      => $jumlah,
                    'harga_satuan'       => $harga,
                    'diskon_persen'      => $discPersen,
                    'diskon_nominal'     => $discNominal,
                    'subtotal'           => $itemSubtotal,
                    'nomor_batch'        => $noBatch,
                    'tanggal_kadaluarsa' => $tglExp,
                    'nomor_bpom'         => $noBpom,
                ]);

                // Update MasterBarang (Stok global & Harga Beli / HNA)
                $barang = MasterBarang::lockForUpdate()->find($item['master_barang_id']);
                if ($barang) {
                    $stokSebelum = $barang->stok ?? 0;
                    $stokSesudah = $stokSebelum + $jumlah;

                    $barang->stok = $stokSesudah;
                    if ($harga > 0) {
                        $barang->harga_beli = $harga;
                        $barang->hna = $harga;
                    }
                    if ($noBatch) {
                        $barang->no_batch = $noBatch;
                    }
                    $barang->save();

                    // Update / Buat Stok Gudang
                    if ($gudangId) {
                        $stokGudang = StokGudang::firstOrNew([
                            'master_gudang_id' => $gudangId,
                            'master_barang_id' => $barang->id
                        ]);
                        $stokGudang->stok = ($stokGudang->exists ? $stokGudang->stok : 0) + $jumlah;
                        $stokGudang->save();
                    }

                    // Update / Buat Batch Barang
                    if ($noBatch) {
                        $batch = BatchBarang::firstOrNew([
                            'master_barang_id' => $barang->id,
                            'nomor_batch'      => $noBatch
                        ]);
                        $batch->master_gudang_id = $gudangId ?? $batch->master_gudang_id;
                        $batch->tanggal_kadaluarsa = $tglExp ?? $batch->tanggal_kadaluarsa;
                        $batch->stok = ($batch->exists ? $batch->stok : 0) + $jumlah;
                        $batch->is_active = true;
                        $batch->save();
                    }

                    // Riwayat / History Stok
                    HistoryStok::create([
                        'master_barang_id'  => $barang->id,
                        'master_gudang_id'  => $gudangId,
                        'jenis_penyesuaian' => 'Penambahan (Penerimaan ' . $masuk->nomor_penerimaan . ')',
                        'tipe_penyesuaian'  => 'Masuk',
                        'tanggal'           => $request->tanggal_terima,
                        'jumlah'            => $jumlah,
                        'stok_sebelum'      => $stokSebelum,
                        'stok_sesudah'      => $stokSesudah,
                        'keterangan'        => 'Penerimaan Faktur: ' . $masuk->nomor_faktur . ' (Vendor: ' . ($masuk->masterVendor->nama_vendor ?? '-') . ')',
                        'status'            => 'Berhasil'
                    ]);
                }
            }

            // Update status OrderPembelian jika ada ref PO
            if ($request->order_pembelian_id) {
                OrderPembelian::where('id', $request->order_pembelian_id)->update(['status' => 'Diterima']);
            }

            // Buat Hutang jika ada sisa bayar / Kredit
            if ($sisaBayar > 0 || $jenisPembayaran === 'Kredit') {
                Hutang::create([
                    'masuk_barang_id'     => $masuk->id,
                    'master_vendor_id'    => $masuk->master_vendor_id,
                    'jumlah_hutang'       => $grandTotal,
                    'jumlah_dibayar'      => $totalBayar,
                    'sisa_hutang'         => $sisaBayar,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo ?? now()->addDays(30),
                    'status'              => ($sisaBayar <= 0 ? 'Lunas' : 'Belum Lunas')
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Transaksi Penerimaan Barang (' . $masuk->nomor_penerimaan . ') berhasil disimpan.',
                    'id'       => $masuk->id,
                    'redirect' => url('/barangmasuk/index')
                ]);
            }

            return redirect('/barangmasuk/index')->with('success', 'Transaksi Penerimaan Barang (' . $masuk->nomor_penerimaan . ') berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show details of a Penerimaan Barang.
     */
    public function show($id)
    {
        $mb = MasukBarang::with([
            'masterVendor',
            'orderPembelian',
            'details.masterBarang',
            'details.masterGudang',
            'hutang.pembayarans'
        ])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $mb
            ]);
        }

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.masuk_barang.show', compact('mb', 'profil'));
    }

    /**
     * Delete a Penerimaan Barang.
     */
    public function destroy($id)
    {
        $mb = MasukBarang::with('details')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert stok if needed or soft delete
            foreach ($mb->details as $d) {
                $barang = MasterBarang::find($d->master_barang_id);
                if ($barang) {
                    $stokSebelum = $barang->stok;
                    $stokSesudah = max(0, $stokSebelum - $d->jumlah_terima);
                    $barang->stok = $stokSesudah;
                    $barang->save();

                    HistoryStok::create([
                        'master_barang_id'  => $barang->id,
                        'master_gudang_id'  => $d->master_gudang_id,
                        'jenis_penyesuaian' => 'Pembatalan (Hapus Penerimaan ' . $mb->nomor_penerimaan . ')',
                        'tipe_penyesuaian'  => 'Keluar',
                        'tanggal'           => date('Y-m-d'),
                        'jumlah'            => $d->jumlah_terima,
                        'stok_sebelum'      => $stokSebelum,
                        'stok_sesudah'      => $stokSesudah,
                        'keterangan'        => 'Dibatalkan/Dihapus oleh pengguna',
                        'status'            => 'Berhasil'
                    ]);
                }
            }

            // Delete hutang
            Hutang::where('masuk_barang_id', $mb->id)->delete();
            $mb->details()->delete();
            $mb->delete();

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi Penerimaan Barang berhasil dihapus.'
                ]);
            }

            return redirect('/barangmasuk/index')->with('success', 'Transaksi Penerimaan Barang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Laporan Rekapitulasi Penerimaan (PDF View).
     */
    public function cetakPdf(Request $request)
    {
        $query = MasukBarang::with(['masterVendor', 'orderPembelian', 'details'])
            ->orderBy('tanggal_terima', 'desc');

        if ($request->filled('nomor_penerimaan')) {
            $query->where('nomor_penerimaan', 'like', '%' . $request->nomor_penerimaan . '%');
        }
        if ($request->filled('nomor_faktur')) {
            $query->where('nomor_faktur', 'like', '%' . $request->nomor_faktur . '%');
        }
        if ($request->filled('master_vendor_id')) {
            $query->where('master_vendor_id', $request->master_vendor_id);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_terima', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_terima', '<=', $request->tanggal_akhir);
        }

        $data = $query->get();
        $profil = DB::table('profil_faskes')->first();

        return view('farmasi.masuk_barang.cetak_laporan', compact('data', 'profil'));
    }

    /**
     * Cetak Lembar Faktur / BKB Single Penerimaan (PDF View).
     */
    public function cetakSingle($id)
    {
        $mb = MasukBarang::with([
            'masterVendor',
            'orderPembelian',
            'details.masterBarang',
            'details.masterGudang'
        ])->findOrFail($id);

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.masuk_barang.cetak_single', compact('mb', 'profil'));
    }

    /**
     * Export Excel / XLS of Penerimaan Barang.
     */
    public function exportExcel(Request $request)
    {
        $query = MasukBarang::with(['masterVendor', 'orderPembelian', 'details'])
            ->orderBy('tanggal_terima', 'desc');

        if ($request->filled('nomor_penerimaan')) {
            $query->where('nomor_penerimaan', 'like', '%' . $request->nomor_penerimaan . '%');
        }
        if ($request->filled('nomor_faktur')) {
            $query->where('nomor_faktur', 'like', '%' . $request->nomor_faktur . '%');
        }
        if ($request->filled('master_vendor_id')) {
            $query->where('master_vendor_id', $request->master_vendor_id);
        }
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_terima', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_terima', '<=', $request->tanggal_akhir);
        }

        $data = $query->get();

        $filename = 'Laporan_Penerimaan_Barang_' . date('Ymd_His') . '.xls';

        $profil = DB::table('profil_faskes')->first();
        $klinikNama = $profil->nama_klinik ?? 'KLINIK TELAGASARI 24 JAM';

        $html = "<table border='1'>";
        $html .= "<tr><th colspan='11' style='font-size:16px; font-weight:bold; text-align:center;'>LAPORAN PENERIMAAN BARANG - " . strtoupper($klinikNama) . "</th></tr>";
        $html .= "<tr><th colspan='11' style='text-align:center;'>Dicetak pada: " . date('d-m-Y H:i:s') . "</th></tr>";
        $html .= "<tr><th colspan='11'></th></tr>";
        $html .= "<tr style='background-color:#dff0d8; font-weight:bold;'>
                <th>No</th>
                <th>Kode Penerimaan</th>
                <th>Tanggal Terima</th>
                <th>Vendor</th>
                <th>No Faktur</th>
                <th>Grand Total</th>
                <th>Jenis Pembayaran</th>
                <th>Jatuh Tempo</th>
                <th>Pembayaran</th>
                <th>Total Bayar</th>
                <th>Sisa Bayar</th>
              </tr>";

        $totalGrand = 0;
        $totalBayarSum = 0;
        $totalSisaSum = 0;

        foreach ($data as $idx => $row) {
            $totalGrand += ($row->grand_total > 0 ? $row->grand_total : $row->total_tagihan);
            $totalBayarSum += $row->total_bayar;
            $totalSisaSum += $row->sisa_bayar;

            $html .= "<tr>";
            $html .= "<td align='center'>" . ($idx + 1) . "</td>";
            $html .= "<td>" . $row->nomor_penerimaan . "</td>";
            $html .= "<td align='center'>" . Carbon::parse($row->tanggal_terima)->format('d-m-Y') . "</td>";
            $html .= "<td>" . ($row->masterVendor ? $row->masterVendor->nama_vendor : '-') . "</td>";
            $html .= "<td>" . $row->nomor_faktur . "</td>";
            $html .= "<td align='right'>" . number_format($row->grand_total > 0 ? $row->grand_total : $row->total_tagihan, 0, ',', '.') . "</td>";
            $html .= "<td align='center'>" . $row->jenis_pembayaran . "</td>";
            $html .= "<td align='center'>" . ($row->tanggal_jatuh_tempo ? Carbon::parse($row->tanggal_jatuh_tempo)->format('d-m-Y') : '-') . "</td>";
            $html .= "<td align='center'>" . $row->status_pembayaran . "</td>";
            $html .= "<td align='right'>" . number_format($row->total_bayar, 0, ',', '.') . "</td>";
            $html .= "<td align='right'>" . number_format($row->sisa_bayar, 0, ',', '.') . "</td>";
            $html .= "</tr>";
        }

        $html .= "<tr style='background-color:#f5f5f5; font-weight:bold;'>
                <td colspan='5' align='right'>TOTAL KESELURUHAN</td>
                <td align='right'>" . number_format($totalGrand, 0, ',', '.') . "</td>
                <td colspan='3'></td>
                <td align='right'>" . number_format($totalBayarSum, 0, ',', '.') . "</td>
                <td align='right'>" . number_format($totalSisaSum, 0, ',', '.') . "</td>
              </tr>";
        $html .= "</table>";

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
