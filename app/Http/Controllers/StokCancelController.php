<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokCancel;
use App\Models\StokCancelDetail;
use App\Models\MasterGudang;
use App\Models\MasterBarang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use Illuminate\Support\Facades\DB;

class StokCancelController extends Controller
{
    /**
     * Display a listing of Stok Cancels (PROFASKES Screenshot 1:1).
     */
    public function index(Request $request)
    {
        $query = StokCancel::with(['masterGudang', 'details.masterBarang'])->orderBy('id', 'desc');

        // Column inline filters
        if ($request->filled('cancel_no')) {
            $query->where('cancel_no', 'like', "%{$request->cancel_no}%");
        }

        if ($request->filled('cancel_tanggal')) {
            $query->where('cancel_tanggal', 'like', "%{$request->cancel_tanggal}%");
        }

        if ($request->filled('kode_gudang')) {
            $query->where('kode_gudang', 'like', "%{$request->kode_gudang}%");
        }

        if ($request->filled('cancel_type')) {
            $query->where('cancel_type', 'like', "%{$request->cancel_type}%");
        }

        if ($request->filled('create_at')) {
            $query->where('created_at', 'like', "%{$request->create_at}%");
        }

        $cancels = $query->paginate(20)->withQueryString();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $barangs = MasterBarang::orderBy('nama_barang')->get();

        return view('farmasi.stok_cancel', compact('cancels', 'gudangs', 'barangs'));
    }

    /**
     * Show detail of a specific Stok Cancel (for Detail button / modal).
     */
    public function show($id)
    {
        $cancel = StokCancel::with(['masterGudang', 'details.masterBarang', 'user'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $cancel,
            ]);
        }

        return view('farmasi.stok_cancel_detail', compact('cancel'));
    }

    /**
     * PROFASKES exact view alias (/stok-cancel/view?id=1).
     */
    public function view(Request $request)
    {
        $id = $request->query('id', 1);
        return $this->show($id);
    }

    /**
     * Store a new cancellation (Cancel a stock transaction).
     */
    public function store(Request $request)
    {
        $request->validate([
            'cancel_tanggal' => 'required|date',
            'kode_gudang'    => 'required|string',
            'cancel_type'    => 'required|string',
            'items'          => 'required|array',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'           => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $lastCancel = StokCancel::orderBy('id', 'desc')->first();
            $nextNo = $lastCancel ? (int)$lastCancel->cancel_no + 1 : 1;

            $gudang = MasterGudang::where('kode_gudang', $request->kode_gudang)->first();

            $cancel = StokCancel::create([
                'cancel_no'        => (string)$nextNo,
                'cancel_tanggal'   => $request->cancel_tanggal,
                'kode_gudang'      => $request->kode_gudang,
                'master_gudang_id' => $gudang ? $gudang->id : null,
                'cancel_type'      => $request->cancel_type,
                'keterangan'       => $request->keterangan,
                'user_id'          => auth()->id(),
            ]);

            foreach ($request->items as $itemData) {
                $barang = MasterBarang::lockForUpdate()->findOrFail($itemData['master_barang_id']);
                
                StokCancelDetail::create([
                    'stok_cancel_id'   => $cancel->id,
                    'master_barang_id' => $barang->id,
                    'jumlah'           => $itemData['jumlah'],
                    'satuan'           => $barang->kemasan ?? 'Pcs',
                    'keterangan'       => $itemData['keterangan'] ?? null,
                ]);

                // Rollback warehouse stock if applicable
                if ($gudang) {
                    $stokGudang = StokGudang::firstOrNew([
                        'master_gudang_id' => $gudang->id,
                        'master_barang_id' => $barang->id,
                    ]);
                    $stokGudang->stok = $stokGudang->stok - (int)$itemData['jumlah'];
                    $stokGudang->save();
                }

                // Sync MasterBarang stock
                $barang->stok = $barang->stok - (int)$itemData['jumlah'];
                $barang->save();
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembatalan stok berhasil diproses.',
                    'data'    => $cancel
                ]);
            }

            return redirect('/stok-cancel/index')->with('success', 'Pembatalan stok berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
