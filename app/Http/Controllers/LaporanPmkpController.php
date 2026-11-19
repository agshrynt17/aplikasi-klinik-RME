<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';

        $parseDate = function ($str) {
            if (!$str) return date('Y-m-d');
            $parts = explode('-', $str);
            if (count($parts) == 3) {
                if (strlen($parts[0]) == 4) {
                    return $str;
                } else {
                    return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }
            return date('Y-m-d');
        };

        $tglAwal = $parseDate($tglAwalInput);
        $tglAkhir = $parseDate($tglAkhirInput);

        $profil = \App\Models\ProfilFaskes::first();
        $namaKlinik = ($profil && !empty($profil->nama_faskes)) ? $profil->nama_faskes : 'KLINIK TELAGASARI 24 JAM';

        $surveys = DB::table('survei_kepuasans')
            ->whereDate('created_at', '>=', $tglAwal)
            ->whereDate('created_at', '<=', $tglAkhir)
            ->get();

        $count = $surveys->count();

        // Calculate Permenpan RB IKM
        $nilaiUnsur = array_fill(1, 9, 0);
        $nrrUnsur = array_fill(1, 9, 0.0);
        $nrrTertimbang = array_fill(1, 9, 0.0);
        $totalNrrTertimbang = 0.0;
        $ikm = 0.0;

        if ($count > 0) {
            foreach ($surveys as $s) {
                for ($i = 1; $i <= 9; $i++) {
                    $val = $s->{"u{$i}"} ?? 3;
                    $nilaiUnsur[$i] += $val;
                }
            }
            for ($i = 1; $i <= 9; $i++) {
                $nrrUnsur[$i] = $nilaiUnsur[$i] / $count;
                $nrrTertimbang[$i] = $nrrUnsur[$i] * 0.11;
                $totalNrrTertimbang += $nrrTertimbang[$i];
            }
            $ikm = $totalNrrTertimbang * 25;
        }

        return view('laporan.survey_kepuasan', compact(
            'surveys',
            'tglAwalInput',
            'tglAkhirInput',
            'nilaiUnsur',
            'nrrUnsur',
            'nrrTertimbang',
            'totalNrrTertimbang',
            'ikm',
            'namaKlinik'
        ));
    }

    public function cetakSurveyKepuasanPdf(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        return response('<script>window.print();</script><h2 style="font-family:sans-serif;">PENGOLAHAN DATA SURVEI KEPUASAN MASYARAKAT</h2><p>Periode: ' . $tglAwalInput . ' s/d ' . $tglAkhirInput . '</p>');
    }

    // 2. Laporan Waktu Tunggu Pelayanan
    public function waktuTungguPelayanan(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        $jenisAsesmen = $request->jenis_asesmen ?: 'Asesmen Awal';

        $parseDate = function ($str) {
            if (!$str) return date('Y-m-d');
            $parts = explode('-', $str);
            if (count($parts) == 3) {
                if (strlen($parts[0]) == 4) {
                    return $str; // YYYY-MM-DD
                } else {
                    return $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // DD-MM-YYYY to YYYY-MM-DD
                }
            }
            return date('Y-m-d');
        };

        $tglAwal = $parseDate($tglAwalInput);
        $tglAkhir = $parseDate($tglAkhirInput);

        if ($jenisAsesmen == 'Asesmen Ulang') {
            $table = 'asesmen_ulangs';
        } else {
            $table = 'asesmen_awal_rajals';
        }

        $query = DB::table($table)
            ->join('pendaftarans', "{$table}.pendaftaran_id", '=', 'pendaftarans.id')
            ->join('pasiens', "{$table}.pasien_id", '=', 'pasiens.id')
            ->select(
                "{$table}.*",
                'pendaftarans.nomor_registrasi',
                'pendaftarans.tanggal_daftar',
                'pendaftarans.jam_daftar',
                'pasiens.nama_pasien',
                'pasiens.no_rm'
            )
            ->whereDate('pendaftarans.tanggal_daftar', '>=', $tglAwal)
            ->whereDate('pendaftarans.tanggal_daftar', '<=', $tglAkhir);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q, $table) {
                $w->where('pendaftarans.nomor_registrasi', 'like', "%{$q}%")
                  ->orWhere('pasiens.nama_pasien', 'like', "%{$q}%")
                  ->orWhere('pasiens.no_rm', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy("{$table}.created_at", 'asc')->get();

        $rataRata = '0 Jam 0 Menit';

        return view('laporan.waktu_tunggu_pelayanan', compact('data', 'tglAwalInput', 'tglAkhirInput', 'jenisAsesmen', 'rataRata'));
    }

    public function cetakWaktuTungguPelayananPdf(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        $jenisAsesmen = $request->jenis_asesmen ?: 'Asesmen Awal';
        return response('<script>window.print();</script><h2 style="font-family:sans-serif;">LAPORAN WAKTU TUNGGU PELAYANAN</h2><p>Periode: ' . $tglAwalInput . ' s/d ' . $tglAkhirInput . ' (' . $jenisAsesmen . ')</p>');
    }

    public function cetakWaktuTungguPelayananExcel(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        $filename = "Laporan_Waktu_Tunggu_Pelayanan_{$tglAwalInput}_{$tglAkhirInput}.xls";
        
        return response("Kode Registrasi\tTanggal\tNama Pasien\tNo RM\tJam Pendaftaran\tJam Asesmen\tWaktu Tunggu\tKet\n", 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // 3. Laporan Waktu Tunggu Obat
    public function waktuTungguObat(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';

        // Parse format safely
        $parseDate = function ($str) {
            if (!$str) return date('Y-m-d');
            $parts = explode('-', $str);
            if (count($parts) == 3) {
                if (strlen($parts[0]) == 4) {
                    return $str; // YYYY-MM-DD
                } else {
                    return $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // DD-MM-YYYY to YYYY-MM-DD
                }
            }
            return date('Y-m-d');
        };

        $tglAwal = $parseDate($tglAwalInput);
        $tglAkhir = $parseDate($tglAkhirInput);

        $query = DB::table('reseps')
            ->join('pendaftarans', 'reseps.pendaftaran_id', '=', 'pendaftarans.id')
            ->join('pasiens', 'reseps.pasien_id', '=', 'pasiens.id')
            ->select(
                'reseps.*',
                'pendaftarans.nomor_registrasi',
                'pendaftarans.tanggal_daftar',
                'pasiens.nama_pasien',
                'pasiens.no_rm'
            )
            ->whereDate('pendaftarans.tanggal_daftar', '>=', $tglAwal)
            ->whereDate('pendaftarans.tanggal_daftar', '<=', $tglAkhir);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('pendaftarans.nomor_registrasi', 'like', "%{$q}%")
                  ->orWhere('pasiens.nama_pasien', 'like', "%{$q}%")
                  ->orWhere('pasiens.no_rm', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('reseps.waktu_proses', 'asc')->get();

        return view('laporan.waktu_tunggu_obat', compact('data', 'tglAwalInput', 'tglAkhirInput'));
    }

    public function cetakWaktuTungguObatPdf(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        return response('<script>window.print();</script><h2 style="font-family:sans-serif;">LAPORAN WAKTU TUNGGU OBAT</h2><p>Periode: ' . $tglAwalInput . ' s/d ' . $tglAkhirInput . '</p>');
    }

    public function cetakWaktuTungguObatExcel(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        $filename = "Laporan_Waktu_Tunggu_Obat_{$tglAwalInput}_{$tglAkhirInput}.xls";
        
        return response("Kode Registrasi\tTanggal\tNama Pasien\tNo RM\tWaktu Penerimaan Resep\tWaktu Penyerahan Obat\tWaktu Tunggu\n", 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // 4. Laporan Waktu Tunggu Obat Racik
    public function waktuTungguObatRacik(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';

        $parseDate = function ($str) {
            if (!$str) return date('Y-m-d');
            $parts = explode('-', $str);
            if (count($parts) == 3) {
                if (strlen($parts[0]) == 4) {
                    return $str; // YYYY-MM-DD
                } else {
                    return $parts[2] . '-' . $parts[1] . '-' . $parts[0]; // DD-MM-YYYY to YYYY-MM-DD
                }
            }
            return date('Y-m-d');
        };

        $tglAwal = $parseDate($tglAwalInput);
        $tglAkhir = $parseDate($tglAkhirInput);

        $query = DB::table('reseps')
            ->join('pendaftarans', 'reseps.pendaftaran_id', '=', 'pendaftarans.id')
            ->join('pasiens', 'reseps.pasien_id', '=', 'pasiens.id')
            ->select(
                'reseps.*',
                'pendaftarans.nomor_registrasi',
                'pendaftarans.tanggal_daftar',
                'pasiens.nama_pasien',
                'pasiens.no_rm'
            )
            ->where('reseps.is_racikan', true)
            ->whereDate('pendaftarans.tanggal_daftar', '>=', $tglAwal)
            ->whereDate('pendaftarans.tanggal_daftar', '<=', $tglAkhir);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('pendaftarans.nomor_registrasi', 'like', "%{$q}%")
                  ->orWhere('pasiens.nama_pasien', 'like', "%{$q}%")
                  ->orWhere('pasiens.no_rm', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('reseps.waktu_proses', 'asc')->get();

        $rataRata = '0 Jam 0 Menit';

        return view('laporan.waktu_tunggu_obat_racik', compact('data', 'tglAwalInput', 'tglAkhirInput', 'rataRata'));
    }

    public function cetakWaktuTungguRacikPdf(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        return response('<script>window.print();</script><h2 style="font-family:sans-serif;">LAPORAN WAKTU TUNGGU OBAT RACIK</h2><p>Periode: ' . $tglAwalInput . ' s/d ' . $tglAkhirInput . '</p>');
    }

    public function cetakWaktuTungguRacikExcel(Request $request)
    {
        $tglAwalInput = $request->tgl_awal ?: '14-10-2026';
        $tglAkhirInput = $request->tgl_akhir ?: '14-10-2026';
        $filename = "Laporan_Waktu_Tunggu_Obat_Racik_{$tglAwalInput}_{$tglAkhirInput}.xls";
        
        return response("Kode Registrasi\tTanggal\tNama Pasien\tNo RM\tWaktu Penerimaan Resep\tWaktu Penyerahan Obat\tWaktu Tunggu\n", 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
