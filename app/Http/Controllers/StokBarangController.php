<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use App\Models\BatchBarang;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StokBarangController extends Controller
{
    /**
     * Display listing of stocks per warehouse (KONHAKES Screenshot 1 & 4).
     */
    public function index(Request $request)
    {
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        
        // Find current warehouse: by kode_gudang, gudang_id, or default to Gudang Farmasi
        $currentGudang = null;
        if ($request->filled('kode_gudang')) {
            $currentGudang = $gudangs->firstWhere('kode_gudang', $request->kode_gudang);
        } elseif ($request->filled('gudang_id')) {
            $currentGudang = $gudangs->firstWhere('id', $request->gudang_id);
        }
        
        if (!$currentGudang) {
            $currentGudang = $gudangs->firstWhere('nama_gudang', 'Gudang Farmasi') ?? $gudangs->first();
        }

        // Query MasterBarang with relations
        $query = MasterBarang::with(['batches', 'stokGudangs' => function ($q) use ($currentGudang) {
            if ($currentGudang) {
                $q->where('master_gudang_id', $currentGudang->id);
            }
        }]);

        // Filter: Jenis Barang
        if ($request->filled('jenis_barang')) {
            $query->where('jenis', $request->jenis_barang);
        }

        // Filter: Kelas Terapi
        if ($request->filled('kelas_terapi')) {
            $query->where('kelas_terapi', $request->kelas_terapi);
        }

        // Filter: Search Keyword (Kode / Nama)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('kode_barang', 'like', "%{$q}%");
            });
        }

        // Sort order
        $sortCol = $request->input('sort', 'id');
        $sortDir = $request->input('dir', 'asc');
        if (in_array($sortCol, ['nama_barang', 'kode_barang', 'jenis', 'kelas_terapi', 'stok'])) {
            $query->orderBy($sortCol, $sortDir);
        } else {
            $query->orderBy('id', 'asc');
        }

        $barangs = $query->paginate(20)->withQueryString();

        // Unique lists for filters
        $jenisList = MasterBarang::whereNotNull('jenis')->distinct()->pluck('jenis');
        $kelasTerapiList = MasterBarang::whereNotNull('kelas_terapi')->distinct()->pluck('kelas_terapi');

        return view('farmasi.stok_barang', compact('barangs', 'gudangs', 'currentGudang', 'jenisList', 'kelasTerapiList'));
    }

    // Alias for existing route /farmasi/stok-barang
    public function indexStok(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Form Tambah Stok Awal Per Gudang (KONHAKES Screenshot 2).
     */
    public function createStokAwal(Request $request)
    {
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $selectedGudang = null;
        if ($request->filled('kode_gudang')) {
            $selectedGudang = $gudangs->firstWhere('kode_gudang', $request->kode_gudang);
        } elseif ($request->filled('gudang_id')) {
            $selectedGudang = $gudangs->firstWhere('id', $request->gudang_id);
        }

        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();

        return view('farmasi.stok_create_awal', compact('gudangs', 'selectedGudang', 'barangs'));
    }

    /**
     * Store Stok Awal Per Gudang.
     */
    public function storeStokAwal(Request $request)
    {
        $request->validate([
            'gudang_id'        => 'required|exists:master_gudangs,id',
            'master_barang_id' => 'required|exists:master_barangs,id',
            'stok_awal'        => 'required|integer',
        ], [
            'gudang_id.required'        => 'Kode Gudang wajib dipilih!',
            'master_barang_id.required' => 'Nama Barang wajib dipilih!',
            'stok_awal.required'        => 'Stok Awal wajib diisi!',
        ]);

        try {
            DB::beginTransaction();

            $barang = MasterBarang::lockForUpdate()->findOrFail($request->master_barang_id);
            $stokAwal = (int)$request->stok_awal;

            $stokGudang = StokGudang::firstOrNew([
                'master_gudang_id' => $request->gudang_id,
                'master_barang_id' => $barang->id,
            ]);

            $stokSebelum = $stokGudang->exists ? $stokGudang->stok : 0;
            $stokSesudah = $stokSebelum + $stokAwal;

            $stokGudang->stok = $stokSesudah;
            $stokGudang->stok_awal = $stokAwal;
            $stokGudang->save();

            // Sync master barang stok
            $barang->stok = $barang->stok + $stokAwal;
            $barang->save();

            // Record History
            HistoryStok::create([
                'master_barang_id'  => $barang->id,
                'master_gudang_id'  => $request->gudang_id,
                'jenis_penyesuaian' => 'Penambahan',
                'tipe_penyesuaian'  => 'Stok Awal',
                'tanggal'           => date('Y-m-d'),
                'jumlah'            => abs($stokAwal),
                'stok_sebelum'      => $stokSebelum,
                'stok_sesudah'      => $stokSesudah,
                'keterangan'        => 'Input Stok Awal Per Gudang',
                'status'            => 'Berhasil'
            ]);

            DB::commit();

            return redirect('/stok/index?gudang_id=' . $request->gudang_id)->with('success', 'Stok Awal berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Form Transaksi Penyesuaian Barang (KONHAKES Screenshot 3).
     */
    public function createAdjustment(Request $request)
    {
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $selectedGudang = null;
        if ($request->filled('kode_gudang')) {
            $selectedGudang = $gudangs->firstWhere('kode_gudang', $request->kode_gudang);
        } elseif ($request->filled('gudang_id')) {
            $selectedGudang = $gudangs->firstWhere('id', $request->gudang_id);
        }
        if (!$selectedGudang) {
            $selectedGudang = $gudangs->firstWhere('nama_gudang', 'Gudang Farmasi') ?? $gudangs->first();
        }

        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();

        return view('farmasi.stok_adjustment_create', compact('gudangs', 'selectedGudang', 'barangs'));
    }

    /**
     * Store Penyesuaian Barang.
     */
    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'gudang_id'        => 'required|exists:master_gudangs,id',
            'tanggal'          => 'required|date',
            'tipe'             => 'required|string',
            'master_barang_id' => 'required|exists:master_barangs,id',
            'jml_fisik'        => 'required|integer',
            'keterangan'       => 'required|string',
        ], [
            'master_barang_id.required' => 'Barang wajib dipilih!',
            'jml_fisik.required'        => 'Jml. Fisik wajib diisi!',
            'keterangan.required'       => 'Keterangan wajib dipilih!',
        ]);

        try {
            DB::beginTransaction();

            $barang = MasterBarang::lockForUpdate()->findOrFail($request->master_barang_id);
            $stokGudang = StokGudang::firstOrNew([
                'master_gudang_id' => $request->gudang_id,
                'master_barang_id' => $barang->id,
            ]);

            $stokSistem = $stokGudang->exists ? $stokGudang->stok : $barang->stok;
            $stokFisik = (int)$request->jml_fisik;
            $selisih = $stokFisik - $stokSistem;

            // Update warehouse stock
            $stokGudang->stok = $stokFisik;
            $stokGudang->save();

            // Update master barang stock
            $barang->stok = $stokFisik;
            $barang->save();

            // Record history
            HistoryStok::create([
                'master_barang_id'  => $barang->id,
                'master_gudang_id'  => $request->gudang_id,
                'jenis_penyesuaian' => $selisih >= 0 ? 'Penambahan' : 'Pengurangan',
                'tipe_penyesuaian'  => $request->tipe,
                'tanggal'           => $request->tanggal,
                'jumlah'            => abs($selisih),
                'stok_sebelum'      => $stokSistem,
                'stok_sesudah'      => $stokFisik,
                'keterangan'        => $request->keterangan . ($request->catatan ? ' (' . $request->catatan . ')' : ''),
                'status'            => 'Berhasil'
            ]);

            DB::commit();

            return redirect('/stok/index?gudang_id=' . $request->gudang_id)->with('success', 'Transaksi Penyesuaian Barang berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penyesuaian: ' . $e->getMessage());
        }
    }

    /**
     * Kartu Stok Barang.
     */
    public function kartuStok($barangId, Request $request)
    {
        $barang = MasterBarang::with('batches')->findOrFail($barangId);
        $gudangId = $request->input('gudang_id');
        
        $query = HistoryStok::where('master_barang_id', $barang->id)->orderBy('id', 'desc');
        if ($gudangId) {
            $query->where('master_gudang_id', $gudangId);
        }
        $histories = $query->paginate(20)->withQueryString();

        return view('farmasi.stok_kartu_stok', compact('barang', 'histories'));
    }

    /**
     * Export Stok Data to CSV/Excel.
     */
    public function export(Request $request)
    {
        $gudangId = $request->input('gudang_id');
        $gudang = MasterGudang::find($gudangId) ?? MasterGudang::first();
        
        $barangs = MasterBarang::with(['stokGudangs' => function ($q) use ($gudang) {
            if ($gudang) $q->where('master_gudang_id', $gudang->id);
        }])->orderBy('nama_barang')->get();

        $filename = 'stok_barang_' . ($gudang ? strtolower(str_replace(' ', '_', $gudang->nama_gudang)) : 'all') . '_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($barangs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Jenis Barang', 'Kelas Terapi', 'Kode Item', 'Nama Barang', 'Stok Tersedia']);

            foreach ($barangs as $idx => $b) {
                $stok = $b->stokGudangs->first()->stok ?? $b->stok;
                fputcsv($handle, [
                    $idx + 1,
                    $b->jenis ?? '-',
                    $b->kelas_terapi ?? '-',
                    $b->kode_barang,
                    $b->nama_barang,
                    $stok,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    /**
     * Form Upload Stok Awal Per Gudang.
     */
    public function uploadForm(Request $request)
    {
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        $selectedGudang = null;
        if ($request->filled('kode_gudang')) {
            $selectedGudang = $gudangs->firstWhere('kode_gudang', $request->kode_gudang);
        } elseif ($request->filled('gudang_id')) {
            $selectedGudang = $gudangs->firstWhere('id', $request->gudang_id);
        }
        if (!$selectedGudang) {
            $selectedGudang = $gudangs->firstWhere('nama_gudang', 'Gudang Farmasi') ?? $gudangs->first();
        }

        return view('farmasi.stok_upload_awal', compact('gudangs', 'selectedGudang'));
    }

    /**
     * Process Upload Stok Awal CSV.
     */
    public function processUpload(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required|exists:master_gudangs,id',
            'file_stok' => 'required|file',
        ], [
            'gudang_id.required' => 'Gudang tujuan wajib dipilih!',
            'file_stok.required' => 'File CSV/Excel wajib diunggah!',
        ]);

        $file = $request->file('file_stok');
        $gudangId = $request->gudang_id;
        $gudang = MasterGudang::findOrFail($gudangId);

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membaca file upload.');
        }

        $header = fgetcsv($handle, 1000, ',');
        // Auto-detect semicolon delimiter if single column detected
        if ($header && count($header) === 1 && strpos($header[0], ';') !== false) {
            rewind($handle);
            $delimiter = ';';
            $header = fgetcsv($handle, 1000, $delimiter);
        } else {
            $delimiter = ',';
        }

        $successCount = 0;
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                    continue;
                }

                $kodeItem = null;
                $stokAwal = 0;

                if (count($row) >= 6 && is_numeric(trim($row[0]))) {
                    // Exported format: No, Jenis, Kelas Terapi, Kode Item, Nama Barang, Stok
                    $kodeItem = trim($row[3] ?? '');
                    $stokAwal = (int)trim($row[5] ?? 0);
                } elseif (count($row) >= 3) {
                    // Standard template format: Kode Item, Nama Barang, Stok Awal
                    $kodeItem = trim($row[0] ?? '');
                    $stokAwal = (int)trim(end($row));
                }

                if (!$kodeItem) {
                    continue;
                }

                $barang = MasterBarang::where('kode_barang', $kodeItem)->first();
                if (!$barang) {
                    $namaBarang = trim($row[1] ?? '');
                    if ($namaBarang) {
                        $barang = MasterBarang::where('nama_barang', $namaBarang)->first();
                    }
                }

                if ($barang) {
                    $stokGudang = StokGudang::firstOrNew([
                        'master_gudang_id' => $gudangId,
                        'master_barang_id' => $barang->id,
                    ]);

                    $stokSebelum = $stokGudang->exists ? $stokGudang->stok : 0;
                    $stokGudang->stok = $stokAwal;
                    $stokGudang->stok_awal = $stokAwal;
                    $stokGudang->save();

                    // Sync MasterBarang stock
                    $barang->stok = $stokAwal;
                    $barang->save();

                    // Log history
                    HistoryStok::create([
                        'master_barang_id'  => $barang->id,
                        'master_gudang_id'  => $gudangId,
                        'jenis_penyesuaian' => 'Penambahan',
                        'tipe_penyesuaian'  => 'Upload Stok Awal',
                        'tanggal'           => date('Y-m-d'),
                        'jumlah'            => abs($stokAwal - $stokSebelum),
                        'stok_sebelum'      => $stokSebelum,
                        'stok_sesudah'      => $stokAwal,
                        'keterangan'        => "Upload Stok Awal CSV ke {$gudang->nama_gudang}",
                        'status'            => 'Berhasil'
                    ]);

                    $successCount++;
                }
            }

            DB::commit();
            fclose($handle);

            return redirect('/stok/index?gudang_id=' . $gudangId)->with('success', "Berhasil mengunggah stok awal untuk {$successCount} barang di {$gudang->nama_gudang}.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses data: ' . $e->getMessage());
        }
    }

    /**
     * Download Template CSV Upload Stok Awal.
     */
    public function downloadTemplate()
    {
        $barangs = MasterBarang::where('is_active', true)->orderBy('kode_barang')->get();
        $filename = 'template_upload_stok_awal_' . date('Ymd') . '.csv';

        $response = new StreamedResponse(function () use ($barangs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode Item', 'Nama Barang', 'Jenis Barang', 'Stok Awal']);

            foreach ($barangs as $b) {
                fputcsv($handle, [
                    $b->kode_barang,
                    $b->nama_barang,
                    $b->jenis ?? 'Obat',
                    $b->stok ?? 0,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    // --- PEMBATALAN STOK ---
    public function indexPembatalan()
    {
        $data = HistoryStok::with(['masterBarang', 'masterGudang'])->where('status', 'Berhasil')->orderBy('id', 'desc')->get();
        return view('farmasi.pembatalan_stok', compact('data'));
    }

    public function batalStok(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $history = HistoryStok::lockForUpdate()->findOrFail($id);
            
            if ($history->status === 'Dibatalkan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi ini sudah pernah dibatalkan.'
                ], 422);
            }

            $barang = MasterBarang::lockForUpdate()->findOrFail($history->master_barang_id);
            $stokSekarang = $barang->stok;

            // Rollback Logic
            $stokBaru = $stokSekarang;
            if ($history->jenis_penyesuaian === 'Penambahan') {
                $stokBaru -= $history->jumlah;
            } else { // Pengurangan
                $stokBaru += $history->jumlah;
            }

            // Update Stok Master
            $barang->update(['stok' => $stokBaru]);

            // Update History Status
            $history->update(['status' => 'Dibatalkan']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penyesuaian stok berhasil dibatalkan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- HISTORY PENYESUAIAN ---
    public function indexHistory()
    {
        $data = HistoryStok::with(['masterBarang', 'masterGudang'])->orderBy('id', 'desc')->get();
        return view('farmasi.history_stok', compact('data'));
    }
}
