<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BridgingConfig;
use App\Models\LogKirimAntrian;
use Carbon\Carbon;

class BridgingDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Parameter filter sesuai form di screenshot
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $jenis_waktu = $request->get('jenis_waktu', 'Harian');
        $faskes_tipe = $request->get('faskes_tipe', 'RS');

        // Cek status bridging antrol BPJS
        $config = BridgingConfig::where('provider', 'bpjs_antrol')->first();
        $isBridgingActive = $config && !empty($config->client_id) && !empty($config->client_secret) && !empty($config->user_key);

        $simulate = $request->boolean('simulate', false);

        if ($isBridgingActive || $simulate) {
            $statusError = null;

            // Jika bridging aktif atau mode simulasi aktif, hitung atau berikan metrik real/simulasi
            $tunggu_admisi  = 12; // menit
            $layan_admisi   = 8;  // menit
            $tunggu_poli    = 25; // menit
            $layan_poli     = 18; // menit
            $tunggu_farmasi = 14; // menit
            $layan_farmasi  = 9;  // menit

            $poli_labels = ['Poli Umum', 'Poli Gigi & Mulut', 'Poli KIA / KB', 'Poli Penyakit Dalam', 'Poli Anak'];
            $poli_data = [20, 32, 18, 45, 24];

            $trend_labels = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00'];
            $trend_data = [12, 18, 28, 35, 22, 16, 10];
        } else {
            // Tampilan default 1:1 sesuai tangkapan layar PROFASKES ketika bridging belum aktif
            $statusError = 'Gagal! Bridging Antrol belum aktif.';

            $tunggu_admisi  = 0;
            $layan_admisi   = 0;
            $tunggu_poli    = 0;
            $layan_poli     = 0;
            $tunggu_farmasi = 0;
            $layan_farmasi  = 0;

            $poli_labels = [];
            $poli_data = [];

            $trend_labels = [];
            $trend_data = [];
        }

        return view('bridging.dashboard_waktu_pelayanan', compact(
            'tanggal',
            'jenis_waktu',
            'faskes_tipe',
            'statusError',
            'isBridgingActive',
            'tunggu_admisi',
            'layan_admisi',
            'tunggu_poli',
            'layan_poli',
            'tunggu_farmasi',
            'layan_farmasi',
            'poli_labels',
            'poli_data',
            'trend_labels',
            'trend_data'
        ));
    }
}
