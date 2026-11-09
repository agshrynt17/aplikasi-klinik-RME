<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class BackOfficeController extends Controller
{
    // Generate dummy data helper
    private function generateDummyData($type, $count)
    {
        $data = [];
        $names = ['Budi Santoso', 'Siti Aminah', 'Agus Setiawan', 'Dewi Lestari', 'Joko Widodo', 'Rina Nose'];
        $doctors = ['dr. Andi', 'dr. Budi', 'dr. Clara'];
        
        for ($i = 0; $i < $count; $i++) {
            $date = Carbon::now()->subDays(rand(0, 10))->format('Y-m-d');
            
            if ($type == 'jasa_medis') {
                $data[] = (object)[
                    'tanggal' => $date,
                    'no_rawat' => 'RW-' . rand(1000, 9999),
                    'nama_pasien' => $names[array_rand($names)],
                    'nama_dokter' => $doctors[array_rand($doctors)],
                    'tindakan' => 'Konsultasi & Pemeriksaan',
                    'tarif_tindakan' => rand(50000, 150000),
                    'jasa_medis' => rand(30000, 100000),
                ];
            } elseif ($type == 'klaim') {
                $data[] = (object)[
                    'tanggal' => $date,
                    'no_rawat' => 'RW-' . rand(1000, 9999),
                    'no_sep' => 'SEP-' . rand(1000000, 9999999),
                    'nama_pasien' => $names[array_rand($names)],
                    'diagnosa' => 'A09 - Diarrhoea and gastroenteritis',
                    'tarif_rs' => rand(150000, 300000),
                    'tarif_inacbg' => rand(120000, 250000),
                    'status' => (rand(0, 1) == 1) ? 'Diklaim' : 'Pending',
                ];
            } elseif ($type == 'pendapatan') {
                $data[] = (object)[
                    'tanggal' => $date,
                    'no_rawat' => 'RW-' . rand(1000, 9999),
                    'nama_pasien' => $names[array_rand($names)],
                    'jenis_pasien' => (rand(0, 1) == 1) ? 'UMUM' : 'BPJS',
                    'total_tagihan' => rand(150000, 500000),
                    'sudah_dibayar' => rand(150000, 500000),
                    'piutang' => 0,
                ];
            } elseif ($type == 'kasir') {
                $total = rand(150000, 500000);
                $data[] = (object)[
                    'no_kwitansi' => 'KWT-' . rand(1000, 9999),
                    'tanggal' => $date,
                    'no_rawat' => 'RW-' . rand(1000, 9999),
                    'nama_pasien' => $names[array_rand($names)],
                    'total_tagihan' => $total,
                    'pembayaran' => 'Tunai',
                    'kasir' => 'Admin Kasir',
                ];
            }
        }
        return collect($data)->sortByDesc('tanggal')->values()->all();
    }

    // 1. Lap. Jasa Medis Tindakan
    public function jasaMedis(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');
        $data = $this->generateDummyData('jasa_medis', 20);
        return view('backoffice.jasa_medis', compact('data', 'bulan', 'tahun'));
    }

    // 2. Klaim Rawat Jalan
    public function klaimRawatJalan(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');
        $data = $this->generateDummyData('klaim', 15);
        return view('backoffice.klaim_rajal', compact('data', 'bulan', 'tahun'));
    }

    // 3. Pendapatan Pasien
    public function pendapatanPasien(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');
        $data = $this->generateDummyData('pendapatan', 20);
        return view('backoffice.pendapatan_pasien', compact('data', 'bulan', 'tahun'));
    }

    // 4. Lap. Transaksi Kasir
    public function lapTransaksiKasir(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');
        $data = $this->generateDummyData('kasir', 25);
        return view('backoffice.lap_kasir', compact('data', 'bulan', 'tahun'));
    }

    // 5. Transaksi Kasir (Dashboard Form Kasir)
    public function transaksiKasir(Request $request)
    {
        // Ini adalah layar antrean pasien yang belum dibayar di kasir (Billing / Checkout)
        $antrean = [
            (object)['no_rawat' => 'RW-001', 'nama_pasien' => 'Agus Setiawan', 'poli' => 'Poli Umum', 'dokter' => 'dr. Andi', 'total' => 150000],
            (object)['no_rawat' => 'RW-002', 'nama_pasien' => 'Siti Aminah', 'poli' => 'Poli Gigi', 'dokter' => 'dr. Budi', 'total' => 250000],
        ];
        return view('backoffice.transaksi_kasir', compact('antrean'));
    }
}
