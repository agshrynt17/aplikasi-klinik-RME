<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterRadiologi;
use Illuminate\Http\Request;

class MasterRadiologiController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterRadiologi::query();

        // Filter: Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter: Nama Radiologi (or Kode)
        if ($request->filled('nama_radiologi')) {
            $keyword = $request->nama_radiologi;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_pemeriksaan', 'like', "%{$keyword}%")
                  ->orWhere('kode_rad', 'like', "%{$keyword}%");
            });
        }

        // Filter: Status
        if ($request->has('status') && $request->status !== '' && $request->status !== null) {
            $query->where('is_active', (int)$request->status);
        }

        // Filter: Alat Tersedia
        if ($request->has('alat_tersedia') && $request->alat_tersedia !== '' && $request->alat_tersedia !== null) {
            $query->where('alat_tersedia', (int)$request->alat_tersedia);
        }

        // Sorting
        $sortBy = $request->get('sort', 'default');
        $sortDir = $request->get('dir', 'asc');

        if ($sortBy === 'nama') {
            $query->orderBy('nama_pemeriksaan', $sortDir);
        } elseif ($sortBy === 'status') {
            $query->orderBy('is_active', $sortDir);
        } elseif ($sortBy === 'kode') {
            $query->orderBy('kode_rad', $sortDir);
        } else {
            // Default KONHAKES sort: Aktif first, then ID / creation order
            $query->orderBy('is_active', 'desc')->orderBy('id', 'asc');
        }

        // 100 items per page as shown in screenshot "Menampilkan 1-100 dari 2.874 item."
        $perPage = 100;
        $data = $query->paginate($perPage)->withQueryString();

        // Kategori dropdown options
        $kategoriList = MasterRadiologi::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('radiologi.master_radiologi', compact('data', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rad'         => 'required|unique:master_radiologis|max:20',
            'nama_pemeriksaan' => 'required|string|max:200',
        ]);

        $item = MasterRadiologi::create([
            'kode_rad'         => strtoupper($request->kode_rad),
            'nama_pemeriksaan' => $request->nama_pemeriksaan,
            'kategori'         => $request->kategori ?: null,
            'golongan'         => $request->golongan ?: null,
            'permintaan_hasil' => $request->permintaan_hasil ?: null,
            'unitcost'         => $request->unitcost ? (float)str_replace(',', '', $request->unitcost) : 0,
            'alat_tersedia'    => $request->has('alat_tersedia') ? (bool)$request->alat_tersedia : true,
            'is_active'        => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Pemeriksaan Radiologi berhasil ditambahkan.',
            'data'    => $item,
        ]);
    }

    public function edit($id)
    {
        return response()->json(MasterRadiologi::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemeriksaan' => 'required|string|max:200',
        ]);

        $item = MasterRadiologi::findOrFail($id);
        
        $item->update([
            'nama_pemeriksaan' => $request->nama_pemeriksaan,
            'kategori'         => $request->kategori ?: null,
            'golongan'         => $request->golongan ?: null,
            'permintaan_hasil' => $request->permintaan_hasil ?: null,
            'unitcost'         => $request->has('unitcost') ? (float)str_replace(',', '', $request->unitcost) : $item->unitcost,
            'alat_tersedia'    => $request->has('alat_tersedia') ? (bool)$request->alat_tersedia : $item->alat_tersedia,
            'is_active'        => $request->has('is_active') ? (bool)$request->is_active : $item->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Setting Master & Cost Radiologi berhasil diperbarui.',
            'data'    => $item,
        ]);
    }

    public function destroy($id)
    {
        MasterRadiologi::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Pemeriksaan Radiologi berhasil dihapus.']);
    }
}
