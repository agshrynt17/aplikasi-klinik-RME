<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MutasiBarang;
use App\Models\MutasiBarangDetail;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\BatchBarang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MutasiBarangController extends Controller
{
    /**
     * Display a listing of Mutasi Barang Antar Gudang (PROFASKES 1:1).
     */
    public function index(Request $request)
    {
        $query = MutasiBarang::with(['gudangAsal', 'gudangTujuan', 'details.masterBarang'])
            ->orderBy('id', 'desc');

        // Filter: No Mutasi
        if ($request->filled('no_mutasi')) {
            $query->where('nomor_mutasi', 'like', '%' . $request->no_mutasi . '%');
        }

        // Filter: Tgl Awal & Tgl Akhir
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_mutasi', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_mutasi', '<=', $request->tgl_akhir);
        }

        // Filter: Gudang Asal
        if ($request->filled('gudang_asal_id')) {
            $query->where('gudang_asal_id', $request->gudang_asal_id);
        }

        // Filter: Gudang Tujuan
        if ($request->filled('gudang_tujuan_id')) {
            $query->where('gudang_tujuan_id', $request->gudang_tujuan_id);
        }

        // Filter: Sudah Diterima?
        if ($request->filled('sudah_diterima') && $request->sudah_diterima !== 'Semua') {
            $query->where('sudah_diterima', $request->sudah_diterima);
        }

        $data = $query->paginate(20)->withQueryString();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();

        return view('farmasi.mutasi_barang.index', compact('data', 'gudangs'));
    }

    /**
     * Show the form for creating a new Mutasi Barang.
     */
    public function create()
    {
        // Format No Mutasi: MB + YYMMDD + 4-digit nomor urut (e.g. MB2610140001)
        $todayPrefix = 'MB' . date('ymd');
        $lastMutasi = MutasiBarang::where('nomor_mutasi', 'like', "{$todayPrefix}%")
                                  ->orderBy('nomor_mutasi', 'desc')
                                  ->first();

        if ($lastMutasi) {
            $lastSeq = (int) substr($lastMutasi->nomor_mutasi, -4);
            $nextSeq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }
        $nomorMutasi = $todayPrefix . $nextSeq;

        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        if ($gudangs->count() < 2) {
            // Ensure minimum 2 gudangs exist for transfer
            if (!MasterGudang::where('nama_gudang', 'GUDANG AWAL')->exists()) {
                MasterGudang::create([
                    'kode_gudang' => '002',
                    'nama_gudang' => 'GUDANG AWAL',
                    'lokasi'      => 'Logistik Medik',
                    'is_active'   => true
                ]);
            }
            if (!MasterGudang::where('nama_gudang', 'Gudang Farmasi')->exists()) {
                MasterGudang::create([
                    'kode_gudang' => '001',
                    'nama_gudang' => 'Gudang Farmasi',
                    'lokasi'      => 'Instalasi Farmasi',
                    'is_active'   => true
                ]);
            }
            $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();
        }

        $barangs = MasterBarang::where('is_active', true)->orderBy('nama_barang')->get();
        $petugas = Auth::user()->name ?? 'dr. FAHMI RIZAL';

        $from = request()->get('from', '');
        if (!$from && (str_contains(request()->path(), 'penerimaan') || str_contains(url()->previous(), 'penerimaan'))) {
            $from = 'penerimaan';
        }
        $backUrl = ($from === 'penerimaan') ? url('/penerimaanmutasibarang/index') : url('/mutasibarang/index');
        $backTitle = ($from === 'penerimaan') ? 'Penerimaan Mutasi Barang Antar Gudang' : 'Mutasibarangs';

        return view('farmasi.mutasi_barang.create', compact('nomorMutasi', 'gudangs', 'barangs', 'petugas', 'from', 'backUrl', 'backTitle'));
    }

    /**
     * AJAX endpoint to fetch batches for a selected barang.
     */
    public function getBatches($barangId, Request $request)
    {
        $gudangId = $request->get('gudang_id');
        $query = BatchBarang::where('master_barang_id', $barangId)
                            ->where('is_active', true);

        if ($gudangId) {
            $query->where(function($q) use ($gudangId) {
                $q->where('master_gudang_id', $gudangId)
                  ->orWhereNull('master_gudang_id');
            });
        }

        $batches = $query->orderBy('tanggal_kadaluarsa', 'asc')->get();

        return response()->json([
            'success' => true,
            'batches' => $batches
        ]);
    }

    /**
     * Store a newly created Mutasi Barang.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_mutasi'             => 'required|unique:mutasi_barangs,nomor_mutasi',
            'tanggal_mutasi'           => 'required|date',
            'gudang_asal_id'           => 'required|exists:master_gudangs,id',
            'gudang_tujuan_id'         => 'required|different:gudang_asal_id|exists:master_gudangs,id',
            'items'                    => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'           => 'required|numeric|min:1'
        ], [
            'nomor_mutasi.unique'      => 'Nomor mutasi ini sudah digunakan.',
            'gudang_asal_id.required'  => 'Gudang asal wajib dipilih.',
            'gudang_tujuan_id.required'=> 'Gudang tujuan wajib dipilih.',
            'gudang_tujuan_id.different'=> 'Gudang tujuan harus berbeda dengan gudang asal.',
            'items.required'           => 'Minimal harus ada 1 item barang yang dimutasi.'
        ]);

        DB::beginTransaction();
        try {
            $gudangAsal = MasterGudang::findOrFail($request->gudang_asal_id);
            $gudangTujuan = MasterGudang::findOrFail($request->gudang_tujuan_id);

            $mutasi = MutasiBarang::create([
                'nomor_mutasi'     => $request->nomor_mutasi,
                'tanggal_mutasi'   => $request->tanggal_mutasi,
                'gudang_asal_id'   => $request->gudang_asal_id,
                'gudang_tujuan_id' => $request->gudang_tujuan_id,
                'unit_tujuan'      => $gudangTujuan->nama_gudang,
                'petugas'          => $request->petugas ?? (Auth::user()->name ?? 'dr. FAHMI RIZAL'),
                'status'           => 'Dikirim',
                'sudah_diterima'   => 'Belum Terima',
                'keterangan'       => $request->keterangan
            ]);

            foreach ($request->items as $item) {
                $barang = MasterBarang::lockForUpdate()->findOrFail($item['master_barang_id']);
                $jumlah = floatval($item['jumlah']);
                $kemasan = $item['kemasan'] ?? ($barang->kemasan ?? 'Box');
                $noBatch = !empty($item['nomor_batch']) ? trim($item['nomor_batch']) : null;
                $tglExp = !empty($item['tanggal_kadaluarsa']) ? $item['tanggal_kadaluarsa'] : null;

                // 1. Kurangi stok di StokGudang asal
                $stokAsal = StokGudang::firstOrNew([
                    'master_gudang_id' => $request->gudang_asal_id,
                    'master_barang_id' => $barang->id
                ]);
                $stokAsal->stok = max(0, ($stokAsal->exists ? $stokAsal->stok : 0) - $jumlah);
                $stokAsal->save();

                // 2. Simpan Detail Mutasi
                MutasiBarangDetail::create([
                    'mutasi_barang_id'   => $mutasi->id,
                    'master_barang_id'   => $barang->id,
                    'kemasan'            => $kemasan,
                    'jumlah'             => $jumlah,
                    'nomor_batch'        => $noBatch,
                    'tanggal_kadaluarsa' => $tglExp,
                    'keterangan'         => $item['keterangan'] ?? null
                ]);

                // 3. Log History Stok Keluar dari Gudang Asal
                HistoryStok::create([
                    'master_barang_id'  => $barang->id,
                    'master_gudang_id'  => $request->gudang_asal_id,
                    'jenis_penyesuaian' => 'Mutasi Keluar ke ' . $gudangTujuan->nama_gudang,
                    'tipe_penyesuaian'  => 'Keluar',
                    'tanggal'           => $request->tanggal_mutasi,
                    'jumlah'            => $jumlah,
                    'stok_sebelum'      => ($stokAsal->stok + $jumlah),
                    'stok_sesudah'      => $stokAsal->stok,
                    'keterangan'        => 'Mutasi Keluar No: ' . $mutasi->nomor_mutasi . ' ke ' . $gudangTujuan->nama_gudang,
                    'status'            => 'Berhasil'
                ]);
            }

            DB::commit();

            $redirectUrl = ($request->get('from') === 'penerimaan') ? url('/penerimaanmutasibarang/index') : url('/mutasibarang/index');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Mutasi Barang (' . $mutasi->nomor_mutasi . ') berhasil dibuat.',
                    'id'       => $mutasi->id,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl)->with('success', 'Mutasi Barang (' . $mutasi->nomor_mutasi . ') berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses mutasi: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal memproses mutasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show detail of Mutasi Barang.
     */
    public function show($id)
    {
        $mutasi = MutasiBarang::with([
            'gudangAsal',
            'gudangTujuan',
            'details.masterBarang'
        ])->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $mutasi
            ]);
        }

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.mutasi_barang.show', compact('mutasi', 'profil'));
    }

    /**
     * Delete Mutasi Barang.
     */
    public function destroy($id)
    {
        $mutasi = MutasiBarang::with('details')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Revert stok gudang asal
            if ($mutasi->sudah_diterima !== 'Sudah Terima') {
                foreach ($mutasi->details as $d) {
                    if ($mutasi->gudang_asal_id) {
                        $stokAsal = StokGudang::firstOrNew([
                            'master_gudang_id' => $mutasi->gudang_asal_id,
                            'master_barang_id' => $d->master_barang_id
                        ]);
                        $stokAsal->stok = ($stokAsal->exists ? $stokAsal->stok : 0) + $d->jumlah;
                        $stokAsal->save();
                    }
                }
            }

            $mutasi->details()->delete();
            $mutasi->delete();

            DB::commit();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Mutasi Barang berhasil dibatalkan dan dihapus.'
                ]);
            }

            return redirect('/mutasibarang/index')->with('success', 'Data Mutasi Barang berhasil dibatalkan dan dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus mutasi: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal menghapus mutasi: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Bukti Mutasi Barang / Surat Jalan (PDF View).
     */
    public function cetakSingle($id)
    {
        $mutasi = MutasiBarang::with([
            'gudangAsal',
            'gudangTujuan',
            'details.masterBarang'
        ])->findOrFail($id);

        $profil = DB::table('profil_faskes')->first();
        return view('farmasi.mutasi_barang.cetak_single', compact('mutasi', 'profil'));
    }

    // === TRANSAKSI PENERIMAAN MUTASI (PROFASKES 1:1) ===
    public function indexPenerimaan(Request $request)
    {
        $query = MutasiBarang::with(['gudangAsal', 'gudangTujuan', 'details.masterBarang'])
            ->orderBy('id', 'desc');

        // Filter: No Mutasi
        if ($request->filled('no_mutasi')) {
            $query->where('nomor_mutasi', 'like', '%' . $request->no_mutasi . '%');
        }

        // Filter: Tgl Awal & Tgl Akhir
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_mutasi', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_mutasi', '<=', $request->tgl_akhir);
        }

        // Filter: Gudang Asal
        if ($request->filled('gudang_asal_id')) {
            $query->where('gudang_asal_id', $request->gudang_asal_id);
        }

        // Filter: Gudang Tujuan
        if ($request->filled('gudang_tujuan_id')) {
            $query->where('gudang_tujuan_id', $request->gudang_tujuan_id);
        }

        // Filter: Sudah Diterima? (Default 'Belum Terima' as shown in PROFASKES screenshot)
        $sudahDiterima = $request->has('sudah_diterima') ? $request->sudah_diterima : 'Belum Terima';
        if ($sudahDiterima && $sudahDiterima !== 'Semua') {
            $query->where('sudah_diterima', $sudahDiterima);
        }

        $data = $query->paginate(20)->withQueryString();
        $gudangs = MasterGudang::where('is_active', true)->orderBy('nama_gudang')->get();

        return view('farmasi.mutasi_barang.penerimaan', compact('data', 'gudangs', 'sudahDiterima'));
    }

    public function terimaMutasi(Request $request, $id)
    {
        $request->validate([
            'penerima'       => 'required|string|max:100',
            'tanggal_terima' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $mutasi = MutasiBarang::with(['details.masterBarang', 'gudangAsal', 'gudangTujuan'])->lockForUpdate()->findOrFail($id);
            
            if ($mutasi->sudah_diterima === 'Sudah Terima' || $mutasi->status === 'Diterima') {
                return response()->json(['success' => false, 'message' => 'Mutasi ini sudah berstatus Diterima.'], 422);
            }

            // Tambahkan stok ke Gudang Tujuan
            foreach ($mutasi->details as $d) {
                if ($mutasi->gudang_tujuan_id) {
                    $stokTujuan = StokGudang::firstOrNew([
                        'master_gudang_id' => $mutasi->gudang_tujuan_id,
                        'master_barang_id' => $d->master_barang_id
                    ]);
                    $stokSebelum = $stokTujuan->exists ? $stokTujuan->stok : 0;
                    $stokTujuan->stok = $stokSebelum + $d->jumlah;
                    $stokTujuan->save();

                    // Update or create BatchBarang for gudang tujuan if batch exists
                    if (!empty($d->nomor_batch)) {
                        $batch = BatchBarang::firstOrNew([
                            'master_barang_id' => $d->master_barang_id,
                            'nomor_batch'      => $d->nomor_batch,
                            'master_gudang_id' => $mutasi->gudang_tujuan_id
                        ]);
                        $batch->tanggal_kadaluarsa = $d->tanggal_kadaluarsa;
                        $batch->stok = ($batch->exists ? $batch->stok : 0) + $d->jumlah;
                        $batch->is_active = true;
                        $batch->save();
                    }

                    // Log History Penerimaan Mutasi
                    HistoryStok::create([
                        'master_barang_id'  => $d->master_barang_id,
                        'master_gudang_id'  => $mutasi->gudang_tujuan_id,
                        'jenis_penyesuaian' => 'Penerimaan Mutasi dari ' . ($mutasi->gudangAsal->nama_gudang ?? 'Gudang Asal'),
                        'tipe_penyesuaian'  => 'Masuk',
                        'tanggal'           => $request->tanggal_terima,
                        'jumlah'            => $d->jumlah,
                        'stok_sebelum'      => $stokSebelum,
                        'stok_sesudah'      => $stokTujuan->stok,
                        'keterangan'        => 'Penerimaan Mutasi No: ' . $mutasi->nomor_mutasi . ' diterima oleh ' . $request->penerima,
                        'status'            => 'Berhasil'
                    ]);
                }
            }

            $mutasi->update([
                'status'         => 'Diterima',
                'sudah_diterima' => 'Sudah Terima',
                'penerima'       => $request->penerima,
                'tanggal_terima' => $request->tanggal_terima
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Penerimaan Mutasi Barang (' . $mutasi->nomor_mutasi . ') berhasil dikonfirmasi dan stok gudang tujuan telah ditambahkan.'
                ]);
            }

            return redirect('/penerimaanmutasibarang/index')->with('success', 'Penerimaan Mutasi Barang (' . $mutasi->nomor_mutasi . ') berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
