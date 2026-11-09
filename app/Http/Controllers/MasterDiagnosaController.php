<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterDiagnosa;
use Illuminate\Http\Request;

class MasterDiagnosaController extends Controller
{
    // Menampilkan daftar data diagnosa (PROFASKES /diagnosa/index)
    public function index(Request $request)
    {
        $query = MasterDiagnosa::query();

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kode_katdiagnosa', $request->kategori);
        }

        // Filter Kelompok
        if ($request->filled('kelompok')) {
            $query->where('kode_kelompokdiagnosa', $request->kelompok);
        }

        // Filter Nama Diagnosa / Kode
        if ($request->filled('nama_diagnosa')) {
            $term = $request->nama_diagnosa;
            $query->where(function($q) use ($term) {
                $q->where('nama_diagnosa', 'like', "%{$term}%")
                  ->orWhere('kode_diagnosa', 'like', "%{$term}%");
            });
        }

        // Filter Non Spesialis (ya / tidak)
        if ($request->filled('non_spesialis')) {
            $val = strtolower($request->non_spesialis);
            if ($val === 'ya' || $val === '1') {
                $query->where('is_non_spesialis', true);
            } elseif ($val === 'tidak' || $val === '0') {
                $query->where('is_non_spesialis', false);
            }
        }

        // Filter Diagnosa Lab (ya / tidak)
        if ($request->filled('diagnosa_lab')) {
            $val = strtolower($request->diagnosa_lab);
            if ($val === 'ya' || $val === '1') {
                $query->where('is_diagnosa_lab', true);
            } elseif ($val === 'tidak' || $val === '0') {
                $query->where('is_diagnosa_lab', false);
            }
        }

        // Filter Diagnosa Radiologi (ya / tidak)
        if ($request->filled('diagnosa_radiologi')) {
            $val = strtolower($request->diagnosa_radiologi);
            if ($val === 'ya' || $val === '1') {
                $query->where('is_diagnosa_radiologi', true);
            } elseif ($val === 'tidak' || $val === '0') {
                $query->where('is_diagnosa_radiologi', false);
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'kode_diagnosa');
        $sortDir = $request->get('dir', 'asc');
        $allowedSorts = ['kode_diagnosa', 'nama_diagnosa', 'kode_katdiagnosa', 'kode_kelompokdiagnosa', 'is_non_spesialis', 'is_diagnosa_lab', 'is_diagnosa_radiologi', 'keterangan'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('kode_diagnosa', 'asc');
        }

        $totalCount = $query->count();
        $data = $query->paginate(20)->appends($request->all());

        // Get unique categories and groups for filter dropdowns
        $kategoriList = MasterDiagnosa::whereNotNull('kode_katdiagnosa')
            ->where('kode_katdiagnosa', '!=', '')
            ->distinct()
            ->orderBy('kode_katdiagnosa')
            ->pluck('kode_katdiagnosa');

        $kelompokList = MasterDiagnosa::whereNotNull('kode_kelompokdiagnosa')
            ->where('kode_kelompokdiagnosa', '!=', '')
            ->distinct()
            ->orderBy('kode_kelompokdiagnosa')
            ->pluck('kode_kelompokdiagnosa');

        return view('rekam_medis.master_diagnosa', compact('data', 'kategoriList', 'kelompokList', 'totalCount'));
    }

    // Detail diagnosa (JSON)
    public function show($id)
    {
        $item = MasterDiagnosa::findOrFail($id);
        return response()->json($item);
    }

    // Edit diagnosa (JSON)
    public function edit($id)
    {
        $item = MasterDiagnosa::findOrFail($id);
        return response()->json($item);
    }

    // Store diagnosa baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_diagnosa' => 'required|unique:master_diagnosas|max:20',
            'nama_diagnosa' => 'required|string',
            'kode_katdiagnosa' => 'nullable|string',
            'kode_kelompokdiagnosa' => 'nullable|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = MasterDiagnosa::create([
            'kode_diagnosa'          => strtoupper(trim($request->kode_diagnosa)),
            'nama_diagnosa'          => trim($request->nama_diagnosa),
            'kode_katdiagnosa'       => $request->kode_katdiagnosa,
            'kode_kelompokdiagnosa'  => $request->kode_kelompokdiagnosa,
            'is_non_spesialis'       => $request->boolean('is_non_spesialis'),
            'is_diagnosa_lab'        => $request->boolean('is_diagnosa_lab'),
            'is_diagnosa_radiologi'  => $request->boolean('is_diagnosa_radiologi'),
            'keterangan'             => $request->keterangan,
            'is_active'              => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa berhasil ditambahkan.',
            'data'    => $item
        ]);
    }

    // Update diagnosa
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_diagnosa' => 'required|max:20|unique:master_diagnosas,kode_diagnosa,' . $id,
            'nama_diagnosa' => 'required|string',
            'kode_katdiagnosa' => 'nullable|string',
            'kode_kelompokdiagnosa' => 'nullable|string',
            'keterangan'    => 'nullable|string'
        ]);

        $item = MasterDiagnosa::findOrFail($id);
        $item->update([
            'kode_diagnosa'          => strtoupper(trim($request->kode_diagnosa)),
            'nama_diagnosa'          => trim($request->nama_diagnosa),
            'kode_katdiagnosa'       => $request->kode_katdiagnosa,
            'kode_kelompokdiagnosa'  => $request->kode_kelompokdiagnosa,
            'is_non_spesialis'       => $request->boolean('is_non_spesialis'),
            'is_diagnosa_lab'        => $request->boolean('is_diagnosa_lab'),
            'is_diagnosa_radiologi'  => $request->boolean('is_diagnosa_radiologi'),
            'keterangan'             => $request->keterangan,
            'is_active'              => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa berhasil diperbarui.',
            'data'    => $item
        ]);
    }

    // Delete diagnosa
    public function destroy($id)
    {
        $item = MasterDiagnosa::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Diagnosa berhasil dihapus.'
        ]);
    }
}
