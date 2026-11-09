<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\ResepDetail;
use App\Models\Pasien;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use App\Models\Pendaftaran;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\StokGudang;
use App\Models\HistoryStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResepController extends Controller
{
    /**
     * Display a listing of Transaksi Resep (PROFASKES 1:1).
     */
    public function index(Request $request)
    {
        $query = Resep::with([
            'pasien',
            'dokter',
            'pendaftaran.poliklinik',
            'pendaftaran.dokter',
            'pendaftaran.perawat',
            'pendaftaran.penjamin',
            'details.masterBarang'
        ])
        ->where('is_bhp', false)
        ->orderBy('id', 'desc');

        // Filter: Tanggal Awal & Akhir
        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_resep', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_resep', '<=', $request->tgl_akhir);
        }

        // Filter: Keyword (No Resep, No RM, Nama Pasien, No Registrasi)
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nomor_resep', 'like', "%{$kw}%")
                  ->orWhere('antrian_nomor', 'like', "%{$kw}%")
                  ->orWhereHas('pasien', function($qp) use ($kw) {
                      $qp->where('nama_pasien', 'like', "%{$kw}%")
                         ->orWhere('no_rm', 'like', "%{$kw}%");
                  })
                  ->orWhereHas('pendaftaran', function($qreg) use ($kw) {
                      $qreg->where('nomor_registrasi', 'like', "%{$kw}%");
                  });
            });
        }

        // Filter: Status Proses
        if ($request->filled('status_proses') && $request->status_proses !== 'Semua') {
            $query->where('status_proses', $request->status_proses);
        }

        // Filter: Status Penyerahan
        if ($request->filled('status_penyerahan') && $request->status_penyerahan !== 'Semua') {
            $query->where('status_penyerahan', $request->status_penyerahan);
        }

        // Filter: Poliklinik
        if ($request->filled('poliklinik_id')) {
            $query->whereHas('pendaftaran', function($q) use ($request) {
                $q->where('poliklinik_id', $request->poliklinik_id);
            });
        }

        // Filter: Dokter
        if ($request->filled('dokter_id')) {
            $query->where('pegawai_id', $request->dokter_id);
        }

        $data = $query->paginate(30)->withQueryString();

        // Count of unserved prescriptions for notification banner
        $resepBaruCount = Resep::where('status_proses', 'Belum Diproses')
            ->where('is_bhp', false)
            ->count();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $dokters = Pegawai::where('nama_pegawai', 'like', 'dr.%')->orderBy('nama_pegawai')->get();

        return view('farmasi.resep.index', compact('data', 'resepBaruCount', 'polikliniks', 'dokters'));
    }

    /**
     * Display Transaksi Bahan Habis Pakai (BHP) (PROFASKES 1:1).
     */
    public function indexBhp(Request $request)
    {
        $query = Resep::with([
            'pasien',
            'dokter',
            'pendaftaran.poliklinik',
            'pendaftaran.penjamin',
            'details.masterBarang'
        ])
        ->where('is_bhp', true)
        ->orderBy('id', 'desc');

        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_resep', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_resep', '<=', $request->tgl_akhir);
        }
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nomor_resep', 'like', "%{$kw}%")
                  ->orWhereHas('pasien', function($qp) use ($kw) {
                      $qp->where('nama_pasien', 'like', "%{$kw}%")
                         ->orWhere('no_rm', 'like', "%{$kw}%");
                  });
            });
        }

        $data = $query->paginate(30)->withQueryString();

        return view('farmasi.resep.bhp', compact('data'));
    }

    /**
     * Show detail of Resep (for AJAX modal).
     */
    public function show($id)
    {
        $resep = Resep::with([
            'pasien',
            'dokter',
            'pendaftaran.poliklinik',
            'pendaftaran.dokter',
            'pendaftaran.perawat',
            'pendaftaran.penjamin',
            'details.masterBarang'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $resep
        ]);
    }

    /**
     * Process / Racik Resep.
     */
    public function prosesResep(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $resep = Resep::with('details.masterBarang')->lockForUpdate()->findOrFail($id);

            // Gudang Farmasi
            $gudangFarmasi = MasterGudang::where('nama_gudang', 'Gudang Farmasi')->first()
                          ?? MasterGudang::first();

            foreach ($resep->details as $detail) {
                $barang = MasterBarang::lockForUpdate()->find($detail->master_barang_id);
                if ($barang) {
                    $stokSebelum = $barang->stok;
                    $stokSesudah = max(0, $stokSebelum - $detail->jumlah);
                    $barang->stok = $stokSesudah;
                    $barang->save();

                    // Update Gudang Farmasi stock if exists
                    if ($gudangFarmasi) {
                        $stokG = StokGudang::firstOrNew([
                            'master_gudang_id' => $gudangFarmasi->id,
                            'master_barang_id' => $barang->id
                        ]);
                        $stokG->stok = max(0, ($stokG->exists ? $stokG->stok : 0) - $detail->jumlah);
                        $stokG->save();

                        HistoryStok::create([
                            'master_barang_id'  => $barang->id,
                            'master_gudang_id'  => $gudangFarmasi->id,
                            'jenis_penyesuaian' => 'Pelayanan Resep Pasien',
                            'tipe_penyesuaian'  => 'Keluar',
                            'tanggal'           => Carbon::now()->toDateString(),
                            'jumlah'            => $detail->jumlah,
                            'stok_sebelum'      => $stokSebelum,
                            'stok_sesudah'      => $stokSesudah,
                            'keterangan'        => 'Pelayanan Resep No: ' . $resep->nomor_resep . ' Pasien: ' . ($resep->pasien->nama_pasien ?? '-'),
                            'status'            => 'Berhasil'
                        ]);
                    }
                }
            }

            $resep->update([
                'status_proses'   => 'Sudah Diproses',
                'waktu_proses'    => Carbon::now(),
                'petugas_farmasi' => Auth::user()->name ?? 'dr. FAHMI RIZAL',
                'status'          => 'Diproses'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resep No. ' . $resep->nomor_resep . ' berhasil diproses & diracik.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses resep: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serahkan Obat ke Pasien.
     */
    public function serahkanObat(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $resep = Resep::findOrFail($id);

            // Calculate wait time
            $waktuTunggu = '0 Jam 0 Mnt';
            if ($resep->created_at) {
                $created = Carbon::parse($resep->created_at);
                $now = Carbon::now();
                $diffHours = (int) $created->diffInHours($now);
                $diffMins = (int) ($created->diffInMinutes($now) % 60);
                $waktuTunggu = "{$diffHours} Jam {$diffMins} Mnt";
            }

            $resep->update([
                'status_penyerahan' => 'Sudah Diserahkan',
                'waktu_penyerahan'  => Carbon::now(),
                'waktu_tunggu'      => $waktuTunggu,
                'penyerahan_oleh'   => Auth::user()->name ?? 'dr. FAHMI RIZAL',
                'status'            => 'Dilayani'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Obat Resep No. ' . $resep->nomor_resep . ' berhasil diserahkan kepada pasien.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat penyerahan obat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Conseling notes.
     */
    public function simpanConseling(Request $request, $id)
    {
        $request->validate(['catatan' => 'required|string']);

        $resep = Resep::findOrFail($id);
        $resep->update(['conseling_catatan' => $request->catatan]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan konseling resep berhasil disimpan.'
        ]);
    }

    /**
     * Save Pelayanan Informasi Obat (PIO).
     */
    public function simpanPio(Request $request, $id)
    {
        $request->validate(['catatan' => 'required|string']);

        $resep = Resep::findOrFail($id);
        $resep->update(['pio_catatan' => $request->catatan]);

        return response()->json([
            'success' => true,
            'message' => 'Data Entri PIO (Pelayanan Informasi Obat) berhasil disimpan.'
        ]);
    }

    /**
     * Print PDF Report.
     */
    public function cetakPdf(Request $request)
    {
        $query = Resep::with(['pasien', 'dokter', 'pendaftaran.poliklinik', 'pendaftaran.penjamin', 'details.masterBarang'])
            ->where('is_bhp', false)
            ->orderBy('id', 'desc');

        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_resep', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_resep', '<=', $request->tgl_akhir);
        }

        $data = $query->get();
        $profil = DB::table('profil_faskes')->first();

        return view('farmasi.resep.cetak_pdf', compact('data', 'profil'));
    }

    /**
     * Export XLS Report.
     */
    public function cetakXls(Request $request)
    {
        $query = Resep::with(['pasien', 'dokter', 'pendaftaran.poliklinik', 'pendaftaran.penjamin', 'details.masterBarang'])
            ->where('is_bhp', false)
            ->orderBy('id', 'desc');

        if ($request->filled('tgl_awal')) {
            $query->whereDate('tanggal_resep', '>=', $request->tgl_awal);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('tanggal_resep', '<=', $request->tgl_akhir);
        }

        $data = $query->get();

        $filename = 'Laporan_Transaksi_Resep_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, ['No', 'No Resep', 'Antrian', 'Tgl Resep', 'No Registrasi', 'Nama Pasien', 'No RM', 'Poli', 'Dokter', 'Penjamin', 'Status Proses', 'Status Penyerahan', 'Total Biaya']);

            foreach ($data as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->nomor_resep,
                    $row->antrian_nomor,
                    $row->tanggal_resep ? Carbon::parse($row->tanggal_resep)->format('d/m/Y') : '-',
                    $row->pendaftaran ? $row->pendaftaran->nomor_registrasi : '-',
                    $row->pasien ? $row->pasien->nama_pasien : '-',
                    $row->pasien ? $row->pasien->no_rm : '-',
                    $row->pendaftaran && $row->pendaftaran->poliklinik ? $row->pendaftaran->poliklinik->nama_poli : '-',
                    $row->dokter ? $row->dokter->nama_pegawai : '-',
                    $row->pendaftaran && $row->pendaftaran->penjamin ? $row->pendaftaran->penjamin->nama_penjamin : '-',
                    $row->status_proses,
                    $row->status_penyerahan,
                    $row->total_harga
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Form create manual prescription.
     */
    public function create()
    {
        $pasiens = Pasien::orderBy('nama_pasien')->take(100)->get();
        $dokters = Pegawai::where('nama_pegawai', 'like', 'dr.%')->orderBy('nama_pegawai')->get();
        $barangs = MasterBarang::where('is_active', true)->where('stok', '>', 0)->orderBy('nama_barang')->get();

        $lastResep = Resep::orderBy('id', 'desc')->first();
        $nextId = $lastResep ? $lastResep->id + 1 : 1;
        $nomorResep = 'RSP-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('farmasi.resep.create', compact('pasiens', 'dokters', 'barangs', 'nomorResep'));
    }

    /**
     * Store manual prescription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_resep'              => 'required|unique:reseps,nomor_resep',
            'tanggal_resep'            => 'required|date',
            'pasien_id'                => 'required|exists:pasiens,id',
            'pegawai_id'               => 'nullable|exists:pegawais,id',
            'items'                    => 'required|array|min:1',
            'items.*.master_barang_id' => 'required|exists:master_barangs,id',
            'items.*.jumlah'           => 'required|integer|min:1',
            'items.*.aturan_pakai'     => 'required|string|max:100'
        ]);

        try {
            DB::beginTransaction();

            $totalHarga = 0;
            $resep = Resep::create([
                'nomor_resep'       => $request->nomor_resep,
                'tanggal_resep'     => $request->tanggal_resep,
                'pasien_id'         => $request->pasien_id,
                'pegawai_id'        => $request->pegawai_id,
                'keterangan'        => $request->keterangan,
                'status'            => 'Menunggu',
                'status_proses'     => 'Belum Diproses',
                'status_penyerahan' => 'Belum Diserahkan',
                'total_harga'       => 0,
                'is_bhp'            => false
            ]);

            foreach ($request->items as $item) {
                $barang = MasterBarang::find($item['master_barang_id']);
                $hargaSatuan = $barang->harga_jual ?? 0;
                $subtotal = $hargaSatuan * $item['jumlah'];
                $totalHarga += $subtotal;

                ResepDetail::create([
                    'resep_id'         => $resep->id,
                    'master_barang_id' => $item['master_barang_id'],
                    'jumlah'           => $item['jumlah'],
                    'aturan_pakai'     => $item['aturan_pakai'],
                    'harga_satuan'     => $hargaSatuan,
                    'subtotal'         => $subtotal
                ]);
            }

            $resep->update(['total_harga' => $totalHarga]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Resep berhasil dibuat dan masuk antrean farmasi.',
                    'redirect' => url('/transaksiresep/index')
                ]);
            }

            return redirect('/transaksiresep/index')->with('success', 'Resep berhasil dibuat.');

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
