<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanPmkpController extends Controller
{
    /**
     * Helper untuk generate data dummy jika tabel aslinya belum ada/kosong
     */
    private function generateDummyData($count, $type)
    {
        $data = [];
        $names = ['Budi Santoso', 'Siti Aminah', 'Agus Setiawan', 'Dewi Lestari', 'Joko Widodo', 'Rina Nose', 'Ahmad Dhani', 'Luna Maya'];
        $polis = ['Poli Umum', 'Poli Gigi', 'Poli KIA', 'IGD'];

        for ($i = 0; $i < $count; $i++) {
            $jamDaftar = Carbon::now()->subDays(rand(0, 5))->subHours(rand(1, 8));
            
            if ($type == 'survey') {
                $data[] = (object)[
                    'tanggal' => $jamDaftar->format('d/m/Y'),
                    'nama_pasien' => $names[array_rand($names)],
                    'poli' => $polis[array_rand($polis)],
                    'pelayanan_dokter' => rand(3, 5),
                    'pelayanan_perawat' => rand(3, 5),
                    'fasilitas' => rand(3, 5),
                    'kebersihan' => rand(3, 5),
                    'rata_rata' => 0 // dihitung nanti
                ];
            } elseif ($type == 'waktu_pelayanan') {
                $jamMulai = (clone $jamDaftar)->addMinutes(rand(10, 45));
                $jamSelesai = (clone $jamMulai)->addMinutes(rand(5, 20));
                
                $data[] = (object)[
                    'tanggal' => $jamDaftar->format('d/m/Y'),
                    'nama_pasien' => $names[array_rand($names)],
                    'poli' => $polis[array_rand($polis)],
                    'jam_daftar' => $jamDaftar->format('H:i'),
                    'jam_dilayani' => $jamMulai->format('H:i'),
                    'jam_selesai' => $jamSelesai->format('H:i'),
                    'waktu_tunggu' => $jamDaftar->diffInMinutes($jamMulai), // dalam menit
                    'waktu_layanan' => $jamMulai->diffInMinutes($jamSelesai),
                ];
            } elseif ($type == 'waktu_obat_nonracik') {
                $jamResepMasuk = Carbon::now()->subDays(rand(0, 5))->subHours(rand(1, 8));
                $jamResepSelesai = (clone $jamResepMasuk)->addMinutes(rand(5, 25)); // Standar PMKP obat non-racik <= 30 menit
                
                $data[] = (object)[
                    'tanggal' => $jamResepMasuk->format('d/m/Y'),
                    'nama_pasien' => $names[array_rand($names)],
                    'no_resep' => 'RSP-' . rand(1000, 9999),
                    'jam_resep_masuk' => $jamResepMasuk->format('H:i:s'),
                    'jam_resep_selesai' => $jamResepSelesai->format('H:i:s'),
                    'waktu_tunggu' => $jamResepMasuk->diffInMinutes($jamResepSelesai),
                    'standar' => 30,
                    'memenuhi_standar' => ($jamResepMasuk->diffInMinutes($jamResepSelesai) <= 30)
                ];
            } elseif ($type == 'waktu_obat_racik') {
                $jamResepMasuk = Carbon::now()->subDays(rand(0, 5))->subHours(rand(1, 8));
                $jamResepSelesai = (clone $jamResepMasuk)->addMinutes(rand(20, 65)); // Standar PMKP obat racik <= 60 menit
                
                $data[] = (object)[
                    'tanggal' => $jamResepMasuk->format('d/m/Y'),
                    'nama_pasien' => $names[array_rand($names)],
                    'no_resep' => 'RSP-R-' . rand(1000, 9999),
                    'jam_resep_masuk' => $jamResepMasuk->format('H:i:s'),
                    'jam_resep_selesai' => $jamResepSelesai->format('H:i:s'),
                    'waktu_tunggu' => $jamResepMasuk->diffInMinutes($jamResepSelesai),
                    'standar' => 60,
                    'memenuhi_standar' => ($jamResepMasuk->diffInMinutes($jamResepSelesai) <= 60)
                ];
            }
        }
        
        // Sorting by date descending (simulated by array reverse if needed, or just random)
        return $data;
    }

    // 1. Laporan Survey Kepuasan
    public function surveyKepuasan(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');
        
        $data = $this->generateDummyData(15, 'survey');
        
        // Calculate averages
        foreach($data as $d) {
            $d->rata_rata = ($d->pelayanan_dokter + $d->pelayanan_perawat + $d->fasilitas + $d->kebersihan) / 4;
        }

        return view('pmkp.survey_kepuasan', compact('data', 'bulan', 'tahun'));
    }

    // 2. Laporan Waktu Tunggu Pelayanan
    public function waktuTungguPelayanan(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $data = $this->generateDummyData(20, 'waktu_pelayanan');

        $rataTunggu = collect($data)->avg('waktu_tunggu');
        $rataLayanan = collect($data)->avg('waktu_layanan');

        return view('pmkp.waktu_tunggu_pelayanan', compact('data', 'bulan', 'tahun', 'rataTunggu', 'rataLayanan'));
    }

    // 3. Laporan Waktu Tunggu Obat (Non-Racik)
    public function waktuTungguObat(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $data = $this->generateDummyData(25, 'waktu_obat_nonracik');
        
        $totalSesuai = collect($data)->where('memenuhi_standar', true)->count();
        $persentase = count($data) > 0 ? ($totalSesuai / count($data)) * 100 : 0;

        return view('pmkp.waktu_tunggu_obat', compact('data', 'bulan', 'tahun', 'totalSesuai', 'persentase'));
    }

    // 4. Laporan Waktu Tunggu Obat Racik
    public function waktuTungguObatRacik(Request $request)
    {
        $bulan = $request->bulan ?: date('m');
        $tahun = $request->tahun ?: date('Y');

        $data = $this->generateDummyData(15, 'waktu_obat_racik');
        
        $totalSesuai = collect($data)->where('memenuhi_standar', true)->count();
        $persentase = count($data) > 0 ? ($totalSesuai / count($data)) * 100 : 0;

        return view('pmkp.waktu_tunggu_obat_racik', compact('data', 'bulan', 'tahun', 'totalSesuai', 'persentase'));
    }
}
