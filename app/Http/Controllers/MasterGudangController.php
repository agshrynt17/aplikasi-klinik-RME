<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterGudang;

class MasterGudangController extends Controller
{
    /**
     * Halaman Data Gudang (1:1 KONHAKES /gudang/index)
     */
    public function index(Request $request)
    {
        $query = MasterGudang::query();

        if ($request->filled('kode_gudang')) {
            $query->where('kode_gudang', 'like', '%' . $request->kode_gudang . '%');
        }

        if ($request->filled('nama_gudang')) {
            $query->where('nama_gudang', 'like', '%' . $request->nama_gudang . '%');
        }

        $gudangs = $query->orderByRaw('CAST(kode_gudang AS UNSIGNED) ASC')->orderBy('kode_gudang', 'asc')->paginate(20)->withQueryString();

        return view('farmasi.master_gudang', compact('gudangs'));
    }

    /**
     * Halaman Tambah Gudang (1:1 KONHAKES /gudang/create)
     */
    public function create()
    {
        $all = MasterGudang::all();
        $maxNum = 0;
        foreach ($all as $g) {
            if (is_numeric($g->kode_gudang)) {
                $val = intval($g->kode_gudang);
                if ($val > $maxNum) {
                    $maxNum = $val;
                }
            }
        }
        $kode_gudang = str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT);

        return view('farmasi.master_gudang_create', compact('kode_gudang'));
    }

    /**
     * Simpan Data Gudang Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_gudang' => 'required|unique:master_gudangs,kode_gudang|max:50',
            'nama_gudang' => 'required|string|max:255',
        ]);

        try {
            $gudang = MasterGudang::create([
                'kode_gudang' => $request->kode_gudang,
                'nama_gudang' => $request->nama_gudang,
                'lokasi'      => $request->lokasi,
                'keterangan'  => $request->keterangan,
                'is_active'   => $request->has('is_active') ? (bool)$request->is_active : true,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Gudang berhasil disimpan.',
                    'data'    => $gudang
                ]);
            }

            return redirect('/gudang/index')->with('success', 'Data Gudang berhasil disimpan.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail Data Gudang (JSON or view)
     */
    public function show($id)
    {
        $gudang = MasterGudang::findOrFail($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $gudang
            ]);
        }
        return view('farmasi.master_gudang_create', compact('gudang'));
    }

    /**
     * Halaman Edit Gudang
     */
    public function edit($id)
    {
        $gudang = MasterGudang::findOrFail($id);
        return view('farmasi.master_gudang_create', compact('gudang'));
    }

    /**
     * Update Data Gudang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_gudang' => 'required|max:50|unique:master_gudangs,kode_gudang,' . $id,
            'nama_gudang' => 'required|string|max:255',
        ]);

        try {
            $gudang = MasterGudang::findOrFail($id);
            $gudang->update([
                'kode_gudang' => $request->kode_gudang,
                'nama_gudang' => $request->nama_gudang,
                'lokasi'      => $request->lokasi ?: $gudang->lokasi,
                'keterangan'  => $request->keterangan ?: $gudang->keterangan,
                'is_active'   => $request->has('is_active') ? (bool)$request->is_active : $gudang->is_active,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Gudang berhasil diperbarui.',
                    'data'    => $gudang
                ]);
            }

            return redirect('/gudang/index')->with('success', 'Data Gudang berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Data Gudang
     */
    public function destroy($id)
    {
        try {
            MasterGudang::findOrFail($id)->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Gudang berhasil dihapus.'
                ]);
            }

            return redirect('/gudang/index')->with('success', 'Data Gudang berhasil dihapus.');
        } catch (\Exception $e) {
            if ($request->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
