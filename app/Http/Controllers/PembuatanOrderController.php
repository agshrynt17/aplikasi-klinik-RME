<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPembelian;
use App\Models\OrderPembelianDetail;
use App\Models\MasterVendor;
use App\Models\MasterBarang;
use Illuminate\Support\Facades\DB;

class PembuatanOrderController extends Controller
{
    /**
     * Display a listing of Transaksi Pemesanan Barang (PO) (PROFASKES 1:1).
     */
    public function index(Request $request)
    {
        $query = OrderPembelian::with(['masterVendor', 'details.masterBarang']);

        // Filter / Search Keyword
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($w) use ($q) {
                $w->where('nomor_po', 'like', "%{$q}%")
                  ->orWhere('pengiriman_kurir', 'like', "%{$q}%")
                  ->orWhereHas('masterVendor', function($v) use ($q) {
                      $v->where('nama_vendor', 'like', "%{$q}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_po', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_po', '<=', $request->tgl_selesai);
        }

        // Filter Vendor
        if ($request->filled('vendor_id')) {
            $query->where('master_vendor_id', $request->vendor_id);
        }

        $orders = $query->orderBy('tanggal_po', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(20)
                        ->withQueryString();

        $vendors = MasterVendor::orderBy('nama_vendor')->get();

        return view('farmasi.pembuatan_order_index', compact('orders', 'vendors'));
    }

    /**
     * Show the form for creating a new PO (PROFASKES 1:1).
     */
    public function create()
    {
        // Format Nomor PO 1:1: PO + YYMMDD + 5 digit nomor urut (contoh: PO26101400001)
        $todayPrefix = 'PO' . date('ymd');
        $lastOrder = OrderPembelian::where('nomor_po', 'like', "{$todayPrefix}%")
                                   ->orderBy('nomor_po', 'desc')
                                   ->first();

        if ($lastOrder) {
            $lastSeq = (int) substr($lastOrder->nomor_po, -5);
            $nextSeq = str_pad($lastSeq + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '00001';
        }
        $noPo = $todayPrefix . $nextSeq;

        // Tanggal default hari ini (format Y-m-d)
        $tanggalHariIni = date('Y-m-d');

        // Alamat Faskes Klinik
        $profil = DB::table('profil_faskes')->first();
        $alamatKirim = $profil->alamat ?? 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat';

        // Master Vendor & Master Barang
        $vendors = MasterVendor::orderBy('nama_vendor')->get();
        $barangs = MasterBarang::where('is_active', true)
                               ->orderBy('nama_barang')
                               ->get();

        return view('farmasi.pembuatan_order_create', compact(
            'noPo',
            'tanggalHariIni',
            'alamatKirim',
            'vendors',
            'barangs',
            'profil'
        ));
    }

    /**
     * Store a newly created PO in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_po'         => 'required|string|unique:order_pembelians,nomor_po',
            'tanggal_po'       => 'required|date',
            'master_vendor_id' => 'required|exists:master_vendors,id',
            'items'            => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'   => 'required|numeric|min:1',
            'items.*.harga'    => 'required|numeric|min:0',
        ], [
            'nomor_po.unique'  => 'Nomor PO ini sudah terdaftar.',
            'items.required'   => 'Minimal harus ada 1 item obat/barang dalam transaksi pembuatan order.',
            'items.min'        => 'Minimal harus ada 1 item obat/barang dalam transaksi pembuatan order.',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $subtotal = floatval($item['jumlah']) * floatval($item['harga']);
                $totalHarga += $subtotal;
            }

            $order = OrderPembelian::create([
                'nomor_po'         => $request->nomor_po,
                'master_vendor_id' => $request->master_vendor_id,
                'tanggal_po'       => $request->tanggal_po,
                'tgl_kirim'        => $request->tgl_kirim ?: null,
                'rencana_datang'   => $request->tgl_kirim ?: null,
                'pengiriman_kurir' => $request->pengiriman_kurir,
                'alamat_kirim'     => $request->alamat_kirim,
                'total_harga'      => $totalHarga,
                'status'           => 'Draft',
                'keterangan'       => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $subtotal = floatval($item['jumlah']) * floatval($item['harga']);
                OrderPembelianDetail::create([
                    'order_pembelian_id' => $order->id,
                    'master_barang_id'   => $item['master_barang_id'],
                    'kemasan'            => $item['kemasan'] ?? null,
                    'jumlah'             => $item['jumlah'],
                    'harga_satuan'       => $item['harga'],
                    'subtotal'           => $subtotal,
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Transaksi Pemesanan Barang (PO) berhasil disimpan.',
                    'order_id' => $order->id,
                    'redirect' => url('/pembuatanorder/index')
                ]);
            }

            return redirect('/pembuatanorder/index')->with('success', 'Transaksi Pemesanan Barang (' . $order->nomor_po . ') berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan PO: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan PO: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show PO Detail.
     */
    public function show($id)
    {
        $order = OrderPembelian::with(['masterVendor', 'details.masterBarang'])->findOrFail($id);
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'order'   => $order
            ]);
        }

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.pembuatan_order_detail', compact('order', 'profil'));
    }

    /**
     * Delete PO.
     */
    public function destroy($id)
    {
        $order = OrderPembelian::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $order->details()->delete();
            $order->delete();
            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pemesanan Barang (PO) berhasil dihapus.'
                ]);
            }

            return redirect('/pembuatanorder/index')->with('success', 'Pemesanan Barang (PO) berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus PO: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Quick add vendor via modal on create PO page.
     */
    public function quickVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'alamat'      => 'nullable|string',
            'no_telepon'  => 'nullable|string|max:50',
        ]);

        $vendor = MasterVendor::create([
            'nama_vendor' => $request->nama_vendor,
            'alamat'      => $request->alamat,
            'no_telepon'  => $request->no_telepon,
            'is_active'   => true,
        ]);

        return response()->json([
            'success' => true,
            'vendor'  => $vendor,
            'message' => 'Vendor baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Print / Cetak Laporan PDF view.
     */
    public function cetakPdf(Request $request, $id = null)
    {
        $profil = DB::table('profil_faskes')->first();

        if ($id) {
            $order = OrderPembelian::with(['masterVendor', 'details.masterBarang'])->findOrFail($id);
            return view('farmasi.pembuatan_order_cetak_single', compact('order', 'profil'));
        }

        $query = OrderPembelian::with(['masterVendor', 'details']);
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_po', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_po', '<=', $request->tgl_selesai);
        }
        $orders = $query->orderBy('tanggal_po', 'desc')->get();

        return view('farmasi.pembuatan_order_cetak_laporan', compact('orders', 'profil'));
    }
}
