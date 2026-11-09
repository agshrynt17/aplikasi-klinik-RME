<?php

namespace App\Http\Controllers;

use App\Models\BatchBarang;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BatchBarangController extends Controller
{
    /**
     * Display a listing of the batches.
     */
    public function index(Request $request)
    {
        $query = BatchBarang::with(['masterBarang', 'masterGudang'])->orderBy('id', 'desc');

        // Filter pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_batch', 'like', "%{$q}%")
                    ->orWhereHas('masterBarang', function ($b) use ($q) {
                        $b->where('nama_barang', 'like', "%{$q}%")
                          ->orWhere('kode_barang', 'like', "%{$q}%");
                    });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1' || $request->status == 'Aktif');
        }

        // Filter gudang
        if ($request->filled('gudang_id')) {
            $query->where('master_gudang_id', $request->gudang_id);
        }

        $data = $query->paginate(20)->withQueryString();

        $gudangs = MasterGudang::orderBy('nama_gudang')->get();
        $gudangFarmasi = MasterGudang::where('nama_gudang', 'Gudang Farmasi')->first() ?? $gudangs->first();
        $selectedGudang = $request->filled('gudang_id') ? $gudangs->firstWhere('id', $request->gudang_id) : $gudangFarmasi;

        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();

        return view('farmasi.daftar_batch', compact('data', 'barangs', 'gudangs', 'selectedGudang'));
    }

    /**
     * Show the form for creating a new batch.
     */
    public function create()
    {
        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $defaultGudang = MasterGudang::where('nama_gudang', 'Gudang Farmasi')->first() ?? $gudangs->first();

        return view('farmasi.daftar_batch_create', compact('barangs', 'gudangs', 'defaultGudang'));
    }

    /**
     * Store a newly created batch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_barang_id'   => 'required|exists:master_barangs,id',
            'nomor_batch'        => 'required|string|max:100',
            'tanggal_kadaluarsa' => 'nullable|date',
            'stok'               => 'nullable|integer|min:0',
            'master_gudang_id'   => 'nullable|exists:master_gudangs,id',
        ], [
            'master_barang_id.required' => 'Barang wajib dipilih!',
            'nomor_batch.required'      => 'Nomor Batch wajib diisi!',
        ]);

        $defaultGudang = MasterGudang::where('nama_gudang', 'Gudang Farmasi')->first();
        $gudangId = $request->input('master_gudang_id', $defaultGudang ? $defaultGudang->id : null);

        $isActive = true;
        if ($request->has('is_active')) {
            $val = $request->input('is_active');
            $isActive = in_array($val, ['1', 1, true, 'true', 'on', 'Aktif'], true);
        }

        $item = BatchBarang::create([
            'master_barang_id'   => $request->master_barang_id,
            'master_gudang_id'   => $gudangId,
            'nomor_batch'        => strtoupper(trim($request->nomor_batch)),
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'stok'               => $request->input('stok', 0),
            'is_active'          => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Batch berhasil disimpan.',
                'data'    => $item->load(['masterBarang', 'masterGudang'])
            ]);
        }

        return redirect('/batch/index')->with('success', 'Data Batch berhasil disimpan.');
    }

    /**
     * Show or edit a specific batch.
     */
    public function edit($id)
    {
        $item = BatchBarang::with(['masterBarang', 'masterGudang'])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }

        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $defaultGudang = $item->masterGudang ?? (MasterGudang::where('nama_gudang', 'Gudang Farmasi')->first() ?? $gudangs->first());

        return view('farmasi.daftar_batch_create', compact('item', 'barangs', 'gudangs', 'defaultGudang'));
    }

    /**
     * Update an existing batch.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'master_barang_id'   => 'required|exists:master_barangs,id',
            'nomor_batch'        => 'required|string|max:100',
            'tanggal_kadaluarsa' => 'nullable|date',
            'stok'               => 'nullable|integer|min:0',
            'master_gudang_id'   => 'nullable|exists:master_gudangs,id',
        ]);

        $item = BatchBarang::findOrFail($id);

        $isActive = true;
        if ($request->has('is_active')) {
            $val = $request->input('is_active');
            $isActive = in_array($val, ['1', 1, true, 'true', 'on', 'Aktif'], true);
        }

        $item->update([
            'master_barang_id'   => $request->master_barang_id,
            'master_gudang_id'   => $request->input('master_gudang_id', $item->master_gudang_id),
            'nomor_batch'        => strtoupper(trim($request->nomor_batch)),
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'stok'               => $request->input('stok', $item->stok),
            'is_active'          => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Batch berhasil diperbarui.',
                'data'    => $item->load(['masterBarang', 'masterGudang'])
            ]);
        }

        return redirect('/batch/index')->with('success', 'Data Batch berhasil diperbarui.');
    }

    /**
     * Delete an existing batch.
     */
    public function destroy($id)
    {
        $item = BatchBarang::findOrFail($id);
        $item->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Batch berhasil dihapus.'
            ]);
        }

        return redirect('/batch/index')->with('success', 'Data Batch berhasil dihapus.');
    }
}
