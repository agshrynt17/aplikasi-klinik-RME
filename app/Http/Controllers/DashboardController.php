<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $hariIni = Carbon::today();
        $kemarin = Carbon::yesterday();
        $bulanIni = $hariIni->month;
        $tahunIni = $hariIni->year;

        // Nama Klinik
        $profil = \App\Models\ProfilFaskes::first();
        $namaKlinik = ($profil && !empty($profil->nama_faskes)) ? $profil->nama_faskes : 'KLINIK TELAGASARI 24 JAM';

        // 1. Pasien Hari Ini
        $totalPendaftaranHariIni = Pendaftaran::whereDate('tanggal_daftar', $hariIni)->count();
        $totalPendaftaranKemarin = Pendaftaran::whereDate('tanggal_daftar', $kemarin)->count();
        $pasienHariIni = $totalPendaftaranHariIni;
        $pasienKemarin = $totalPendaftaranKemarin;
        if ($pasienKemarin > 0) {
            $diffP = $pasienHariIni - $pasienKemarin;
            $pasienHariIniTrend = ($diffP >= 0 ? '+' : '') . round(($diffP / $pasienKemarin) * 100) . '%';
        } else {
            $pasienHariIniTrend = $pasienHariIni > 0 ? '+100%' : '0%';
        }

        // 2. Pasien Bulan Ini
        $totalPasienBulanIni = Pendaftaran::whereMonth('tanggal_daftar', $bulanIni)->whereYear('tanggal_daftar', $tahunIni)->count();
        $totalPasienBulanLalu = Pendaftaran::whereMonth('tanggal_daftar', $hariIni->copy()->subMonth()->month)
                                           ->whereYear('tanggal_daftar', $hariIni->copy()->subMonth()->year)
                                           ->count();
        $pasienBulanIni = number_format($totalPasienBulanIni);
        $pasienBulanLalu = number_format($totalPasienBulanLalu);
        if ($totalPasienBulanLalu > 0) {
            $diffM = $totalPasienBulanIni - $totalPasienBulanLalu;
            $pasienBulanIniTrend = ($diffM >= 0 ? '+' : '') . round(($diffM / $totalPasienBulanLalu) * 100) . '%';
        } else {
            $pasienBulanIniTrend = $totalPasienBulanIni > 0 ? '+100%' : '0%';
        }

        // 3. Resep Hari Ini
        $totalResepHariIni = class_exists(\App\Models\Resep::class) ? \App\Models\Resep::whereDate('created_at', $hariIni)->count() : 0;
        $totalResepKemarin = class_exists(\App\Models\Resep::class) ? \App\Models\Resep::whereDate('created_at', $kemarin)->count() : 0;
        $resepHariIni = $totalResepHariIni;
        $resepKemarin = $totalResepKemarin;
        if ($resepKemarin > 0) {
            $diffR = $resepHariIni - $resepKemarin;
            $resepTrend = ($diffR >= 0 ? '+' : '') . round(($diffR / $resepKemarin) * 100) . '%';
        } else {
            $resepTrend = $resepHariIni > 0 ? '+100%' : '0%';
        }

        // 4. Lab Hari Ini
        $totalLabHariIni = class_exists(\App\Models\TransaksiLab::class) ? \App\Models\TransaksiLab::whereDate('created_at', $hariIni)->count() : 0;
        $totalLabKemarin = class_exists(\App\Models\TransaksiLab::class) ? \App\Models\TransaksiLab::whereDate('created_at', $kemarin)->count() : 0;
        $labHariIni = $totalLabHariIni;
        $labKemarin = $totalLabKemarin;
        if ($labKemarin > 0) {
            $diffL = $labHariIni - $labKemarin;
            $labTrend = ($diffL >= 0 ? '+' : '') . round(($diffL / $labKemarin) * 100) . '%';
        } else {
            $labTrend = $labHariIni > 0 ? '+100%' : '0%';
        }

        // 5. Radiologi
        $totalRadHariIni = class_exists(\App\Models\TransaksiRadiologi::class) ? \App\Models\TransaksiRadiologi::whereDate('created_at', $hariIni)->count() : 0;
        $totalRadKemarin = class_exists(\App\Models\TransaksiRadiologi::class) ? \App\Models\TransaksiRadiologi::whereDate('created_at', $kemarin)->count() : 0;
        $radHariIni = $totalRadHariIni;
        $radKemarin = $totalRadKemarin;
        if ($radKemarin > 0) {
            $diffRad = $radHariIni - $radKemarin;
            $radTrend = ($diffRad >= 0 ? '+' : '') . round(($diffRad / $radKemarin) * 100) . '%';
        } else {
            $radTrend = '0%';
        }

        // 6. Satu Sehat
        $satusehatCount = class_exists(\App\Models\LogKirimAntrian::class) ? \App\Models\LogKirimAntrian::whereDate('created_at', $hariIni)->count() : 0;
        $satusehatKemarinCount = class_exists(\App\Models\LogKirimAntrian::class) ? \App\Models\LogKirimAntrian::whereDate('created_at', $kemarin)->count() : 0;
        $satusehatHariIni = $satusehatCount;
        $satusehatKemarin = $satusehatKemarinCount;
        if ($satusehatKemarin > 0) {
            $diffSS = $satusehatHariIni - $satusehatKemarin;
            $satusehatTrend = ($diffSS >= 0 ? '+' : '') . round(($diffSS / $satusehatKemarin) * 100) . '%';
        } else {
            $satusehatTrend = $satusehatHariIni > 0 ? '+100%' : '0%';
        }

        $totalPasienTerdaftar = Pasien::count();
        
        $pendaftaranTerakhir = Pendaftaran::with(['pasien', 'poliklinik'])
                                ->whereDate('tanggal_daftar', $hariIni)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        $grafikLabel = [];
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $grafikLabel[] = $date->format('d M');
            $grafikData[] = Pendaftaran::whereDate('tanggal_daftar', $date)->count();
        }

        return view('dashboard', compact(
            'namaKlinik',
            'pasienHariIni', 'pasienKemarin', 'pasienHariIniTrend',
            'pasienBulanIni', 'pasienBulanLalu', 'pasienBulanIniTrend',
            'resepHariIni', 'resepKemarin', 'resepTrend',
            'labHariIni', 'labKemarin', 'labTrend',
            'radHariIni', 'radKemarin', 'radTrend',
            'satusehatHariIni', 'satusehatKemarin', 'satusehatTrend',
            'totalPendaftaranHariIni', 
            'totalPasienTerdaftar', 
            'pendaftaranTerakhir',
            'grafikLabel',
            'grafikData'
        ));
    }
}
