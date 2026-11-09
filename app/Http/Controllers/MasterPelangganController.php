<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterPelanggan;

class MasterPelangganController extends Controller
{
    /**
     * Halaman Data Pelanggan (1:1 PROFASKES /pelanggan/index)
     */
    public function index(Request $request)
    {
        $query = MasterPelanggan::query();

        if ($request->filled('nama_pelanggan')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        if ($request->filled('telp')) {
            $query->where(function($q) use ($request) {
                $q->where('telp', 'like', '%' . $request->telp . '%')
                  ->orWhere('no_telepon', 'like', '%' . $request->telp . '%');
            });
        }

        if ($request->filled('kode_prop')) {
            $query->where('kode_prop', 'like', '%' . $request->kode_prop . '%');
        }

        if ($request->filled('kode_kab')) {
            $query->where('kode_kab', 'like', '%' . $request->kode_kab . '%');
        }

        if ($request->filled('kode_kec')) {
            $query->where('kode_kec', 'like', '%' . $request->kode_kec . '%');
        }

        if ($request->filled('kode_desa')) {
            $query->where('kode_desa', 'like', '%' . $request->kode_desa . '%');
        }

        $pelanggans = $query->orderBy('kode_pelanggan', 'desc')->paginate(20)->withQueryString();

        return view('farmasi.master_pelanggan', compact('pelanggans'));
    }

    /**
     * Halaman Tambah Pelanggan (1:1 PROFASKES /pelanggan/create)
     */
    public function create()
    {
        // Generate Auto Code PL + yymmdd + 00001 (1:1 PROFASKES)
        $datePrefix = 'PL' . date('ymd');
        $last = MasterPelanggan::where('kode_pelanggan', 'like', $datePrefix . '%')->orderBy('kode_pelanggan', 'desc')->first();
        
        if ($last && preg_match('/' . $datePrefix . '(\d+)/', $last->kode_pelanggan, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }
        
        $kode_pelanggan = $datePrefix . str_pad($num, 5, '0', STR_PAD_LEFT);

        return view('farmasi.master_pelanggan_create', compact('kode_pelanggan'));
    }

    /**
     * Simpan Data Pelanggan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required|unique:master_pelanggans,kode_pelanggan|max:50',
            'nama_pelanggan' => 'required|string|max:255',
        ]);

        try {
            MasterPelanggan::create([
                'kode_pelanggan' => strtoupper($request->kode_pelanggan),
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'alamat'         => $request->alamat,
                'kode_prop'      => $request->kode_prop,
                'kode_kab'       => $request->kode_kab,
                'kode_kec'       => $request->kode_kec,
                'kode_desa'      => $request->kode_desa,
                'telp'           => $request->telp ?: $request->no_telepon,
                'no_telepon'     => $request->telp ?: $request->no_telepon,
                'email'          => $request->email,
            ]);

            return redirect('/pelanggan/index')->with('success', 'Data Pelanggan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Halaman Edit Pelanggan
     */
    public function edit($id)
    {
        $pelanggan = MasterPelanggan::findOrFail($id);
        return view('farmasi.master_pelanggan_create', compact('pelanggan'));
    }

    /**
     * Update Data Pelanggan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_pelanggan' => 'required|max:50|unique:master_pelanggans,kode_pelanggan,' . $id,
            'nama_pelanggan' => 'required|string|max:255',
        ]);

        try {
            $pelanggan = MasterPelanggan::findOrFail($id);
            $pelanggan->update([
                'kode_pelanggan' => strtoupper($request->kode_pelanggan),
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'alamat'         => $request->alamat,
                'kode_prop'      => $request->kode_prop,
                'kode_kab'       => $request->kode_kab,
                'kode_kec'       => $request->kode_kec,
                'kode_desa'      => $request->kode_desa,
                'telp'           => $request->telp ?: $request->no_telepon,
                'no_telepon'     => $request->telp ?: $request->no_telepon,
                'email'          => $request->email,
            ]);

            return redirect('/pelanggan/index')->with('success', 'Data Pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus Data Pelanggan
     */
    public function destroy($id)
    {
        try {
            MasterPelanggan::findOrFail($id)->delete();
            return redirect('/pelanggan/index')->with('success', 'Data Pelanggan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
