<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterLab;
use App\Models\LabNilaiNormal;
use Illuminate\Http\Request;

class LabNilaiNormalController extends Controller
{
    public function index(Request $request)
    {
        $labs = MasterLab::where('is_active', true)->orderBy('nama_pemeriksaan')->get();
        $query = LabNilaiNormal::with('masterLab');

        if ($request->filled('lab_id')) {
            $query->where('master_lab_id', $request->lab_id);
        }

        if ($request->filled('kategori_lab')) {
            $query->where('kategori', $request->kategori_lab);
        }

        if ($request->filled('detail_lab')) {
            $query->where('detail_lab', $request->detail_lab);
        }

        if ($request->filled('nama_setting')) {
            $query->where('nama_setting', 'like', '%' . $request->nama_setting . '%');
        }

        if ($request->filled('bentuk_nilai')) {
            $query->where('bentuk_nilai', $request->bentuk_nilai);
        }

        $totalCount = $query->count();
        $data = $query->orderBy('id', 'asc')->get();

        $kategoris = LabNilaiNormal::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');
        if ($kategoris->isEmpty()) {
            $kategoris = collect(['Dewasa Laki-Laki', 'Dewasa Perempuan', 'Anak', 'Neonatus']);
        }

        $detailLabs = LabNilaiNormal::select('detail_lab')->distinct()->whereNotNull('detail_lab')->pluck('detail_lab');
        if ($detailLabs->isEmpty()) {
            $detailLabs = collect(['Haemoglobin', 'Hematokrit', 'Trombosit', 'Leukosit']);
        }

        return view('laboratorium.nilai_normal', compact('data', 'labs', 'totalCount', 'kategoris', 'detailLabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_lab_id' => 'required|exists:master_labs,id',
            'kategori'      => 'nullable|string',
            'detail_lab'    => 'nullable|string',
            'nama_setting'  => 'required|string',
            'satuan'        => 'nullable|string',
            'bentuk_nilai'  => 'nullable|string',
            'nilai_normal'  => 'nullable|string',
        ]);

        $item = LabNilaiNormal::create([
            'master_lab_id' => $request->master_lab_id,
            'kategori'      => $request->kategori ?: 'Dewasa Laki-Laki',
            'detail_lab'    => $request->detail_lab ?: $request->nama_setting,
            'nama_setting'  => $request->nama_setting,
            'satuan'        => $request->satuan,
            'bentuk_nilai'  => $request->bentuk_nilai ?: 'range',
            'nilai_normal'  => $request->nilai_normal,
            'keterangan'    => $request->keterangan,
        ]);

        $item->load('masterLab');

        return response()->json([
            'success' => true,
            'message' => 'Data Setting Nilai Normal berhasil ditambahkan.',
            'data'    => $item,
        ]);
    }

    public function edit($id)
    {
        $item = LabNilaiNormal::with('masterLab')->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'master_lab_id' => 'required|exists:master_labs,id',
            'nama_setting'  => 'required|string',
        ]);

        $item = LabNilaiNormal::findOrFail($id);
        $item->update([
            'master_lab_id' => $request->master_lab_id,
            'kategori'      => $request->kategori ?: $item->kategori,
            'detail_lab'    => $request->detail_lab ?: $item->detail_lab,
            'nama_setting'  => $request->nama_setting,
            'satuan'        => $request->satuan,
            'bentuk_nilai'  => $request->bentuk_nilai ?: $item->bentuk_nilai,
            'nilai_normal'  => $request->nilai_normal,
            'keterangan'    => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Setting Nilai Normal berhasil diperbarui.',
            'data'    => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = LabNilaiNormal::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Setting Nilai Normal berhasil dihapus.',
        ]);
    }
}
