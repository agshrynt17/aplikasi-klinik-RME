<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiagnosaKeperawatan;

class DiagnosaKeperawatanController extends Controller
{
    // Menampilkan daftar diagnosa keperawatan (PROFASKES /diagnosa-keperawatan/index)
    public function index(Request $request)
    {
        $query = DiagnosaKeperawatan::query();

        // Filter Nama Diagnosa
        if ($request->filled('nama_diagnosa')) {
            $term = $request->nama_diagnosa;
            $query->where(function($q) use ($term) {
                $q->where('nama_diagnosa', 'like', "%{$term}%")
                  ->orWhere('kode_diagnosa', 'like', "%{$term}%")
                  ->orWhere('keterangan', 'like', "%{$term}%");
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'kode_diagnosa');
        $sortDir = $request->get('dir', 'asc');
        $allowedSorts = ['kode_diagnosa', 'nama_diagnosa', 'keterangan'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('kode_diagnosa', 'asc');
        }

        $totalCount = $query->count();
        $data = $query->paginate(20)->appends($request->all());

        return view('rekam_medis.diagnosa_keperawatan', compact('data', 'totalCount'));
    }

    // Detail (JSON)
    public function show($id)
    {
        $item = DiagnosaKeperawatan::findOrFail($id);
        return response()->json($item);
    }

    // Edit (JSON)
    public function edit($id)
    {
        $item = DiagnosaKeperawatan::findOrFail($id);
        return response()->json($item);
    }

    // Simpan Diagnosa Keperawatan Baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_diagnosa' => 'required|unique:diagnosa_keperawatans|max:20',
            'nama_diagnosa' => 'required|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = DiagnosaKeperawatan::create([
            'kode_diagnosa' => strtoupper(trim($request->kode_diagnosa)),
            'nama_diagnosa' => trim($request->nama_diagnosa),
            'keterangan'    => $request->keterangan,
            'is_active'     => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa Keperawatan berhasil ditambahkan.',
            'data'    => $item
        ]);
    }

    // Update Diagnosa Keperawatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_diagnosa' => 'required|max:20|unique:diagnosa_keperawatans,kode_diagnosa,' . $id,
            'nama_diagnosa' => 'required|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = DiagnosaKeperawatan::findOrFail($id);
        $item->update([
            'kode_diagnosa' => strtoupper(trim($request->kode_diagnosa)),
            'nama_diagnosa' => trim($request->nama_diagnosa),
            'keterangan'    => $request->keterangan,
            'is_active'     => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa Keperawatan berhasil diperbarui.',
            'data'    => $item
        ]);
    }

    // Hapus Diagnosa Keperawatan
    public function destroy($id)
    {
        $item = DiagnosaKeperawatan::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa Keperawatan berhasil dihapus.'
        ]);
    }
}
