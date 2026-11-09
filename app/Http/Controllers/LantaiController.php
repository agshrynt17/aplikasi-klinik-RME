<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lantai;
use App\Models\Gedung;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class LantaiController extends Controller
{
    public function index(Request $request)
    {
        // Self-healing default seed matching screenshot if less than 2 items
        $ga = Gedung::where('kode_gedung', '01')->first();
        $gb = Gedung::where('kode_gedung', '02')->first();
        if ($ga && $gb && Lantai::count() < 2) {
            $l1 = Lantai::updateOrCreate(['kode_lantai' => '01'], ['nama_lantai' => 'Lantai 1', 'gedung_id' => $ga->id, 'is_active' => true]);
            $l2 = Lantai::updateOrCreate(['kode_lantai' => '02'], ['nama_lantai' => 'Lantai 1', 'gedung_id' => $gb->id, 'is_active' => true]);
            \App\Models\Ruangan::where('lantai_id', '!=', $l1->id)->where('lantai_id', '!=', $l2->id)->update(['lantai_id' => $l1->id]);
            Lantai::whereNotIn('kode_lantai', ['01', '02'])->delete();
        }

        $query = Lantai::with('gedung');

        if ($request->filled('gedung')) {
            $query->whereHas('gedung', function ($q) use ($request) {
                $q->where('nama_gedung', 'like', '%' . $request->gedung . '%')
                  ->orWhere('kode_gedung', 'like', '%' . $request->gedung . '%');
            });
        }

        if ($request->filled('nama_lantai')) {
            $query->where('nama_lantai', 'like', '%' . $request->nama_lantai . '%');
        }

        $data    = $query->orderBy('kode_lantai', 'asc')->get();
        $gedungs = Gedung::where('is_active', true)->orderBy('kode_gedung')->get();
        $profil  = ProfilFaskes::first();

        return view('lokasi.lantai', compact('data', 'gedungs', 'profil'));
    }

    public function create()
    {
        $gedungs = Gedung::where('is_active', true)->orderBy('kode_gedung')->get();
        $profil  = ProfilFaskes::first();
        return view('lokasi.lantai_create', compact('gedungs', 'profil'));
    }

    public function show($id)
    {
        $item = Lantai::with('gedung')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gedung_id'   => 'required|exists:gedungs,id',
            'kode_lantai' => 'required|unique:lantais,kode_lantai|max:20',
            'nama_lantai' => 'required|max:255',
            'keterangan'  => 'nullable',
        ]);

        $item = Lantai::create([
            'gedung_id'   => $request->gedung_id,
            'kode_lantai' => strtoupper($request->kode_lantai),
            'nama_lantai' => $request->nama_lantai,
            'keterangan'  => $request->keterangan,
            'is_active'   => $request->has('is_active') ? true : false,
        ]);
        $item->load('gedung');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Lantai berhasil ditambahkan.',
                'data'    => $item
            ]);
        }

        return redirect('/master-lantai')->with('success', 'Data Lantai berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $item = Lantai::with('gedung')->findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }
        $gedungs = Gedung::where('is_active', true)->orderBy('kode_gedung')->get();
        $profil  = ProfilFaskes::first();
        return view('lokasi.lantai_create', compact('item', 'gedungs', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gedung_id'   => 'required|exists:gedungs,id',
            'kode_lantai' => 'required|unique:lantais,kode_lantai,' . $id . '|max:20',
            'nama_lantai' => 'required|max:255',
            'keterangan'  => 'nullable',
        ]);

        $item = Lantai::findOrFail($id);
        $item->update([
            'gedung_id'   => $request->gedung_id,
            'kode_lantai' => strtoupper($request->kode_lantai),
            'nama_lantai' => $request->nama_lantai,
            'keterangan'  => $request->keterangan,
            'is_active'   => $request->has('is_active') ? true : false,
        ]);

        $item->load('gedung');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Lantai berhasil diperbarui.',
                'data'    => $item
            ]);
        }

        return redirect('/master-lantai')->with('success', 'Data Lantai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Lantai::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Lantai berhasil dihapus.']);
    }
}
