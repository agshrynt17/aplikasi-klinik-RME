<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderPembelian;
use App\Models\OrderPembelianDetail;
use App\Models\MasterVendor;
use App\Models\MasterBarang;
use Illuminate\Support\Facades\DB;

class OrderPembelianController extends Controller
{
    public function index()
    {
        $data = OrderPembelian::with('masterVendor')->orderBy('id', 'desc')->get();
        return view('farmasi.order_pembelian.index', compact('data'));
    }

    public function create()
    {
        $vendors = MasterVendor::where('is_active', true)->orderBy('nama_vendor')->get();
        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();
        
        // Generate nomor PO (Simple auto-increment logic for demo)
        $lastPo = OrderPembelian::orderBy('id', 'desc')->first();
        $nextId = $lastPo ? $lastPo->id + 1 : 1;
        $nomorPo = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('farmasi.order_pembelian.create', compact('vendors', 'barangs', 'nomorPo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_po' => 'required|unique:order_pembelians,nomor_po',
            'master_vendor_id' => 'required|exists:master_vendors,id',
            'tanggal_po' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $totalHarga = 0;
            $po = OrderPembelian::create([
                'nomor_po' => $request->nomor_po,
                'master_vendor_id' => $request->master_vendor_id,
                'tanggal_po' => $request->tanggal_po,
                'rencana_datang' => $request->rencana_datang,
                'keterangan' => $request->keterangan,
                'status' => 'Pending',
                'total_harga' => 0
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                $totalHarga += $subtotal;

                OrderPembelianDetail::create([
                    'order_pembelian_id' => $po->id,
                    'master_barang_id' => $item['master_barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal
                ]);
            }

            $po->update(['total_harga' => $totalHarga]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Pembelian berhasil dibuat.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $po = OrderPembelian::with(['masterVendor', 'details.masterBarang'])->findOrFail($id);
        return response()->json($po);
    }
    
    public function destroy($id)
    {
        $po = OrderPembelian::findOrFail($id);
        if($po->status !== 'Pending') {
            return response()->json(['success' => false, 'message' => 'Hanya PO berstatus Pending yang dapat dihapus.'], 422);
        }
        $po->delete();
        return response()->json(['success' => true, 'message' => 'PO berhasil dihapus.']);
    }
}
