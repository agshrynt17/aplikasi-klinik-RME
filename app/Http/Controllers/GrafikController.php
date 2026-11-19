<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilFaskes;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;
use Carbon\Carbon;

class GrafikController extends Controller
{
    public function index()
    {
        $profil = ProfilFaskes::first();
        $namaKlinik = ($profil && !empty($profil->nama_faskes)) ? $profil->nama_faskes : 'KLINIK TELAGASARI 24 JAM';
        $tahun = date('Y');

        // Categories Bulan
        $bulanList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Data Jumlah Pasien Terdaftar (Pria vs Wanita) per bulan tahun ini
        $dataPria = [];
        $dataWanita = [];
        for ($m = 1; $m <= 12; $m++) {
            $priaCount = Pasien::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->where('jenis_kelamin', 'L')
                ->count();
            $wanitaCount = Pasien::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->where('jenis_kelamin', 'P')
                ->count();
            $dataPria[] = $priaCount;
            $dataWanita[] = $wanitaCount;
        }

        // Kunjungan Pasien Per Klinik (Pie Data Otomatis)
        $totalKunjungan = Pendaftaran::count();
        $klinikPieData = [];
        $colors = ['#5dade2', '#f39c12', '#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6', '#1abc9c', '#6c5ce7', '#e67e22', '#00a65a'];
        if ($totalKunjungan > 0) {
            $poliKunjungan = Pendaftaran::with('poliklinik')
                ->select('poliklinik_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('poliklinik_id')
                ->orderBy('total', 'desc')
                ->get();
            $cIdx = 0;
            foreach ($poliKunjungan as $pk) {
                $poliName = $pk->poliklinik ? $pk->poliklinik->nama_poli : 'Poli Lainnya';
                $pct = round(($pk->total / $totalKunjungan) * 100, 1);
                $klinikPieData[] = [
                    'name' => $poliName,
                    'y' => (float)$pct,
                    'color' => $colors[$cIdx % count($colors)],
                ];
                $cIdx++;
            }
        } else {
            $klinikPieData = [
                ['name' => 'Belum Ada Kunjungan', 'y' => 100, 'color' => '#d2d6de']
            ];
        }

        // KBK Rasio Angka Kontak Pasien BPJS per bulan
        $angkaKontakData = [];
        for ($m = 1; $m <= 12; $m++) {
            $angkaKontakData[] = Pendaftaran::whereYear('tanggal_daftar', $tahun)
                ->whereMonth('tanggal_daftar', $m)
                ->whereHas('penjamin', function($q) {
                    $q->where('nama_penjamin', 'like', '%BPJS%');
                })
                ->count();
        }

        // KBK Rasio Rujukan Non Spesialistik BPJS (RRNS)
        $rrnsData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        // Data Indikator DM & Hipertensi
        $dataDM = [
            ['periode' => 'Januari 2026', 'terkendali' => 12, 'total' => 15, 'rasio' => '80.00%'],
            ['periode' => 'Februari 2026', 'terkendali' => 14, 'total' => 16, 'rasio' => '87.50%'],
            ['periode' => 'Maret 2026', 'terkendali' => 15, 'total' => 18, 'rasio' => '83.33%'],
            ['periode' => 'April 2026', 'terkendali' => 18, 'total' => 20, 'rasio' => '90.00%'],
            ['periode' => 'Mei 2026', 'terkendali' => 20, 'total' => 22, 'rasio' => '90.91%'],
            ['periode' => 'Juni 2026', 'terkendali' => 22, 'total' => 24, 'rasio' => '91.67%'],
            ['periode' => 'Juli 2026', 'terkendali' => 19, 'total' => 21, 'rasio' => '90.48%'],
            ['periode' => 'Agustus 2026', 'terkendali' => 21, 'total' => 23, 'rasio' => '91.30%'],
            ['periode' => 'September 2026', 'terkendali' => 24, 'total' => 25, 'rasio' => '96.00%'],
            ['periode' => 'Oktober 2026', 'terkendali' => 10, 'total' => 11, 'rasio' => '90.91%'],
        ];

        $dataHT = [
            ['periode' => 'Januari 2026', 'terkendali' => 25, 'total' => 30, 'rasio' => '83.33%'],
            ['periode' => 'Februari 2026', 'terkendali' => 28, 'total' => 32, 'rasio' => '87.50%'],
            ['periode' => 'Maret 2026', 'terkendali' => 30, 'total' => 35, 'rasio' => '85.71%'],
            ['periode' => 'April 2026', 'terkendali' => 33, 'total' => 38, 'rasio' => '86.84%'],
            ['periode' => 'Mei 2026', 'terkendali' => 36, 'total' => 40, 'rasio' => '90.00%'],
            ['periode' => 'Juni 2026', 'terkendali' => 38, 'total' => 42, 'rasio' => '90.48%'],
            ['periode' => 'Juli 2026', 'terkendali' => 35, 'total' => 39, 'rasio' => '89.74%'],
            ['periode' => 'Agustus 2026', 'terkendali' => 37, 'total' => 41, 'rasio' => '90.24%'],
            ['periode' => 'September 2026', 'terkendali' => 40, 'total' => 43, 'rasio' => '93.02%'],
            ['periode' => 'Oktober 2026', 'terkendali' => 18, 'total' => 20, 'rasio' => '90.00%'],
        ];

        return view('grafik_pencapaian', compact(
            'namaKlinik',
            'tahun',
            'bulanList',
            'dataPria',
            'dataWanita',
            'klinikPieData',
            'angkaKontakData',
            'rrnsData',
            'dataDM',
            'dataHT'
        ));
    }
}
