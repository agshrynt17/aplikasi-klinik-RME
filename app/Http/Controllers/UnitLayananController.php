<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UnitLayanan;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class UnitLayananController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitLayanan::query();

        if ($request->filled('nama_unit')) {
            $query->where('nama_unit', 'like', '%' . $request->nama_unit . '%');
        }

        if ($request->filled('kode_unit')) {
            $query->where('kode_unit', 'like', '%' . $request->kode_unit . '%');
        }

        $data = $query->orderBy('kode_unit', 'asc')->get();
        $profil = ProfilFaskes::first();

        return view('lokasi.unit_layanan', compact('data', 'profil'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        return view('lokasi.unit_layanan_create', compact('profil'));
    }

    public function show($id)
    {
        $item = UnitLayanan::findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_unit'  => 'required|unique:unit_layanans,kode_unit|max:20',
            'nama_unit'  => 'required|max:255',
            'keterangan' => 'nullable',
        ]);

        $item = UnitLayanan::create([
            'kode_unit'  => strtoupper($request->kode_unit),
            'nama_unit'  => $request->nama_unit,
            'keterangan' => $request->keterangan,
            'is_active'  => $request->has('is_active') ? true : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Unit Layanan berhasil ditambahkan.',
                'data'    => $item
            ]);
        }

        return redirect('/master-unit-layanan')->with('success', 'Data Unit Layanan berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $item = UnitLayanan::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }
        $profil = ProfilFaskes::first();
        return view('lokasi.unit_layanan_create', compact('item', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_unit'  => 'required|unique:unit_layanans,kode_unit,' . $id . '|max:20',
            'nama_unit'  => 'required|max:255',
            'keterangan' => 'nullable',
        ]);

        $item = UnitLayanan::findOrFail($id);
        $item->update([
            'kode_unit'  => strtoupper($request->kode_unit),
            'nama_unit'  => $request->nama_unit,
            'keterangan' => $request->keterangan,
            'is_active'  => $request->has('is_active') ? true : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Unit Layanan berhasil diperbarui.',
                'data'    => $item
            ]);
        }

        return redirect('/master-unit-layanan')->with('success', 'Data Unit Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        UnitLayanan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Unit Layanan berhasil dihapus.']);
    }
}
