<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\TransaksiKasir;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanTransaksiKasirController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $q = trim($request->q ?? '');
        $poliklinikId = $request->poliklinik_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';
        $caraBayar = $request->cara_bayar ?? '';

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'penjamin', 'transaksiKasir'])
            ->whereDate('tanggal_daftar', $tanggal);

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nomor_registrasi', 'like', "%{$q}%")
                    ->orWhere('no_rawat', 'like', "%{$q}%")
                    ->orWhereHas('pasien', function($qp) use ($q) {
                        $qp->where('nama_pasien', 'like', "%{$q}%")
                           ->orWhere('no_rm', 'like', "%{$q}%")
                           ->orWhere('no_bpjs', 'like', "%{$q}%");
                    });
            });
        }

        if ($poliklinikId) {
            $query->where('poliklinik_id', $poliklinikId);
        }

        if ($penjaminId) {
            $query->where('penjamin_id', $penjaminId);
        }

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
                              ->orderBy('id', 'desc')
                              ->get();

        $rows = [];
        $no = 1;
        foreach ($pendaftarans as $p) {
            $tk = $p->transaksiKasir;

            $adm = $tk ? (float)$tk->biaya_adm : 10000;
            $tindakan = $tk ? (float)$tk->total_tindakan : 0;
            $konsultasi = $tk ? (float)$tk->biaya_konsultasi : 0;
            $farmasi = $tk ? (float)$tk->total_obat : 0;
            $lab = $tk ? (float)$tk->biaya_lab : 0;
            $radiologi = $tk ? (float)$tk->biaya_radiologi : 0;

            $subTotal = $tk && $tk->sub_total > 0 ? (float)$tk->sub_total : ($adm + $tindakan + $konsultasi + $farmasi + $lab + $radiologi);
            $disc = $tk ? (float)$tk->diskon : 0;
            $totalTagihan = $tk ? (float)$tk->total_tagihan : 0;
            $dijamin = $tk ? (float)$tk->dijamin : 0;
            $dibayarPasien = $tk ? (float)$tk->dibayar_pasien : 0;
            $caraBayarVal = $tk && $tk->cara_bayar ? $tk->cara_bayar : 'Belum Bayar';

            if ($caraBayar && $caraBayarVal !== $caraBayar) {
                continue;
            }

            $jk = '-';
            if ($p->pasien) {
                $jk = ($p->pasien->jenis_kelamin == 'P' || strtolower($p->pasien->jenis_kelamin) == 'perempuan') ? 'Perempuan' : 'Laki-Laki';
            }

            $penjaminNama = $p->penjamin->nama_penjamin ?? 'Pribadi';
            $noKartu = $p->no_bpjs_asuransi ?: ($p->pasien->no_bpjs ?: '-');
            if (stripos($penjaminNama, 'Pribadi') !== false || stripos($penjaminNama, 'Umum') !== false) {
                if (!$p->no_bpjs_asuransi) {
                    $noKartu = '-';
                }
            }

            $klinikNama = $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1');

            $tglDaftar = $p->tanggal_daftar ? Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';

            $rows[] = (object)[
                'no' => $no++,
                'tanggal' => $tglDaftar,
                'no_reg' => $p->nomor_registrasi,
                'nama' => $p->pasien->nama_pasien ?? '-',
                'no_rm' => $p->pasien->no_rm ?? '-',
                'jk' => $jk,
                'penjamin' => $penjaminNama,
                'no_kartu' => $noKartu,
                'klinik' => $klinikNama,
                'adm' => $adm,
                'tindakan' => $tindakan,
                'konsultasi' => $konsultasi,
                'farmasi' => $farmasi,
                'lab' => $lab,
                'radiologi' => $radiologi,
                'sub_total' => $subTotal,
                'disc' => $disc,
                'total_tagihan' => $totalTagihan,
                'dijamin' => $dijamin,
                'dibayar_pasien' => $dibayarPasien,
                'cara_bayar' => $caraBayarVal,
            ];
        }

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        $penjamins = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();

        return view('laporan.laporan_transaksi_kasir', compact(
            'rows', 'tanggal', 'q', 'poliklinikId', 'penjaminId', 'caraBayar',
            'polikliniks', 'penjamins'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $q = trim($request->q ?? '');
        $poliklinikId = $request->poliklinik_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';
        $caraBayar = $request->cara_bayar ?? '';

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'penjamin', 'transaksiKasir'])
            ->whereDate('tanggal_daftar', $tanggal);

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('nomor_registrasi', 'like', "%{$q}%")
                    ->orWhere('no_rawat', 'like', "%{$q}%")
                    ->orWhereHas('pasien', function($qp) use ($q) {
                        $qp->where('nama_pasien', 'like', "%{$q}%")
                           ->orWhere('no_rm', 'like', "%{$q}%")
                           ->orWhere('no_bpjs', 'like', "%{$q}%");
                    });
            });
        }

        if ($poliklinikId) {
            $query->where('poliklinik_id', $poliklinikId);
        }

        if ($penjaminId) {
            $query->where('penjamin_id', $penjaminId);
        }

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
                              ->orderBy('id', 'desc')
                              ->get();

        $rows = [];
        $no = 1;
        foreach ($pendaftarans as $p) {
            $tk = $p->transaksiKasir;

            $adm = $tk ? (float)$tk->biaya_adm : 10000;
            $tindakan = $tk ? (float)$tk->total_tindakan : 0;
            $konsultasi = $tk ? (float)$tk->biaya_konsultasi : 0;
            $farmasi = $tk ? (float)$tk->total_obat : 0;
            $lab = $tk ? (float)$tk->biaya_lab : 0;
            $radiologi = $tk ? (float)$tk->biaya_radiologi : 0;

            $subTotal = $tk && $tk->sub_total > 0 ? (float)$tk->sub_total : ($adm + $tindakan + $konsultasi + $farmasi + $lab + $radiologi);
            $disc = $tk ? (float)$tk->diskon : 0;
            $totalTagihan = $tk ? (float)$tk->total_tagihan : 0;
            $dijamin = $tk ? (float)$tk->dijamin : 0;
            $dibayarPasien = $tk ? (float)$tk->dibayar_pasien : 0;
            $caraBayarVal = $tk && $tk->cara_bayar ? $tk->cara_bayar : 'Belum Bayar';

            if ($caraBayar && $caraBayarVal !== $caraBayar) {
                continue;
            }

            $jk = '-';
            if ($p->pasien) {
                $jk = ($p->pasien->jenis_kelamin == 'P' || strtolower($p->pasien->jenis_kelamin) == 'perempuan') ? 'Perempuan' : 'Laki-Laki';
            }

            $penjaminNama = $p->penjamin->nama_penjamin ?? 'Pribadi';
            $noKartu = $p->no_bpjs_asuransi ?: ($p->pasien->no_bpjs ?: '-');
            if (stripos($penjaminNama, 'Pribadi') !== false || stripos($penjaminNama, 'Umum') !== false) {
                if (!$p->no_bpjs_asuransi) {
                    $noKartu = '-';
                }
            }

            $klinikNama = $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1');
            $tglDaftar = $p->tanggal_daftar ? Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';

            $rows[] = (object)[
                'no' => $no++,
                'tanggal' => $tglDaftar,
                'no_reg' => $p->nomor_registrasi,
                'nama' => $p->pasien->nama_pasien ?? '-',
                'no_rm' => $p->pasien->no_rm ?? '-',
                'jk' => $jk,
                'penjamin' => $penjaminNama,
                'no_kartu' => $noKartu,
                'klinik' => $klinikNama,
                'adm' => $adm,
                'tindakan' => $tindakan,
                'konsultasi' => $konsultasi,
                'farmasi' => $farmasi,
                'lab' => $lab,
                'radiologi' => $radiologi,
                'sub_total' => $subTotal,
                'disc' => $disc,
                'total_tagihan' => $totalTagihan,
                'dijamin' => $dijamin,
                'dibayar_pasien' => $dibayarPasien,
                'cara_bayar' => $caraBayarVal,
            ];
        }

        return view('laporan.laporan_transaksi_kasir_pdf', compact('rows', 'tanggal'));
    }

    public function exportExcel(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-14';
        $filename = 'Laporan_Transaksi_Kasir_' . $tanggal . '.csv';

        $query = Pendaftaran::with(['pasien', 'poliklinik', 'penjamin', 'transaksiKasir'])
            ->whereDate('tanggal_daftar', $tanggal);

        $pendaftarans = $query->orderBy('nomor_registrasi', 'desc')
                              ->orderBy('id', 'desc')
                              ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($pendaftarans, $tanggal) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No', 'Tanggal', 'No Reg', 'Nama', 'No. RM', 'J.K', 'Penjamin', 'No Kartu',
                'Klinik', 'Adm', 'Tindakan', 'Konsultasi', 'Farmasi', 'Lab', 'Radiologi',
                'Sub Total', 'Disc(%)', 'Total Tagihan', 'Dijamin', 'Dibayar Pasien', 'Cara Bayar'
            ]);

            $no = 1;
            foreach ($pendaftarans as $p) {
                $tk = $p->transaksiKasir;

                $adm = $tk ? (float)$tk->biaya_adm : 10000;
                $tindakan = $tk ? (float)$tk->total_tindakan : 0;
                $konsultasi = $tk ? (float)$tk->biaya_konsultasi : 0;
                $farmasi = $tk ? (float)$tk->total_obat : 0;
                $lab = $tk ? (float)$tk->biaya_lab : 0;
                $radiologi = $tk ? (float)$tk->biaya_radiologi : 0;

                $subTotal = $tk && $tk->sub_total > 0 ? (float)$tk->sub_total : ($adm + $tindakan + $konsultasi + $farmasi + $lab + $radiologi);
                $disc = $tk ? (float)$tk->diskon : 0;
                $totalTagihan = $tk ? (float)$tk->total_tagihan : 0;
                $dijamin = $tk ? (float)$tk->dijamin : 0;
                $dibayarPasien = $tk ? (float)$tk->dibayar_pasien : 0;
                $caraBayarVal = $tk && $tk->cara_bayar ? $tk->cara_bayar : 'Belum Bayar';

                $jk = '-';
                if ($p->pasien) {
                    $jk = ($p->pasien->jenis_kelamin == 'P' || strtolower($p->pasien->jenis_kelamin) == 'perempuan') ? 'Perempuan' : 'Laki-Laki';
                }

                $penjaminNama = $p->penjamin->nama_penjamin ?? 'Pribadi';
                $noKartu = $p->no_bpjs_asuransi ?: ($p->pasien->no_bpjs ?: '-');
                if (stripos($penjaminNama, 'Pribadi') !== false || stripos($penjaminNama, 'Umum') !== false) {
                    if (!$p->no_bpjs_asuransi) {
                        $noKartu = '-';
                    }
                }

                $klinikNama = $p->poliklinik->nama_poli ?? ($p->ruang_pemeriksaan ?: 'Klinik Umum 1');
                $tglDaftar = $p->tanggal_daftar ? Carbon::parse($p->tanggal_daftar)->format('d-m-Y') : '14-10-2026';

                fputcsv($handle, [
                    $no++,
                    $tglDaftar,
                    $p->nomor_registrasi,
                    $p->pasien->nama_pasien ?? '-',
                    $p->pasien->no_rm ?? '-',
                    $jk,
                    $penjaminNama,
                    $noKartu,
                    $klinikNama,
                    number_format($adm, 0, ',', '.'),
                    number_format($tindakan, 0, ',', '.'),
                    number_format($konsultasi, 0, ',', '.'),
                    number_format($farmasi, 0, ',', '.'),
                    number_format($lab, 0, ',', '.'),
                    number_format($radiologi, 0, ',', '.'),
                    number_format($subTotal, 0, ',', '.'),
                    number_format($disc, 0, ',', '.'),
                    number_format($totalTagihan, 0, ',', '.'),
                    number_format($dijamin, 0, ',', '.'),
                    number_format($dibayarPasien, 0, ',', '.'),
                    $caraBayarVal,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

