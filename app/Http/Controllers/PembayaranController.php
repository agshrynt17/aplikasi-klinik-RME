<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKasir;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $q = trim($request->q ?? '');
        $poliklinikId = $request->poliklinik_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';

        $query = TransaksiKasir::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.penjamin'])
            ->whereHas('pendaftaran', function($qp) use ($tanggal, $q, $poliklinikId, $penjaminId) {
                if ($tanggal) {
                    $qp->whereDate('tanggal_daftar', $tanggal);
                }
                if ($q) {
                    $qp->where(function($sub) use ($q) {
                        $sub->where('nomor_registrasi', 'like', "%{$q}%")
                            ->orWhere('no_rawat', 'like', "%{$q}%")
                            ->orWhereHas('pasien', function($qpas) use ($q) {
                                $qpas->where('nama_pasien', 'like', "%{$q}%")
                                     ->orWhere('no_rm', 'like', "%{$q}%");
                            });
                    });
                }
                if ($poliklinikId) {
                    $qp->where('poliklinik_id', $poliklinikId);
                }
                if ($penjaminId) {
                    $qp->where('penjamin_id', $penjaminId);
                }
            })
            ->where('status_pembayaran', 'Lunas')
            ->orderBy('waktu_bayar', 'asc')
            ->orderBy('id', 'asc');

        $data = $query->paginate(20)->withQueryString();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();

        return view('pembayaran.index', compact('data', 'tanggal', 'q', 'poliklinikId', 'penjaminId', 'polikliniks', 'penjamins'));
    }

    public function cetakPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $q = trim($request->q ?? '');
        $poliklinikId = $request->poliklinik_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';

        $query = TransaksiKasir::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.penjamin'])
            ->whereHas('pendaftaran', function($qp) use ($tanggal, $q, $poliklinikId, $penjaminId) {
                if ($tanggal) {
                    $qp->whereDate('tanggal_daftar', $tanggal);
                }
                if ($q) {
                    $qp->where(function($sub) use ($q) {
                        $sub->where('nomor_registrasi', 'like', "%{$q}%")
                            ->orWhere('no_rawat', 'like', "%{$q}%")
                            ->orWhereHas('pasien', function($qpas) use ($q) {
                                $qpas->where('nama_pasien', 'like', "%{$q}%")
                                     ->orWhere('no_rm', 'like', "%{$q}%");
                            });
                    });
                }
                if ($poliklinikId) {
                    $qp->where('poliklinik_id', $poliklinikId);
                }
                if ($penjaminId) {
                    $qp->where('penjamin_id', $penjaminId);
                }
            })
            ->where('status_pembayaran', 'Lunas')
            ->orderBy('waktu_bayar', 'asc')
            ->orderBy('id', 'asc');

        $items = $query->get();

        return view('pembayaran.cetak_pdf', compact('items', 'tanggal'));
    }

    public function cetakExcel(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $filename = 'Data_Pembayaran_Pasien_' . $tanggal . '.csv';

        $query = TransaksiKasir::with(['pendaftaran.pasien', 'pendaftaran.poliklinik', 'pendaftaran.penjamin'])
            ->whereHas('pendaftaran', function($qp) use ($tanggal) {
                $qp->whereDate('tanggal_daftar', $tanggal);
            })
            ->where('status_pembayaran', 'Lunas')
            ->orderBy('waktu_bayar', 'asc')
            ->orderBy('id', 'asc');

        $items = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($items, $tanggal) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No', 'No Registrasi', 'Tgl Registrasi', 'Kode RM', 'Nama Pasien',
                'Penjamin', 'Nama Poliklinik', 'Status Pembayaran', 'Metode Bayar',
                'Tagihan', 'Tanggungan Penjamin', 'Non Penjamin', 'Jumlah Bayar', 'Tgl Bayar'
            ]);

            foreach ($items as $idx => $tk) {
                $p = $tk->pendaftaran;
                $tglReg = $p->tanggal_daftar ? Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';
                $tglBayar = $tk->waktu_bayar ? Carbon::parse($tk->waktu_bayar)->format('d-m-Y') : $tglReg;

                $tagihan = $tk->total_tagihan ?: 0;
                $tanggungan = $tk->dijamin ?: $tagihan;
                $nonPenjamin = $tk->dibayar_pasien ?: 0;
                $jmlBayar = $tk->uang_diterima ?: $tagihan;

                $statusPenjamin = (stripos($p->penjamin->nama_penjamin ?? '', 'BPJS') !== false)
                    ? 'Ditanggung Penjamin Penuh'
                    : 'Pribadi / Umum';

                fputcsv($handle, [
                    $idx + 1,
                    $p->nomor_registrasi,
                    $tglReg,
                    $p->pasien->no_rm ?? '-',
                    $p->pasien->nama_pasien ?? '-',
                    $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan',
                    $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1'),
                    $statusPenjamin,
                    'Metode Bayar: ' . ($tk->cara_bayar ?: 'Cash'),
                    number_format($tagihan, 0, ',', '.'),
                    number_format($tanggungan, 0, ',', '.'),
                    number_format($nonPenjamin, 0, ',', '.'),
                    number_format($jmlBayar, 0, ',', '.'),
                    $tglBayar,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Request $request, $id)
    {
        $tk = TransaksiKasir::find($id);
        if ($tk) {
            $tk->status_pembayaran = 'Belum Lunas';
            $tk->cara_bayar = 'Belum Bayar';
            $tk->save();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan']);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }
}

