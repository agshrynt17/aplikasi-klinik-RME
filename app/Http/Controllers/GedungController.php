<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index(Request $request)
    {
        if (Gedung::where('kode_gedung', 'IGD 1')->exists()) {
            $g1 = Gedung::where('kode_gedung', '01')->first();
            if ($g1) {
                \App\Models\Lantai::where('gedung_id', 3)->update(['gedung_id' => $g1->id]);
            }
            Gedung::where('kode_gedung', 'IGD 1')->delete();
        }

        $query = Gedung::query();

        if ($request->filled('kode_gedung')) {
            $query->where('kode_gedung', 'like', '%' . $request->kode_gedung . '%');
        }

        if ($request->filled('nama_gedung')) {
            $query->where('nama_gedung', 'like', '%' . $request->nama_gedung . '%');
        }

        $data = $query->orderBy('kode_gedung', 'asc')->get();
        $profil = ProfilFaskes::first();

        return view('lokasi.gedung', compact('data', 'profil'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        return view('lokasi.gedung_create', compact('profil'));
    }

    public function show($id)
    {
        $item = Gedung::findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gedung'  => 'required|unique:gedungs,kode_gedung|max:20',
            'nama_gedung'  => 'required|max:255',
            'keterangan'   => 'nullable',
        ]);

        $item = Gedung::create([
            'kode_gedung'  => strtoupper($request->kode_gedung),
            'nama_gedung'  => $request->nama_gedung,
            'keterangan'   => $request->keterangan,
            'is_active'    => $request->has('is_active') ? true : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Gedung berhasil ditambahkan.',
                'data'    => $item
            ]);
        }

        return redirect('/master-gedung')->with('success', 'Data Gedung berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $item = Gedung::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }
        $profil = ProfilFaskes::first();
        return view('lokasi.gedung_create', compact('item', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_gedung'  => 'required|unique:gedungs,kode_gedung,' . $id . '|max:20',
            'nama_gedung'  => 'required|max:255',
            'keterangan'   => 'nullable',
        ]);

        $item = Gedung::findOrFail($id);
        $item->update([
            'kode_gedung'  => strtoupper($request->kode_gedung),
            'nama_gedung'  => $request->nama_gedung,
            'keterangan'   => $request->keterangan,
            'is_active'    => $request->has('is_active') ? true : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Gedung berhasil diperbarui.',
                'data'    => $item->fresh()
            ]);
        }

        return redirect('/master-gedung')->with('success', 'Data Gedung berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Gedung::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Gedung berhasil dihapus.']);
    }
}
