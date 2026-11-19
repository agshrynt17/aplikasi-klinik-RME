<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiTindakan;
use App\Models\Tindakan;
use App\Models\TarifTindakan;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Database\Seeders\PendaftaranKonhakesSeeder;
use Illuminate\Http\Request;

class TransaksiTindakanController extends Controller
{
    // 1. Menampilkan daftar kunjungan (pasien) yang bisa diinput tindakan
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'transaksiTindakans'])
                    ->where('status_periksa', '!=', 'Batal');

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }

        // Filter Pencarian (No Reg, No Rawat, No RM, Nama Pasien)
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('no_rawat', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function($qp) use ($search) {
                      $qp->where('nama_pasien', 'like', "%{$search}%")
                         ->orWhere('no_rm', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Poliklinik
        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }

        // Filter Penjamin
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        // Filter Status Tindakan
        if ($request->filled('status_tindakan')) {
            if ($request->status_tindakan === 'ada') {
                $query->whereHas('transaksiTindakans');
            } elseif ($request->status_tindakan === 'belum') {
                $query->whereDoesntHave('transaksiTindakans');
            }
        }

        $data = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $total = $data->total();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tanggal     = $request->tanggal;

        return view('front_office.transaksi_tindakan_index', compact('data', 'total', 'polikliniks', 'penjamins', 'tanggal'));
    }

    // Export Data to CSV
    public function export(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'transaksiTindakans'])
                    ->where('status_periksa', '!=', 'Batal');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('no_rawat', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function($qp) use ($search) {
                      $qp->where('nama_pasien', 'like', "%{$search}%")
                         ->orWhere('no_rm', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        $records = $query->orderBy('id', 'desc')->get();

        $filename = 'transaksi_tindakan_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($records) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'No', 'Status Tindakan', 'No. Registrasi', 'Tanggal', 'Status Periksa',
                'No. Rekam Medis', 'Jenis Kunjungan', 'Nama Pasien', 'Jenis Kelamin',
                'Tgl Lahir', 'Penjamin', 'Poliklinik', 'Dokter', 'Perawat'
            ]);

            foreach ($records as $idx => $r) {
                $hasTindakan = $r->transaksiTindakans && $r->transaksiTindakans->isNotEmpty();
                fputcsv($file, [
                    $idx + 1,
                    $hasTindakan ? 'Ada tindakan' : 'Belum ada tindakan',
                    $r->nomor_registrasi ?: $r->no_rawat,
                    $r->tanggal_daftar ? $r->tanggal_daftar->format('Y-m-d') : '',
                    $r->status_periksa,
                    $r->pasien->no_rm ?? '-',
                    $r->jenis_kunjungan ?? 'Kunjungan Sakit',
                    $r->pasien->nama_pasien ?? '-',
                    ($r->pasien->jenis_kelamin ?? 'L') == 'P' ? 'Perempuan' : 'Laki-Laki',
                    $r->pasien && $r->pasien->tanggal_lahir ? $r->pasien->tanggal_lahir->format('Y-m-d') : '-',
                    $r->penjamin->nama_penjamin ?? 'Pribadi',
                    $r->poliklinik->nama_poli ?? '-',
                    $r->dokter->nama_pegawai ?? '-',
                    $r->perawat->nama_pegawai ?? ($r->nama_perawat ?: '-')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 2. Menampilkan detail/form input tindakan untuk spesifik kunjungan
    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        // Data tindakan yang sudah diinput
        $tindakans = TransaksiTindakan::with(['tindakan', 'pelaksana'])
                        ->where('pendaftaran_id', $pendaftaran_id)
                        ->latest()
                        ->get();

        // Master data untuk dropdown
        $master_tindakan = Tindakan::where('is_active', true)->get();
        $master_pegawai  = Pegawai::where('is_active', true)->get();

        return view('front_office.transaksi_tindakan_detail', compact('pendaftaran', 'tindakans', 'master_tindakan', 'master_pegawai'));
    }

    // 3. API: Get Tarif berdasarkan Tindakan dan Penjamin Pasien
    public function getTarif(Request $request)
    {
        $tindakan_id = $request->tindakan_id;
        $penjamin_id = $request->penjamin_id; // Bisa null (UMUM)

        // Cek tarif khusus asuransi/penjamin ini
        $tarif = TarifTindakan::where('tindakan_id', $tindakan_id)
                    ->where('penjamin_id', $penjamin_id)
                    ->first();
                    
        if (!$tarif) {
            // Jika tidak ada tarif khusus asuransi, cari tarif UMUM (penjamin_id = null)
            $tarif = TarifTindakan::where('tindakan_id', $tindakan_id)
                        ->whereNull('penjamin_id')
                        ->first();
        }

        return response()->json([
            'harga' => $tarif ? $tarif->harga : 0
        ]);
    }

    // 4. Menyimpan tindakan baru ke keranjang transaksi
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'tindakan_id'    => 'required|exists:tindakans,id',
            'pelaksana_id'   => 'nullable|exists:pegawais,id',
            'tarif'          => 'required|numeric|min:0',
            'qty'            => 'required|integer|min:1',
        ]);

        $item = TransaksiTindakan::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'tindakan_id'    => $request->tindakan_id,
            'pelaksana_id'   => $request->pelaksana_id,
            'tarif'          => $request->tarif,
            'qty'            => $request->qty,
            'subtotal'       => $request->tarif * $request->qty,
        ]);

        $item->load(['tindakan', 'pelaksana']);

        return response()->json([
            'success' => true,
            'message' => 'Tindakan berhasil ditambahkan.',
            'data'    => $item,
        ]);
    }

    // 5. Menghapus tindakan
    public function destroy($id)
    {
        TransaksiTindakan::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Tindakan berhasil dihapus.']);
    }
}
