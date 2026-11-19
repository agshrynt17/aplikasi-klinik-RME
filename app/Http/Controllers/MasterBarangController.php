<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterBarang;
use App\Models\MasterProdusen;

class MasterBarangController extends Controller
{
    /**
     * Halaman Data Barang (1:1 KONHAKES /barang/index)
     */
    public function index(Request $request)
    {
        $query = MasterBarang::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('jenis', 'like', "%{$keyword}%")
                  ->orWhere('kemasan', 'like', "%{$keyword}%")
                  ->orWhere('golongan_obat', 'like', "%{$keyword}%")
                  ->orWhere('kelas_terapi', 'like', "%{$keyword}%")
                  ->orWhere('produsen', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kode_barang')) {
            $query->where('kode_barang', 'like', '%' . $request->kode_barang . '%');
        }

        if ($request->filled('nama_barang')) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('golongan_obat')) {
            $query->where('golongan_obat', $request->golongan_obat);
        }

        $barangs = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();

        return view('farmasi.master_barang', compact('barangs'));
    }

    /**
     * Halaman Tambah Barang (1:1 KONHAKES /barang/create)
     */
    public function create()
    {
        $produsens = MasterProdusen::orderBy('nama_produsen')->get();
        return view('farmasi.master_barang_create', compact('produsens'));
    }

    /**
     * Simpan Data Barang Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis'       => 'nullable|string|max:50',
            'kemasan'     => 'nullable|string|max:50',
        ]);

        try {
            // Auto generate kode_barang: ANFEK-00xxxx
            $last = MasterBarang::where('kode_barang', 'like', 'ANFEK-%')->orderBy('id', 'desc')->first();
            if ($last && preg_match('/ANFEK-(\d+)/', $last->kode_barang, $m)) {
                $nextNum = intval($m[1]) + 1;
            } else {
                $nextNum = MasterBarang::count() + 1;
            }
            $kode_barang = $request->kode_barang ?: ('ANFEK-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT));

            $barang = MasterBarang::create([
                'kode_barang'      => strtoupper($kode_barang),
                'nama_barang'      => $request->nama_barang,
                'jenis'            => $request->jenis ?: 'Obat',
                'kemasan'          => $request->kemasan,
                'kelas_terapi'     => $request->kelas_terapi,
                'golongan_obat'    => $request->golongan_obat,
                'bentuk_obat'      => $request->bentuk_obat,
                'rute_obat'        => $request->rute_obat,
                'hna'              => $request->hna ?? 0,
                'minimum_stok'     => $request->minimum_stok ?? 1,
                'is_formularium'   => (bool)$request->is_formularium,
                'produsen'         => $request->produsen,
                'jenis_produk_kfa' => $request->jenis_produk_kfa,
                'kode_kfa'         => $request->kode_kfa,
                'nama_kfa'         => $request->nama_kfa,
                'keterangan'       => $request->keterangan,
                'is_active'        => $request->status === 'Non Aktif' ? false : true,
                'harga_beli'       => $request->hna ?? 0,
                'harga_jual'       => $request->hna ? ($request->hna * 1.2) : 0,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Barang berhasil disimpan.',
                    'data'    => $barang
                ]);
            }

            return redirect('/barang/index')->with('success', 'Data Barang berhasil disimpan.');
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
     * Detail Data Barang (JSON or view)
     */
    public function show($id)
    {
        $barang = MasterBarang::findOrFail($id);
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $barang
            ]);
        }
        return view('farmasi.master_barang_create', compact('barang'));
    }

    /**
     * Halaman Edit Barang
     */
    public function edit($id)
    {
        $barang = MasterBarang::findOrFail($id);
        $produsens = MasterProdusen::orderBy('nama_produsen')->get();
        return view('farmasi.master_barang_create', compact('barang', 'produsens'));
    }

    /**
     * Update Data Barang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
        ]);

        try {
            $barang = MasterBarang::findOrFail($id);
            $barang->update([
                'nama_barang'      => $request->nama_barang,
                'jenis'            => $request->jenis ?: $barang->jenis,
                'kemasan'          => $request->kemasan,
                'kelas_terapi'     => $request->kelas_terapi,
                'golongan_obat'    => $request->golongan_obat,
                'bentuk_obat'      => $request->bentuk_obat,
                'rute_obat'        => $request->rute_obat,
                'hna'              => $request->hna ?? $barang->hna,
                'minimum_stok'     => $request->minimum_stok ?? $barang->minimum_stok,
                'is_formularium'   => (bool)$request->is_formularium,
                'produsen'         => $request->produsen,
                'jenis_produk_kfa' => $request->jenis_produk_kfa,
                'kode_kfa'         => $request->kode_kfa,
                'nama_kfa'         => $request->nama_kfa,
                'keterangan'       => $request->keterangan,
                'is_active'        => $request->status === 'Non Aktif' ? false : true,
                'harga_beli'       => $request->hna ?? $barang->harga_beli,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Barang berhasil diperbarui.',
                    'data'    => $barang
                ]);
            }

            return redirect('/barang/index')->with('success', 'Data Barang berhasil diperbarui.');
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
     * Quick Update Minimum Stock
     */
    public function updateMinStok(Request $request, $id)
    {
        $request->validate([
            'minimum_stok' => 'required|integer|min:0',
        ]);

        $barang = MasterBarang::findOrFail($id);
        $barang->update([
            'minimum_stok' => $request->minimum_stok
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Minimum stok berhasil diperbarui.',
            'minimum_stok' => $barang->minimum_stok
        ]);
    }

    /**
     * Hapus Data Barang
     */
    public function destroy($id)
    {
        try {
            MasterBarang::findOrFail($id)->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Barang berhasil dihapus.'
                ]);
            }

            return redirect('/barang/index')->with('success', 'Data Barang berhasil dihapus.');
        } catch (\Exception $e) {
            if ($request->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export / Download Data Barang (CSV)
     */
    public function download()
    {
        $barangs = MasterBarang::orderBy('id', 'asc')->get();
        $filename = "master_barang_" . date('Ymd_His') . ".csv";

        return response()->streamDownload(function () use ($barangs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Kode Barang', 'Nama Barang', 'Jenis', 'Kemasan', 'Golongan', 
                'Kelas Terapi', 'Bentuk Obat', 'Rute Obat', 'HNA', 'Minimum Stok', 
                'Produsen', 'Kode KFA', 'Nama KFA', 'Keterangan', 'Status'
            ]);

            foreach ($barangs as $b) {
                fputcsv($handle, [
                    $b->kode_barang,
                    $b->nama_barang,
                    $b->jenis,
                    $b->kemasan,
                    $b->golongan_obat,
                    $b->kelas_terapi,
                    $b->bentuk_obat,
                    $b->rute_obat,
                    $b->hna,
                    $b->minimum_stok,
                    $b->produsen,
                    $b->kode_kfa,
                    $b->nama_kfa,
                    $b->keterangan,
                    $b->is_active ? 'Aktif' : 'Non Aktif'
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
