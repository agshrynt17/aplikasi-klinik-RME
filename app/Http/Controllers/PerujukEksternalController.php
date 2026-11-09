<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PerujukEksternal;
use Illuminate\Http\Request;

class PerujukEksternalController extends Controller
{
    public function index(Request $request)
    {
        $query = PerujukEksternal::query();

        if ($request->filled('nama_perujuk')) {
            $query->where('nama_perujuk', 'like', "%{$request->nama_perujuk}%");
        }

        if ($request->filled('institusi')) {
            $query->where('institusi', 'like', "%{$request->institusi}%");
        }

        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', "%{$request->alamat}%");
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('is_active', $request->status == '1');
        }

        $data = $query->orderBy('nama_perujuk', 'asc')->get();

        return view('laboratorium.perujuk_eksternal_index', compact('data'));
    }

    public function create()
    {
        return view('laboratorium.perujuk_eksternal_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perujuk' => 'required|string|max:200',
            'institusi'    => 'nullable|string|max:200',
            'no_telepon'   => 'nullable|string|max:50',
            'alamat'       => 'nullable|string|max:500',
        ]);

        $last = PerujukEksternal::max('id') ?? 0;
        $kode = 'PRJ-' . str_pad($last + 1, 3, '0', STR_PAD_LEFT);

        $item = PerujukEksternal::create([
            'kode_perujuk'  => $kode,
            'nama_perujuk'  => $request->nama_perujuk,
            'jenis_perujuk' => $request->jenis_perujuk ?? 'Dokter',
            'institusi'     => $request->institusi,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
            'is_active'     => $request->input('is_active', '1') == '1',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perujuk Eksternal berhasil ditambahkan.',
                'data'    => $item,
            ]);
        }

        return redirect('/perujuk-eksternal/index')->with('success', 'Data Perujuk Eksternal berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = PerujukEksternal::findOrFail($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }
        return view('laboratorium.perujuk_eksternal_create', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perujuk' => 'required|string|max:200',
            'institusi'    => 'nullable|string|max:200',
            'no_telepon'   => 'nullable|string|max:50',
            'alamat'       => 'nullable|string|max:500',
        ]);

        $item = PerujukEksternal::findOrFail($id);
        $item->update([
            'nama_perujuk'  => $request->nama_perujuk,
            'jenis_perujuk' => $request->jenis_perujuk ?? $item->jenis_perujuk,
            'institusi'     => $request->institusi,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
            'is_active'     => $request->input('is_active', '1') == '1',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perujuk Eksternal berhasil diperbarui.',
                'data'    => $item,
            ]);
        }

        return redirect('/perujuk-eksternal/index')->with('success', 'Data Perujuk Eksternal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PerujukEksternal::findOrFail($id)->delete();
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data Perujuk Eksternal berhasil dihapus.']);
        }
        return redirect('/perujuk-eksternal/index')->with('success', 'Data Perujuk Eksternal berhasil dihapus.');
    }
}
