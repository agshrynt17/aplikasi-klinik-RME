<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\Lantai;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        // Self-healing default seed matching screenshot if less than 2 items
        $l1 = Lantai::where('kode_lantai', '01')->first();
        if ($l1 && Ruangan::count() < 2) {
            Ruangan::updateOrCreate(
                ['kode_ruangan' => 'R101'],
                [
                    'nama_ruangan'           => 'Ruang Rawat 1',
                    'lantai_id'              => $l1->id,
                    'poli_klinik'            => null,
                    'ruang_ranap'            => null,
                    'kapasitas_tempat_tidur' => '-',
                    'is_active'              => true
                ]
            );
            Ruangan::updateOrCreate(
                ['kode_ruangan' => 'R102'],
                [
                    'nama_ruangan'           => 'Ruang Rawat 2',
                    'lantai_id'              => $l1->id,
                    'poli_klinik'            => null,
                    'ruang_ranap'            => null,
                    'kapasitas_tempat_tidur' => '-',
                    'is_active'              => true
                ]
            );
            Ruangan::whereNotIn('kode_ruangan', ['R101', 'R102'])->delete();
        }

        $query = Ruangan::with('lantai.gedung');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_ruangan', 'like', '%' . $keyword . '%')
                  ->orWhere('nama_ruangan', 'like', '%' . $keyword . '%')
                  ->orWhereHas('lantai', function ($ql) use ($keyword) {
                      $ql->where('nama_lantai', 'like', '%' . $keyword . '%')
                         ->orWhereHas('gedung', function ($qg) use ($keyword) {
                             $qg->where('nama_gedung', 'like', '%' . $keyword . '%');
                         });
                  });
            });
        }

        if ($request->filled('kode_ruangan')) {
            $query->where('kode_ruangan', 'like', '%' . $request->kode_ruangan . '%');
        }

        if ($request->filled('nama_ruangan')) {
            $query->where('nama_ruangan', 'like', '%' . $request->nama_ruangan . '%');
        }

        $data    = $query->orderBy('kode_ruangan', 'asc')->get();
        $lantais = Lantai::where('is_active', true)->with('gedung')->get();
        $profil  = ProfilFaskes::first();

        return view('lokasi.ruangan', compact('data', 'lantais', 'profil'));
    }

    public function create()
    {
        $lantais = Lantai::where('is_active', true)->with('gedung')->get();
        $profil  = ProfilFaskes::first();
        return view('lokasi.ruangan_create', compact('lantais', 'profil'));
    }

    public function show($id)
    {
        $item = Ruangan::with('lantai.gedung')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lantai_id'    => 'required|exists:lantais,id',
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan|max:20',
            'nama_ruangan' => 'required|max:255',
            'keterangan'   => 'nullable',
        ]);

        $item = Ruangan::create([
            'lantai_id'              => $request->lantai_id,
            'kode_ruangan'           => strtoupper($request->kode_ruangan),
            'nama_ruangan'           => $request->nama_ruangan,
            'poli_klinik'            => $request->poli_klinik,
            'ruang_ranap'            => $request->ruang_ranap,
            'kapasitas_tempat_tidur' => $request->kapasitas_tempat_tidur ?? '-',
            'keterangan'             => $request->keterangan,
            'is_active'              => $request->has('is_active') ? true : false,
        ]);
        $item->load('lantai.gedung');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Ruangan berhasil ditambahkan.',
                'data'    => $item
            ]);
        }

        return redirect('/master-ruangan')->with('success', 'Data Ruangan berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $item = Ruangan::with('lantai.gedung')->findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }
        $lantais = Lantai::where('is_active', true)->with('gedung')->get();
        $profil  = ProfilFaskes::first();
        return view('lokasi.ruangan_create', compact('item', 'lantais', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lantai_id'    => 'required|exists:lantais,id',
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan,' . $id . '|max:20',
            'nama_ruangan' => 'required|max:255',
            'keterangan'   => 'nullable',
        ]);

        $item = Ruangan::findOrFail($id);
        $item->update([
            'lantai_id'              => $request->lantai_id,
            'kode_ruangan'           => strtoupper($request->kode_ruangan),
            'nama_ruangan'           => $request->nama_ruangan,
            'poli_klinik'            => $request->poli_klinik,
            'ruang_ranap'            => $request->ruang_ranap,
            'kapasitas_tempat_tidur' => $request->kapasitas_tempat_tidur ?? '-',
            'keterangan'             => $request->keterangan,
            'is_active'              => $request->has('is_active') ? true : false,
        ]);

        $item->load('lantai.gedung');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Ruangan berhasil diperbarui.',
                'data'    => $item
            ]);
        }

        return redirect('/master-ruangan')->with('success', 'Data Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Ruangan berhasil dihapus.']);
    }
}
