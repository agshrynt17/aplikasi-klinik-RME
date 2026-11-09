<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokAdjustment;
use App\Models\StokAdjustmentDetail;
use App\Models\MasterGudang;
use App\Models\MasterBarang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StokAdjustmentController extends Controller
{
    /**
     * Display a listing of Histori Penyesuaian Stok (PROFASKES 1:1).
     */
    public function index(Request $request)
    {
        $query = StokAdjustment::with(['masterGudang', 'user', 'details.masterBarang']);

        // Search Keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_penyesuaian', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%");
            });
        }

        // Filter Gudang
        if ($request->filled('gudang_id')) {
            $query->where('master_gudang_id', $request->gudang_id);
        }

        // Filter Tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter Tanggal
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_penyesuaian', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_penyesuaian', '<=', $request->tgl_selesai);
        }

        // Sorting
        $sort = $request->input('sort', '-created_at');
        if ($sort === 'created_at') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === '-created_at') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'no') {
            $query->orderBy('no_penyesuaian', 'asc');
        } elseif ($sort === '-no') {
            $query->orderBy('no_penyesuaian', 'desc');
        } elseif ($sort === 'tanggal') {
            $query->orderBy('tanggal_penyesuaian', 'asc');
        } elseif ($sort === '-tanggal') {
            $query->orderBy('tanggal_penyesuaian', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $adjustments = $query->paginate(20)->withQueryString();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();

        return view('farmasi.history_stok', compact('adjustments', 'gudangs'));
    }

    /**
     * Show form for creating a new adjustment.
     */
    public function create(Request $request)
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
     * Store a new adjustment transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gudang_id' => 'required|exists:master_gudangs,id',
            'tanggal'   => 'required|date',
            'tipe'      => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $gudang = MasterGudang::findOrFail($request->gudang_id);

            // Generate No Penyesuaian
            $last = StokAdjustment::whereDate('created_at', date('Y-m-d'))->count();
            $noPenyesuaian = 'ADJ' . date('Ymd') . '-' . sprintf('%03d', $last + 1);

            $userName = auth()->check() ? auth()->user()->name : 'dr. FAHMI RIZAL';

            $adjustment = StokAdjustment::create([
                'no_penyesuaian'      => $noPenyesuaian,
                'tanggal_penyesuaian' => $request->tanggal,
                'master_gudang_id'    => $gudang->id,
                'kode_gudang'         => $gudang->kode_gudang,
                'tipe'                => $request->tipe,
                'keterangan'          => $request->keterangan ?? $request->catatan,
                'user_id'             => auth()->id(),
                'user_name'           => $userName,
            ]);

            // Handle items: single item from adjustment_create or multiple items array
            if ($request->filled('master_barang_id') && $request->filled('jml_fisik')) {
                $barang = MasterBarang::lockForUpdate()->findOrFail($request->master_barang_id);
                $stokGudang = StokGudang::firstOrNew([
                    'master_gudang_id' => $gudang->id,
                    'master_barang_id' => $barang->id,
                ]);

                $stokSistem = $stokGudang->exists ? $stokGudang->stok : $barang->stok;
                $stokFisik = (int)$request->jml_fisik;
                $selisih = $stokFisik - $stokSistem;

                StokAdjustmentDetail::create([
                    'stok_adjustment_id' => $adjustment->id,
                    'master_barang_id'   => $barang->id,
                    'stok_sistem'        => $stokSistem,
                    'stok_fisik'         => $stokFisik,
                    'selisih'            => $selisih,
                    'satuan'             => $barang->kemasan ?? 'Pcs',
                    'catatan'            => $request->catatan,
                ]);

                // Update stocks
                $stokGudang->stok = $stokFisik;
                $stokGudang->save();

                $barang->stok = $stokFisik;
                $barang->save();

                // Record history
                HistoryStok::create([
                    'master_barang_id'  => $barang->id,
                    'master_gudang_id'  => $gudang->id,
                    'jenis_penyesuaian' => $selisih >= 0 ? 'Penambahan' : 'Pengurangan',
                    'tipe_penyesuaian'  => $request->tipe,
                    'tanggal'           => $request->tanggal,
                    'jumlah'            => abs($selisih),
                    'stok_sebelum'      => $stokSistem,
                    'stok_sesudah'      => $stokFisik,
                    'keterangan'        => "{$adjustment->no_penyesuaian}: " . ($request->catatan ?? $request->tipe),
                    'status'            => 'Berhasil'
                ]);
            } elseif ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    if (empty($item['master_barang_id'])) continue;

                    $barang = MasterBarang::lockForUpdate()->findOrFail($item['master_barang_id']);
                    $stokGudang = StokGudang::firstOrNew([
                        'master_gudang_id' => $gudang->id,
                        'master_barang_id' => $barang->id,
                    ]);

                    $stokSistem = $stokGudang->exists ? $stokGudang->stok : $barang->stok;
                    $stokFisik = (int)$item['jml_fisik'];
                    $selisih = $stokFisik - $stokSistem;

                    StokAdjustmentDetail::create([
                        'stok_adjustment_id' => $adjustment->id,
                        'master_barang_id'   => $barang->id,
                        'stok_sistem'        => $stokSistem,
                        'stok_fisik'         => $stokFisik,
                        'selisih'            => $selisih,
                        'satuan'             => $barang->kemasan ?? 'Pcs',
                        'catatan'            => $item['catatan'] ?? null,
                    ]);

                    $stokGudang->stok = $stokFisik;
                    $stokGudang->save();

                    $barang->stok = $stokFisik;
                    $barang->save();

                    HistoryStok::create([
                        'master_barang_id'  => $barang->id,
                        'master_gudang_id'  => $gudang->id,
                        'jenis_penyesuaian' => $selisih >= 0 ? 'Penambahan' : 'Pengurangan',
                        'tipe_penyesuaian'  => $request->tipe,
                        'tanggal'           => $request->tanggal,
                        'jumlah'            => abs($selisih),
                        'stok_sebelum'      => $stokSistem,
                        'stok_sesudah'      => $stokFisik,
                        'keterangan'        => "{$adjustment->no_penyesuaian}: " . ($item['catatan'] ?? $request->tipe),
                        'status'            => 'Berhasil'
                    ]);
                }
            }

            DB::commit();

            return redirect('/stok-adjustment/index')->with('success', "Penyesuaian stok {$adjustment->no_penyesuaian} berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penyesuaian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified adjustment.
     */
    public function show(Request $request, $id = null)
    {
        $targetId = $id ?? $request->query('id');
        $adjustment = StokAdjustment::with(['masterGudang', 'user', 'details.masterBarang'])->findOrFail($targetId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $adjustment,
            ]);
        }

        return view('farmasi.stok_adjustment_detail', compact('adjustment'));
    }

    /**
     * Export report to XLS/CSV.
     */
    public function exportXls(Request $request)
    {
        $adjustments = StokAdjustment::with(['masterGudang', 'details'])->orderBy('id', 'desc')->get();
        $filename = 'Histori_Penyesuaian_Stok_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($adjustments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'No Penyesuaian', 'Tgl. Penyesuaian', 'Gudang', 'Tipe', 'Jumlah Item', 'Tgl. Input', 'User', 'Keterangan']);

            foreach ($adjustments as $idx => $adj) {
                fputcsv($handle, [
                    $idx + 1,
                    $adj->no_penyesuaian,
                    $adj->tanggal_penyesuaian ? $adj->tanggal_penyesuaian->format('Y-m-d') : '-',
                    $adj->masterGudang->nama_gudang ?? $adj->kode_gudang,
                    $adj->tipe,
                    $adj->details->count(),
                    $adj->created_at ? $adj->created_at->format('Y-m-d H:i:s') : '-',
                    $adj->user_name ?? ($adj->user->name ?? '-'),
                    $adj->keterangan ?? '-',
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    /**
     * Export report to PDF / Print view.
     */
    public function exportPdf(Request $request)
    {
        $adjustments = StokAdjustment::with(['masterGudang', 'details.masterBarang'])->orderBy('id', 'desc')->get();
        return view('farmasi.stok_adjustment_pdf', compact('adjustments'));
    }
}
