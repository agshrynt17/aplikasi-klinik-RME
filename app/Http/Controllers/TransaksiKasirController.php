<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiKasir;
use App\Models\TransaksiTindakan;
use App\Models\AkunPembayaran;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Database\Seeders\PendaftaranKonhakesSeeder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransaksiKasirController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan ada data pendaftaran jika kosong
        if (Pendaftaran::count() === 0) {
            try {
                (new PendaftaranKonhakesSeeder())->run();
            } catch (\Throwable $e) {}
        }

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'transaksiKasir', 'transaksiTindakans'])
                    ->where('status_periksa', '!=', 'Batal');

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_daftar', $request->tanggal);
        }

        // Filter Pencarian
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

        // Filter Status Bayar
        if ($request->filled('status_bayar')) {
            if ($request->status_bayar === 'Lunas') {
                $query->whereHas('transaksiKasir', function($q) {
                    $q->where('status_pembayaran', 'Lunas');
                });
            } elseif ($request->status_bayar === 'Belum Lunas') {
                $query->whereDoesntHave('transaksiKasir', function($q) {
                    $q->where('status_pembayaran', 'Lunas');
                });
            }
        }

        $data = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $total = $data->total();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins   = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $tanggal     = $request->tanggal;

        return view('front_office.transaksi_kasir_index', compact('data', 'total', 'polikliniks', 'penjamins', 'tanggal'));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'perawat', 'penjamin', 'transaksiKasir', 'transaksiTindakans'])
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

        $data = $query->orderBy('id', 'desc')->get();
        return view('front_office.transaksi_kasir_pdf', compact('data'));
    }

    public function show($pendaftaran_id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poliklinik', 'dokter', 'penjamin'])->findOrFail($pendaftaran_id);
        
        // Ambil rincian tindakan
        $tindakans = TransaksiTindakan::with('tindakan')->where('pendaftaran_id', $pendaftaran_id)->get();
        $total_tindakan = $tindakans->sum('subtotal');
        
        // Asumsi total obat = 0 (karena modul apotek belum ada)
        $total_obat = 0;

        $total_semua = $total_tindakan + $total_obat;

        // Cek apakah sudah dibayar
        $kasir = TransaksiKasir::with(['akunPembayaran', 'kasir'])->where('pendaftaran_id', $pendaftaran_id)->first();

        // Master Akun Pembayaran
        $akun_pembayarans = AkunPembayaran::where('is_active', true)->get();

        return view('front_office.transaksi_kasir_detail', compact(
            'pendaftaran', 'tindakans', 'total_tindakan', 'total_obat', 'total_semua', 'kasir', 'akun_pembayarans'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id'    => 'required|exists:pendaftarans,id',
            'akun_pembayaran_id'=> 'required|exists:akun_pembayarans,id',
            'uang_diterima'     => 'required|numeric|min:0',
            'diskon'            => 'nullable|numeric|min:0',
        ]);

        // Cek apakah sudah lunas sebelumnya
        $exists = TransaksiKasir::where('pendaftaran_id', $request->pendaftaran_id)->first();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas sebelumnya!'], 422);
        }

        // Hitung ulang total untuk keamanan (jangan percaya data input form)
        $total_tindakan = TransaksiTindakan::where('pendaftaran_id', $request->pendaftaran_id)->sum('subtotal');
        $total_obat = 0; // Obat belum ada
        
        $diskon = $request->diskon ?? 0;
        $total_tagihan = ($total_tindakan + $total_obat) - $diskon;
        if ($total_tagihan < 0) $total_tagihan = 0;

        $uang_diterima = $request->uang_diterima;
        $kembalian = $uang_diterima - $total_tagihan;

        if ($uang_diterima < $total_tagihan) {
            return response()->json([
                'success' => false,
                'errors'  => ['uang_diterima' => ['Uang yang diterima kurang dari total tagihan!']]
            ], 422);
        }

        // Generate No Kwitansi
        // Format: INV-YYYYMMDD-XXX
        $tglFormat = date('Ymd');
        $prefix = "INV-" . $tglFormat . "-";
        
        $lastData = TransaksiKasir::where('no_kwitansi', 'like', $prefix . '%')
                                  ->orderBy('no_kwitansi', 'desc')
                                  ->first();
                                  
        $lastNum = $lastData ? intval(substr($lastData->no_kwitansi, -3)) : 0;
        $no_kwitansi = $prefix . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);

        $kasir = TransaksiKasir::create([
            'pendaftaran_id'    => $request->pendaftaran_id,
            'no_kwitansi'       => $no_kwitansi,
            'waktu_bayar'       => Carbon::now(),
            'total_tindakan'    => $total_tindakan,
            'total_obat'        => $total_obat,
            'diskon'            => $diskon,
            'total_tagihan'     => $total_tagihan,
            'uang_diterima'     => $uang_diterima,
            'kembalian'         => $kembalian,
            'akun_pembayaran_id'=> $request->akun_pembayaran_id,
            'status_pembayaran' => 'Lunas',
            'kasir_id'          => Auth::id(), // ID User yang sedang login (Admin)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses!',
            'data'    => $kasir,
        ]);
    }

    public function destroy($id)
    {
        $kasir = TransaksiKasir::findOrFail($id);
        $kasir->delete();
        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan.']);
    }
}
