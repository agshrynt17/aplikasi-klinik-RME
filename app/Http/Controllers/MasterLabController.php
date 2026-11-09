<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterLab;
use Illuminate\Http\Request;

class MasterLabController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterLab::query();

        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'Non Aktif') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('golongan_lab')) {
            $query->where(function($q) use ($request) {
                $q->where('golongan_lab', $request->golongan_lab)
                  ->orWhere('kategori', $request->golongan_lab);
            });
        }

        if ($request->filled('kelompok_lab')) {
            $query->where('kelompok_lab', $request->kelompok_lab);
        }

        if ($request->filled('nama_lab')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pemeriksaan', 'like', '%' . $request->nama_lab . '%')
                  ->orWhere('kode_lab', 'like', '%' . $request->nama_lab . '%');
            });
        }

        $totalCount = $query->count();

        $sortBy = $request->get('sort_by', 'kode_lab');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        if (in_array($sortBy, ['kode_lab', 'golongan_lab', 'nama_pemeriksaan', 'kelompok_lab', 'unitcost', 'is_active'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('kode_lab', 'asc');
        }

        $data = $query->get();

        $golongans = MasterLab::select('golongan_lab')->distinct()->whereNotNull('golongan_lab')->pluck('golongan_lab');
        if ($golongans->isEmpty()) {
            $golongans = collect(['Hematologi', 'Kimia Klinik', 'Urinalisis', 'Imunoserologi', 'Parasitologi', 'Mikrobiologi', 'Feses']);
        }

        return view('laboratorium.master_lab', compact('data', 'totalCount', 'golongans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_lab'         => 'required|unique:master_labs,kode_lab|max:20',
            'golongan_lab'     => 'nullable|max:50',
            'nama_pemeriksaan' => 'required|max:255',
            'kelompok_lab'     => 'nullable|max:50',
            'unitcost'         => 'nullable|numeric|min:0',
        ]);

        $item = MasterLab::create([
            'kode_lab'         => strtoupper($request->kode_lab),
            'golongan_lab'     => $request->golongan_lab ?: 'Hematologi',
            'kategori'         => $request->golongan_lab ?: 'Hematologi',
            'nama_pemeriksaan' => $request->nama_pemeriksaan,
            'kelompok_lab'     => $request->kelompok_lab ?: 'Tunggal',
            'unitcost'         => $request->unitcost ?: 0,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Unit Cost Lab berhasil ditambahkan.',
            'data'    => $item,
        ]);
    }

    public function edit($id)
    {
        $item = MasterLab::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_lab'         => 'required|max:20|unique:master_labs,kode_lab,' . $id,
            'nama_pemeriksaan' => 'required|max:255',
        ]);

        $item = MasterLab::findOrFail($id);
        $item->update([
            'kode_lab'         => strtoupper($request->kode_lab),
            'golongan_lab'     => $request->golongan_lab ?: $item->golongan_lab,
            'kategori'         => $request->golongan_lab ?: $item->kategori,
            'nama_pemeriksaan' => $request->nama_pemeriksaan,
            'kelompok_lab'     => $request->kelompok_lab ?: $item->kelompok_lab,
            'unitcost'         => $request->has('unitcost') ? $request->unitcost : $item->unitcost,
            'is_active'        => $request->has('is_active') ? $request->boolean('is_active') : $item->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Unit Cost Lab berhasil diperbarui.',
            'data'    => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = MasterLab::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Unit Cost Lab berhasil dihapus.',
        ]);
    }
}
