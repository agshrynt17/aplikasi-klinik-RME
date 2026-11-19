<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiKasir;
use App\Models\TransaksiTindakan;
use App\Models\AkunPembayaran;
use App\Models\Resep;
use App\Models\ResepDetail;
use App\Models\RawatInapTransfer;
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
        
        // Ambil rincian resep obat (farmasi)
        $reseps = Resep::with(['details.masterBarang'])->where('pendaftaran_id', $pendaftaran_id)->get();

        // Ambil rincian rawat inap (kamar & bed) jika ada
        $inapTransfers = RawatInapTransfer::with('kamarRanap')->where('pendaftaran_id', $pendaftaran_id)->get();

        // Hitung Split-Bill (BPJS vs Mandiri Pasien)
        $split = $this->calculateSplitBill($pendaftaran, $tindakans, $reseps, $inapTransfers);

        $total_tindakan = $split['total_tindakan'];
        $total_obat     = $split['total_obat'];
        $total_kamar    = $split['total_kamar'];
        $total_semua    = $split['total_bruto'];

        // Cek apakah sudah dibayar
        $kasir = TransaksiKasir::with(['akunPembayaran', 'kasir'])->where('pendaftaran_id', $pendaftaran_id)->first();

        // Master Akun Pembayaran
        $akun_pembayarans = AkunPembayaran::where('is_active', true)->get();

        return view('front_office.transaksi_kasir_detail', compact(
            'pendaftaran', 'tindakans', 'total_tindakan', 'reseps', 'total_obat', 
            'inapTransfers', 'total_kamar', 'total_semua', 'kasir', 'akun_pembayarans', 'split'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id'    => 'required|exists:pendaftarans,id',
            'akun_pembayaran_id'=> 'nullable|exists:akun_pembayarans,id',
            'uang_diterima'     => 'nullable|numeric|min:0',
            'diskon'            => 'nullable|numeric|min:0',
        ]);

        // Cek apakah sudah lunas sebelumnya
        $exists = TransaksiKasir::where('pendaftaran_id', $request->pendaftaran_id)->first();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Tagihan ini sudah lunas sebelumnya!'], 422);
        }

        $pendaftaran = Pendaftaran::with('penjamin')->findOrFail($request->pendaftaran_id);
        $tindakans = TransaksiTindakan::with('tindakan')->where('pendaftaran_id', $request->pendaftaran_id)->get();
        $reseps = Resep::with(['details.masterBarang'])->where('pendaftaran_id', $request->pendaftaran_id)->get();
        $inapTransfers = RawatInapTransfer::with('kamarRanap')->where('pendaftaran_id', $request->pendaftaran_id)->get();

        // Hitung Split-Bill
        $split = $this->calculateSplitBill($pendaftaran, $tindakans, $reseps, $inapTransfers);

        $diskon = (float) ($request->diskon ?? 0);
        $total_tagihan_pasien = max(0, $split['total_iur_pasien'] - $diskon);

        $akunId = $request->akun_pembayaran_id;
        if (!$akunId) {
            if ($split['is_bpjs'] && $total_tagihan_pasien <= 0) {
                $akunBpjs = AkunPembayaran::where('kode_akun', 'BPJS')
                    ->orWhere('nama_akun', 'like', '%BPJS%')
                    ->first();
                $akunId = $akunBpjs ? $akunBpjs->id : (AkunPembayaran::first()->id ?? 1);
            } else {
                $akunCash = AkunPembayaran::where('kode_akun', 'CASH')
                    ->orWhere('nama_akun', 'like', '%Tunai%')
                    ->first();
                $akunId = $akunCash ? $akunCash->id : (AkunPembayaran::first()->id ?? 1);
            }
        }

        $uang_diterima = (float) ($request->uang_diterima ?? 0);
        if ($total_tagihan_pasien > 0 && $uang_diterima < $total_tagihan_pasien) {
            return response()->json([
                'success' => false,
                'errors'  => ['uang_diterima' => ['Uang yang diterima kurang dari total iur bayar pasien (Rp ' . number_format($total_tagihan_pasien, 0, ',', '.') . ')!']]
            ], 422);
        }

        $kembalian = $total_tagihan_pasien > 0 ? max(0, $uang_diterima - $total_tagihan_pasien) : 0;

        // Generate No Kwitansi
        $tglFormat = date('Ymd');
        $prefix = "INV-" . $tglFormat . "-";
        
        $lastData = TransaksiKasir::where('no_kwitansi', 'like', $prefix . '%')
                                  ->orderBy('no_kwitansi', 'desc')
                                  ->first();
                                  
        $lastNum = $lastData ? intval(substr($lastData->no_kwitansi, -3)) : 0;
        $no_kwitansi = $prefix . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);

        $caraBayar = 'Umum / Mandiri';
        if ($split['is_bpjs']) {
            $caraBayar = $total_tagihan_pasien <= 0 ? 'Klaim BPJS 100%' : 'Split-Bill BPJS & Iur Mandiri';
        }

        $kasir = TransaksiKasir::create([
            'pendaftaran_id'    => $request->pendaftaran_id,
            'no_kwitansi'       => $no_kwitansi,
            'waktu_bayar'       => Carbon::now(),
            'biaya_adm'         => $split['is_bpjs'] ? 0 : 10000,
            'total_tindakan'    => $split['total_tindakan'] + $split['total_kamar'],
            'total_obat'        => $split['total_obat'],
            'sub_total'         => $split['total_bruto'],
            'dijamin'           => $split['total_dijamin'],
            'dibayar_pasien'    => $total_tagihan_pasien,
            'diskon'            => $diskon,
            'total_tagihan'     => $total_tagihan_pasien,
            'uang_diterima'     => $uang_diterima,
            'kembalian'         => $kembalian,
            'akun_pembayaran_id'=> $akunId,
            'cara_bayar'        => $caraBayar,
            'status_pembayaran' => 'Lunas',
            'kasir_id'          => Auth::id() ?? 1,
        ]);

        // Update status pendaftaran menjadi Selesai setelah lunas
        Pendaftaran::where('id', $request->pendaftaran_id)->update(['status_periksa' => 'Selesai']);

        // Jika pasien rawat inap menempati bed, otomatis kosongkan bed saat pembayaran selesai
        \App\Models\KamarRanap::where('pendaftaran_id', $request->pendaftaran_id)->update([
            'status'         => 'Tersedia',
            'pendaftaran_id' => null,
            'waktu_masuk'    => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran ' . ($split['is_bpjs'] ? 'Split-Bill BPJS ' : '') . 'berhasil diproses!',
            'data'    => $kasir,
        ]);
    }

    public function cetakKwitansi($id)
    {
        $kasir = TransaksiKasir::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.dokter', 'pendaftaran.penjamin', 'akunPembayaran', 'kasir'])->findOrFail($id);
        $tindakans = TransaksiTindakan::with('tindakan')->where('pendaftaran_id', $kasir->pendaftaran_id)->get();
        $reseps = Resep::with(['details.masterBarang'])->where('pendaftaran_id', $kasir->pendaftaran_id)->get();
        $inapTransfers = RawatInapTransfer::with('kamarRanap')->where('pendaftaran_id', $kasir->pendaftaran_id)->get();

        $split = $this->calculateSplitBill($kasir->pendaftaran, $tindakans, $reseps, $inapTransfers);

        return view('front_office.transaksi_kasir_kwitansi', compact('kasir', 'tindakans', 'reseps', 'inapTransfers', 'split'));
    }

    public function destroy($id)
    {
        $kasir = TransaksiKasir::findOrFail($id);
        $pendaftaranId = $kasir->pendaftaran_id;
        $kasir->delete();

        // Kembalikan status pendaftaran ke proses pelayanan
        Pendaftaran::where('id', $pendaftaranId)->update(['status_periksa' => 'Sedang Diperiksa']);

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan.']);
    }

    /**
     * Engine Split-Bill BPJS vs Iur Mandiri Pasien
     */
    private function calculateSplitBill($pendaftaran, $tindakans, $reseps, $inapTransfers)
    {
        $penjaminNama  = $pendaftaran->penjamin->nama_penjamin ?? '';
        $penjaminJenis = $pendaftaran->penjamin->jenis_penjamin ?? '';
        $isBpjs = (stripos($penjaminNama, 'BPJS') !== false || stripos($penjaminJenis, 'BPJS') !== false);

        $total_tindakan = 0;
        $total_tindakan_dijamin = 0;
        $total_tindakan_iur = 0;

        foreach ($tindakans as $t) {
            $sub = (float) $t->subtotal;
            $total_tindakan += $sub;
            if ($isBpjs) {
                $t->is_dijamin = true;
                $t->nominal_dijamin = $sub;
                $t->nominal_iur = 0;
                $total_tindakan_dijamin += $sub;
            } else {
                $t->is_dijamin = false;
                $t->nominal_dijamin = 0;
                $t->nominal_iur = $sub;
                $total_tindakan_iur += $sub;
            }
        }

        $total_obat = 0;
        $total_obat_dijamin = 0;
        $total_obat_iur = 0;

        foreach ($reseps as $r) {
            if ($r->details && $r->details->count() > 0) {
                foreach ($r->details as $d) {
                    $sub = (float) $d->subtotal;
                    $total_obat += $sub;
                    $isFornas = (bool) ($d->masterBarang && $d->masterBarang->is_formularium);
                    $d->is_fornas = $isFornas;

                    if ($isBpjs) {
                        if ($isFornas) {
                            $d->is_dijamin = true;
                            $d->nominal_dijamin = $sub;
                            $d->nominal_iur = 0;
                            $total_obat_dijamin += $sub;
                        } else {
                            $d->is_dijamin = false;
                            $d->nominal_dijamin = 0;
                            $d->nominal_iur = $sub;
                            $total_obat_iur += $sub;
                        }
                    } else {
                        $d->is_dijamin = false;
                        $d->nominal_dijamin = 0;
                        $d->nominal_iur = $sub;
                        $total_obat_iur += $sub;
                    }
                }
            } elseif ($r->total_harga > 0) {
                $sub = (float) $r->total_harga;
                $total_obat += $sub;
                if ($isBpjs) {
                    $r->is_dijamin = true;
                    $total_obat_dijamin += $sub;
                } else {
                    $r->is_dijamin = false;
                    $total_obat_iur += $sub;
                }
            }
        }

        $total_kamar = 0;
        $total_kamar_dijamin = 0;
        $total_kamar_iur = 0;

        if ($inapTransfers) {
            foreach ($inapTransfers as $it) {
                $sub = (float) $it->total_biaya_kamar;
                $total_kamar += $sub;
                $kelas = strtoupper(trim($it->kamarRanap->kelas ?? 'KELAS 3'));

                if ($isBpjs) {
                    // Jika VIP, dikenakan iur selisih kelas kamar di atas hak BPJS (Rp 200.000/hari)
                    if (str_contains($kelas, 'VIP')) {
                        $plafonPerHari = 200000;
                        $tarifHari = (float) $it->tarif_per_hari;
                        $selisihPerHari = max(0, $tarifHari - $plafonPerHari);
                        $iur = $selisihPerHari * (int) $it->lama_hari;
                        $dijamin = max(0, $sub - $iur);

                        $it->is_dijamin = false;
                        $it->nominal_dijamin = $dijamin;
                        $it->nominal_iur = $iur;
                        $total_kamar_dijamin += $dijamin;
                        $total_kamar_iur += $iur;
                    } else {
                        $it->is_dijamin = true;
                        $it->nominal_dijamin = $sub;
                        $it->nominal_iur = 0;
                        $total_kamar_dijamin += $sub;
                    }
                } else {
                    $it->is_dijamin = false;
                    $it->nominal_dijamin = 0;
                    $it->nominal_iur = $sub;
                    $total_kamar_iur += $sub;
                }
            }
        }

        $total_bruto = $total_tindakan + $total_obat + $total_kamar;
        $total_dijamin = $total_tindakan_dijamin + $total_obat_dijamin + $total_kamar_dijamin;
        $total_iur_pasien = $total_tindakan_iur + $total_obat_iur + $total_kamar_iur;

        return [
            'is_bpjs'                => $isBpjs,
            'total_tindakan'         => $total_tindakan,
            'total_tindakan_dijamin' => $total_tindakan_dijamin,
            'total_tindakan_iur'     => $total_tindakan_iur,
            'total_obat'             => $total_obat,
            'total_obat_dijamin'     => $total_obat_dijamin,
            'total_obat_iur'         => $total_obat_iur,
            'total_kamar'            => $total_kamar,
            'total_kamar_dijamin'    => $total_kamar_dijamin,
            'total_kamar_iur'        => $total_kamar_iur,
            'total_bruto'            => $total_bruto,
            'total_dijamin'          => $total_dijamin,
            'total_iur_pasien'       => $total_iur_pasien,
        ];
    }
}
