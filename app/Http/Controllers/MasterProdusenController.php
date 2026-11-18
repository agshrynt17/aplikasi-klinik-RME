<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterProdusen;

class MasterProdusenController extends Controller
{
    /**
     * Halaman Data Produsen (1:1 KONHAKES /produsen/index)
     */
    public function index(Request $request)
    {
        $query = MasterProdusen::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('kode_produsen', 'like', "%{$keyword}%")
                  ->orWhere('nama_produsen', 'like', "%{$keyword}%")
                  ->orWhere('telp', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kode_produsen')) {
            $query->where('kode_produsen', 'like', '%' . $request->kode_produsen . '%');
        }

        if ($request->filled('nama_produsen')) {
            $query->where('nama_produsen', 'like', '%' . $request->nama_produsen . '%');
        }

        $produsens = $query->orderBy('kode_produsen', 'asc')->paginate(20)->withQueryString();

        // Auto Code untuk modal Tambah
        $last = MasterProdusen::where('kode_produsen', 'like', 'PROD%')->orderBy('kode_produsen', 'desc')->first();
        if ($last && preg_match('/PROD(\d+)/', $last->kode_produsen, $m)) {
            $nextNum = intval($m[1]) + 1;
        } else {
            $nextNum = 1;
        }
        $autoKodeProdusen = 'PROD' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('farmasi.master_produsen', compact('produsens', 'autoKodeProdusen'));
    }

    /**
     * Halaman Tambah Produsen
     */
    public function create()
    {
        $last = MasterProdusen::where('kode_produsen', 'like', 'PROD%')->orderBy('kode_produsen', 'desc')->first();
        if ($last && preg_match('/PROD(\d+)/', $last->kode_produsen, $m)) {
            $nextNum = intval($m[1]) + 1;
        } else {
            $nextNum = 1;
        }
        $kode_produsen = 'PROD' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('farmasi.master_produsen_create', compact('kode_produsen'));
    }

    /**
     * Simpan Data Produsen Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_produsen' => 'required|unique:master_produsens,kode_produsen|max:50',
            'nama_produsen' => 'required|string|max:255',
        ]);

        try {
            $produsen = MasterProdusen::create([
                'kode_produsen' => strtoupper($request->kode_produsen),
                'nama_produsen' => $request->nama_produsen,
                'telp'          => $request->telp ?: 'xxx',
                'fax'           => $request->fax,
                'email'         => $request->email ?: 'xxx',
                'alamat'        => $request->alamat ?: 'xxx',
                'kontak_person' => $request->kontak_person,
                'is_active'     => $request->has('is_active') ? (bool)$request->is_active : true,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Produsen berhasil disimpan.',
                    'data'    => $produsen
                ]);
            }

            return redirect('/produsen/index')->with('success', 'Data Produsen berhasil disimpan.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail Data Produsen (JSON or view)
     */
    public function show($id)
    {
        $produsen = MasterProdusen::findOrFail($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $produsen
            ]);
        }
        return view('farmasi.master_produsen_create', compact('produsen'));
    }

    /**
     * Halaman Edit Produsen
     */
    public function edit($id)
    {
        $produsen = MasterProdusen::findOrFail($id);
        return view('farmasi.master_produsen_create', compact('produsen'));
    }

    /**
     * Update Data Produsen
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_produsen' => 'required|max:50|unique:master_produsens,kode_produsen,' . $id,
            'nama_produsen' => 'required|string|max:255',
        ]);

        try {
            $produsen = MasterProdusen::findOrFail($id);
            $produsen->update([
                'kode_produsen' => strtoupper($request->kode_produsen),
                'nama_produsen' => $request->nama_produsen,
                'telp'          => $request->telp,
                'fax'           => $request->fax,
                'email'         => $request->email,
                'alamat'        => $request->alamat,
                'kontak_person' => $request->kontak_person,
                'is_active'     => $request->has('is_active') ? (bool)$request->is_active : $produsen->is_active,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Produsen berhasil diperbarui.',
                    'data'    => $produsen
                ]);
            }

            return redirect('/produsen/index')->with('success', 'Data Produsen berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Data Produsen
     */
    public function destroy($id)
    {
        try {
            MasterProdusen::findOrFail($id)->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Produsen berhasil dihapus.'
                ]);
            }

            return redirect('/produsen/index')->with('success', 'Data Produsen berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
