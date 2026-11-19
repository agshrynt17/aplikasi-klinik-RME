<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiTindakan;
use App\Models\Pegawai;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanJasaTindakanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-01';
        $q = trim($request->q ?? '');
        $dokterId = $request->dokter_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';

        $query = TransaksiTindakan::with(['pendaftaran.pasien', 'pendaftaran.penjamin', 'pelaksana', 'tindakan'])
            ->whereDate('created_at', $tanggal);

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->whereHas('pendaftaran', function($qp) use ($q) {
                    $qp->where('nomor_registrasi', 'like', "%{$q}%")
                       ->orWhere('no_rawat', 'like', "%{$q}%")
                       ->orWhereHas('pasien', function($qpas) use ($q) {
                           $qpas->where('nama_pasien', 'like', "%{$q}%")
                                ->orWhere('no_rm', 'like', "%{$q}%");
                       });
                })->orWhereHas('tindakan', function($qt) use ($q) {
                    $qt->where('nama_tindakan', 'like', "%{$q}%");
                });
            });
        }

        if ($dokterId) {
            $query->where('pelaksana_id', $dokterId);
        }

        if ($penjaminId) {
            $query->whereHas('pendaftaran', function($qp) use ($penjaminId) {
                $qp->where('penjamin_id', $penjaminId);
            });
        }

        $data = $query->orderBy('id', 'asc')->get();

        $dokters = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();
        $penjamins = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();

        return view('laporan.laporan_jasa_tindakan', compact('data', 'tanggal', 'q', 'dokterId', 'penjaminId', 'dokters', 'penjamins'));
    }

    public function exportPdf(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-01';
        $q = trim($request->q ?? '');
        $dokterId = $request->dokter_id ?? '';
        $penjaminId = $request->penjamin_id ?? '';

        $query = TransaksiTindakan::with(['pendaftaran.pasien', 'pendaftaran.penjamin', 'pelaksana', 'tindakan'])
            ->whereDate('created_at', $tanggal);

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->whereHas('pendaftaran', function($qp) use ($q) {
                    $qp->where('nomor_registrasi', 'like', "%{$q}%")
                       ->orWhere('no_rawat', 'like', "%{$q}%")
                       ->orWhereHas('pasien', function($qpas) use ($q) {
                           $qpas->where('nama_pasien', 'like', "%{$q}%")
                                ->orWhere('no_rm', 'like', "%{$q}%");
                       });
                })->orWhereHas('tindakan', function($qt) use ($q) {
                    $qt->where('nama_tindakan', 'like', "%{$q}%");
                });
            });
        }

        if ($dokterId) {
            $query->where('pelaksana_id', $dokterId);
        }

        if ($penjaminId) {
            $query->whereHas('pendaftaran', function($qp) use ($penjaminId) {
                $qp->where('penjamin_id', $penjaminId);
            });
        }

        $items = $query->orderBy('id', 'asc')->get();

        return view('laporan.laporan_jasa_tindakan_pdf', compact('items', 'tanggal'));
    }

    public function exportXls(Request $request)
    {
        $tanggal = $request->tanggal ?? '2026-10-01';
        $filename = 'Laporan_Jasa_Tindakan_Medis_' . $tanggal . '.csv';

        $query = TransaksiTindakan::with(['pendaftaran.pasien', 'pendaftaran.penjamin', 'pelaksana', 'tindakan'])
            ->whereDate('created_at', $tanggal)
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
                'No', 'Tanggal', 'Jam', 'No Reg', 'Nama Pasien', 'Kode RM', 'Penjamin', 'Dokter', 'Tindakan', 'Jasa Medis'
            ]);

            foreach ($items as $idx => $item) {
                $p = $item->pendaftaran;
                $tgl = $item->created_at ? Carbon::parse($item->created_at)->format('Y-m-d') : $tanggal;
                $jam = $item->created_at ? Carbon::parse($item->created_at)->format('H:i') : '08:00';

                fputcsv($handle, [
                    $idx + 1,
                    $tgl,
                    $jam,
                    $p->nomor_registrasi ?? '-',
                    $p->pasien->nama_pasien ?? '-',
                    $p->pasien->no_rm ?? '-',
                    $p->penjamin->nama_penjamin ?? 'BPJS Kesehatan',
                    $item->pelaksana->nama_pegawai ?? ($p->dokter->nama_pegawai ?? '-'),
                    $item->tindakan->nama_tindakan ?? '-',
                    number_format($item->subtotal ?? 0, 0, ',', '.')
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

