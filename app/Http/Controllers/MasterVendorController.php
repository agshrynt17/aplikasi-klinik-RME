<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterVendor;

class MasterVendorController extends Controller
{
    /**
     * Halaman Data Vendor (1:1 PROFASKES /vendor/index)
     */
    public function index(Request $request)
    {
        $query = MasterVendor::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('kode_vendor', 'like', "%{$keyword}%")
                  ->orWhere('nama_vendor', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%")
                  ->orWhere('telp', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('nama_pic', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kode_vendor')) {
            $query->where('kode_vendor', 'like', '%' . $request->kode_vendor . '%');
        }

        if ($request->filled('nama_vendor')) {
            $query->where('nama_vendor', 'like', '%' . $request->nama_vendor . '%');
        }

        $vendors = $query->orderByRaw('CAST(kode_vendor AS UNSIGNED) ASC')->orderBy('kode_vendor', 'asc')->paginate(20)->withQueryString();

        return view('farmasi.master_vendor', compact('vendors'));
    }

    /**
     * Halaman Tambah Vendor (1:1 PROFASKES /vendor/create)
     */
    public function create()
    {
        $all = MasterVendor::all();
        $maxNum = 0;
        foreach ($all as $v) {
            if (is_numeric($v->kode_vendor)) {
                $val = intval($v->kode_vendor);
                if ($val > $maxNum) {
                    $maxNum = $val;
                }
            }
        }
        $kode_vendor = str_pad($maxNum + 1, 2, '0', STR_PAD_LEFT);

        return view('farmasi.master_vendor_create', compact('kode_vendor'));
    }

    /**
     * Simpan Data Vendor Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_vendor' => 'required|unique:master_vendors,kode_vendor|max:50',
            'nama_vendor' => 'required|string|max:255',
        ]);

        try {
            $vendor = MasterVendor::create([
                'kode_vendor'   => $request->kode_vendor,
                'nama_vendor'   => $request->nama_vendor,
                'join_date'     => $request->join_date,
                'alamat'        => $request->alamat,
                'telp'          => $request->telp ?: $request->no_telepon,
                'no_telepon'    => $request->telp ?: $request->no_telepon,
                'fax'           => $request->fax,
                'email'         => $request->email,
                'nomor_spk'     => $request->nomor_spk,
                'expired_spk'   => $request->expired_spk,
                'nama_pic'      => $request->nama_pic ?: $request->kontak_person,
                'kontak_person' => $request->nama_pic ?: $request->kontak_person,
                'telp_pic'      => $request->telp_pic,
                'email_pic'     => $request->email_pic,
                'is_active'     => $request->has('is_active') ? (bool)$request->is_active : true,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Vendor berhasil disimpan.',
                    'data'    => $vendor
                ]);
            }

            return redirect('/vendor/index')->with('success', 'Data Vendor berhasil disimpan.');
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
     * Detail Data Vendor (JSON or view)
     */
    public function show($id)
    {
        $vendor = MasterVendor::findOrFail($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $vendor
            ]);
        }
        return view('farmasi.master_vendor_create', compact('vendor'));
    }

    /**
     * Halaman Edit Vendor
     */
    public function edit($id)
    {
        $vendor = MasterVendor::findOrFail($id);
        return view('farmasi.master_vendor_create', compact('vendor'));
    }

    /**
     * Update Data Vendor
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_vendor' => 'required|max:50|unique:master_vendors,kode_vendor,' . $id,
            'nama_vendor' => 'required|string|max:255',
        ]);

        try {
            $vendor = MasterVendor::findOrFail($id);
            $vendor->update([
                'kode_vendor'   => $request->kode_vendor,
                'nama_vendor'   => $request->nama_vendor,
                'join_date'     => $request->join_date,
                'alamat'        => $request->alamat,
                'telp'          => $request->telp ?: $request->no_telepon,
                'no_telepon'    => $request->telp ?: $request->no_telepon,
                'fax'           => $request->fax,
                'email'         => $request->email,
                'nomor_spk'     => $request->nomor_spk,
                'expired_spk'   => $request->expired_spk,
                'nama_pic'      => $request->nama_pic ?: $request->kontak_person,
                'kontak_person' => $request->nama_pic ?: $request->kontak_person,
                'telp_pic'      => $request->telp_pic,
                'email_pic'     => $request->email_pic,
                'is_active'     => $request->has('is_active') ? (bool)$request->is_active : $vendor->is_active,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Vendor berhasil diperbarui.',
                    'data'    => $vendor
                ]);
            }

            return redirect('/vendor/index')->with('success', 'Data Vendor berhasil diperbarui.');
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
     * Hapus Data Vendor
     */
    public function destroy($id)
    {
        try {
            MasterVendor::findOrFail($id)->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Vendor berhasil dihapus.'
                ]);
            }

            return redirect('/vendor/index')->with('success', 'Data Vendor berhasil dihapus.');
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
