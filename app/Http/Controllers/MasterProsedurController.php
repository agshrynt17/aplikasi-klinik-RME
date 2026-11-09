<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterProsedur;

class MasterProsedurController extends Controller
{
    // Menampilkan daftar data prosedur (PROFASKES /prosedur/index)
    public function index(Request $request)
    {
        $query = MasterProsedur::query();

        // Filter Kelompok Prosedur
        if ($request->filled('kelompok')) {
            if ($request->kelompok === 'belum_diset') {
                $query->whereNull('kelompok_prosedur')->orWhere('kelompok_prosedur', '');
            } else {
                $query->where('kelompok_prosedur', $request->kelompok);
            }
        }

        // Filter Katagori
        if ($request->filled('katagori')) {
            $query->where('kategori_prosedur', $request->katagori);
        } elseif ($request->filled('kategori')) {
            $query->where('kategori_prosedur', $request->kategori);
        }

        // Filter Kode
        if ($request->filled('kode')) {
            $query->where('kode_prosedur', 'like', "%{$request->kode}%");
        }

        // Filter Prosedur (Nama)
        if ($request->filled('prosedur')) {
            $query->where('nama_prosedur', 'like', "%{$request->prosedur}%");
        }

        // Sorting
        $sortField = $request->get('sort', 'kode_prosedur');
        $sortDir = $request->get('dir', 'asc');
        $allowedSorts = ['kode_prosedur', 'nama_prosedur', 'kelompok_prosedur', 'kategori_prosedur', 'keterangan'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('kode_prosedur', 'asc');
        }

        $totalCount = $query->count();
        $data = $query->paginate(20)->appends($request->all());

        // Get unique categories and groups for filter dropdowns
        $kelompokList = MasterProsedur::whereNotNull('kelompok_prosedur')
            ->where('kelompok_prosedur', '!=', '')
            ->distinct()
            ->orderBy('kelompok_prosedur')
            ->pluck('kelompok_prosedur');

        $kategoriList = MasterProsedur::whereNotNull('kategori_prosedur')
            ->where('kategori_prosedur', '!=', '')
            ->distinct()
            ->orderBy('kategori_prosedur')
            ->pluck('kategori_prosedur');

        return view('rekam_medis.master_prosedur', compact('data', 'kelompokList', 'kategoriList', 'totalCount'));
    }

    // Detail Prosedur (JSON)
    public function show($id)
    {
        $item = MasterProsedur::findOrFail($id);
        return response()->json($item);
    }

    // Edit Prosedur (JSON)
    public function edit($id)
    {
        $item = MasterProsedur::findOrFail($id);
        return response()->json($item);
    }

    // Simpan Prosedur Baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_prosedur' => 'required|unique:master_prosedurs|max:20',
            'nama_prosedur' => 'required|string',
            'kelompok_prosedur' => 'nullable|string',
            'kategori_prosedur' => 'nullable|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = MasterProsedur::create([
            'kode_prosedur'      => strtoupper(trim($request->kode_prosedur)),
            'nama_prosedur'      => trim($request->nama_prosedur),
            'kelompok_prosedur'  => $request->kelompok_prosedur,
            'kategori_prosedur'  => $request->kategori_prosedur,
            'keterangan'         => $request->keterangan,
            'is_active'          => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Prosedur berhasil ditambahkan.',
            'data'    => $item
        ]);
    }

    // Update Prosedur
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_prosedur' => 'required|max:20|unique:master_prosedurs,kode_prosedur,' . $id,
            'nama_prosedur' => 'required|string',
            'kelompok_prosedur' => 'nullable|string',
            'kategori_prosedur' => 'nullable|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = MasterProsedur::findOrFail($id);
        $item->update([
            'kode_prosedur'      => strtoupper(trim($request->kode_prosedur)),
            'nama_prosedur'      => trim($request->nama_prosedur),
            'kelompok_prosedur'  => $request->kelompok_prosedur,
            'kategori_prosedur'  => $request->kategori_prosedur,
            'keterangan'         => $request->keterangan,
            'is_active'          => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Prosedur berhasil diperbarui.',
            'data'    => $item
        ]);
    }

    // Hapus Prosedur
    public function destroy($id)
    {
        $item = MasterProsedur::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Prosedur berhasil dihapus.'
        ]);
    }
}
