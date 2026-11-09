<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiLab;
use App\Models\Pegawai;
use App\Models\Penjamin;
use App\Models\MasterLab;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LapJasMedLabController extends Controller
{
    public function index(Request $request)
    {
        $tglAwal  = $request->tgl_awal  ?? '2026-10-01';
        $tglAkhir = $request->tgl_akhir ?? '2026-10-14';
        $penjaminId  = $request->penjamin_id ?? '';
        $masterLabId = $request->lab_id ?? '';
        $dokterId    = $request->dokter_id ?? '';

        $query = TransaksiLab::with([
                    'pendaftaran.pasien',
                    'pendaftaran.penjamin',
                    'pendaftaran.dokter',
                    'masterLab',
                    'analis',
                ])
                ->join('pendaftarans', 'transaksi_labs.pendaftaran_id', '=', 'pendaftarans.id')
                ->select('transaksi_labs.*')
                ->whereBetween('pendaftarans.tanggal_daftar', [$tglAwal, $tglAkhir]);

        if ($penjaminId) {
            $query->where('pendaftarans.penjamin_id', $penjaminId);
        }

        if ($masterLabId) {
            $query->where('transaksi_labs.master_lab_id', $masterLabId);
        }

        if ($dokterId) {
            $query->where('pendaftarans.dokter_id', $dokterId);
        }

        $transaksis = $query->orderBy('pendaftarans.tanggal_daftar', 'asc')
                            ->orderBy('pendaftarans.id', 'asc')
                            ->orderBy('transaksi_labs.id', 'asc')
                            ->get();

        $penjamins  = Penjamin::where('is_active', true)->orderBy('nama_penjamin')->get();
        $masterLabs = MasterLab::where('is_active', true)->orderBy('nama_pemeriksaan')->get();
        $dokters    = Pegawai::where('is_active', true)->orderBy('nama_pegawai')->get();

        return view('laboratorium.lap_jasmed_lab', compact(
            'transaksis', 'tglAwal', 'tglAkhir', 'penjaminId', 'masterLabId', 'dokterId',
            'penjamins', 'masterLabs', 'dokters'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tglAwal  = $request->tgl_awal  ?? '2026-10-01';
        $tglAkhir = $request->tgl_akhir ?? '2026-10-14';
        $penjaminId  = $request->penjamin_id ?? '';
        $masterLabId = $request->lab_id ?? '';
        $dokterId    = $request->dokter_id ?? '';

        $query = TransaksiLab::with([
                    'pendaftaran.pasien',
                    'pendaftaran.penjamin',
                    'pendaftaran.dokter',
                    'masterLab',
                    'analis',
                ])
                ->join('pendaftarans', 'transaksi_labs.pendaftaran_id', '=', 'pendaftarans.id')
                ->select('transaksi_labs.*')
                ->whereBetween('pendaftarans.tanggal_daftar', [$tglAwal, $tglAkhir]);

        if ($penjaminId) {
            $query->where('pendaftarans.penjamin_id', $penjaminId);
        }
        if ($masterLabId) {
            $query->where('transaksi_labs.master_lab_id', $masterLabId);
        }
        if ($dokterId) {
            $query->where('pendaftarans.dokter_id', $dokterId);
        }

        $transaksis = $query->orderBy('pendaftarans.tanggal_daftar', 'asc')
                            ->orderBy('pendaftarans.id', 'asc')
                            ->orderBy('transaksi_labs.id', 'asc')
                            ->get();

        return view('laboratorium.lap_jasmed_lab_pdf', compact('transaksis', 'tglAwal', 'tglAkhir'));
    }

    public function exportXls(Request $request)
    {
        $tglAwal  = $request->tgl_awal  ?? '2026-10-01';
        $tglAkhir = $request->tgl_akhir ?? '2026-10-14';
        $penjaminId  = $request->penjamin_id ?? '';
        $masterLabId = $request->lab_id ?? '';
        $dokterId    = $request->dokter_id ?? '';

        $query = TransaksiLab::with([
                    'pendaftaran.pasien',
                    'pendaftaran.penjamin',
                    'pendaftaran.dokter',
                    'masterLab',
                    'analis',
                ])
                ->join('pendaftarans', 'transaksi_labs.pendaftaran_id', '=', 'pendaftarans.id')
                ->select('transaksi_labs.*')
                ->whereBetween('pendaftarans.tanggal_daftar', [$tglAwal, $tglAkhir]);

        if ($penjaminId) {
            $query->where('pendaftarans.penjamin_id', $penjaminId);
        }
        if ($masterLabId) {
            $query->where('transaksi_labs.master_lab_id', $masterLabId);
        }
        if ($dokterId) {
            $query->where('pendaftarans.dokter_id', $dokterId);
        }

        $transaksis = $query->orderBy('pendaftarans.tanggal_daftar', 'asc')
                            ->orderBy('pendaftarans.id', 'asc')
                            ->orderBy('transaksi_labs.id', 'asc')
                            ->get();

        $filename = "Laporan_Insentif_Lab_{$tglAwal}_sd_{$tglAkhir}.xls";

        return response()->streamDownload(function () use ($transaksis, $tglAwal, $tglAkhir) {
            echo "<html><head><meta charset='utf-8'></head><body>";
            echo "<h3>Laporan Insentif Lab</h3>";
            echo "<p>Periode: {$tglAwal} s/d {$tglAkhir}</p>";
            echo "<table border='1' cellspacing='0' cellpadding='5'>";
            echo "<tr style='background-color: #e4e4e4; font-weight: bold;'>";
            echo "<th>#</th><th>Tanggal</th><th>No Reg</th><th>Nama Pasien</th><th>Kode RM</th><th>Penjamin</th><th>Dokter</th><th>Nama Lab</th><th>Tarif Lab</th><th>Jasa Medis</th>";
            echo "</tr>";
            foreach ($transaksis as $i => $t) {
                $tgl = $t->pendaftaran && $t->pendaftaran->tanggal_daftar ? $t->pendaftaran->tanggal_daftar : '-';
                $noReg = $t->pendaftaran ? $t->pendaftaran->nomor_registrasi : '-';
                $pasien = $t->pendaftaran && $t->pendaftaran->pasien ? $t->pendaftaran->pasien->nama_pasien : '-';
                $rm = $t->pendaftaran && $t->pendaftaran->pasien ? $t->pendaftaran->pasien->no_rm : '-';
                $penjamin = $t->pendaftaran && $t->pendaftaran->penjamin ? $t->pendaftaran->penjamin->nama_penjamin : 'Pribadi';
                $dokter = $t->pendaftaran && $t->pendaftaran->dokter ? $t->pendaftaran->dokter->nama_pegawai : 'dr. Fahmi Rizal';
                $lab = $t->masterLab ? $t->masterLab->nama_pemeriksaan : '-';
                $tarif = number_format($t->tarif ?? 0);
                echo "<tr>";
                echo "<td>" . ($i + 1) . "</td>";
                echo "<td>{$tgl}</td>";
                echo "<td>{$noReg}</td>";
                echo "<td>{$pasien}</td>";
                echo "<td>{$rm}</td>";
                echo "<td>{$penjamin}</td>";
                echo "<td>{$dokter}</td>";
                echo "<td>{$lab}</td>";
                echo "<td align='right'>{$tarif}</td>";
                echo "<td align='right'>0</td>";
                echo "</tr>";
            }
            echo "</table></body></html>";
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
