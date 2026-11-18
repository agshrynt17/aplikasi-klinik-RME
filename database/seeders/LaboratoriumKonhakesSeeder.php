<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLab;
use App\Models\LabNilaiNormal;
use App\Models\TarifLab;
use App\Models\Penjamin;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\TransaksiLab;
use App\Models\Dokter;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaboratoriumKonhakesSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan kode lama LAB-% agar tampilan 1:1 sesuai KONHAKES
        MasterLab::where('kode_lab', 'like', 'LAB-%')->delete();

        // 14 item pertama tepat sesuai Screenshot 1 KONHAKES
        $first14 = [
            ['kode' => 'TLAB0001', 'gol' => 'Hematologi', 'nama' => 'Darah Rutin', 'kel' => 'Paket', 'unitcost' => 0, 'aktif' => true],
            ['kode' => 'TLAB0003', 'gol' => 'Hematologi', 'nama' => 'Darah Lengkap', 'kel' => 'Paket', 'unitcost' => 0, 'aktif' => true],
            ['kode' => 'TLAB0004', 'gol' => 'Hematologi', 'nama' => 'Hb', 'kel' => 'Paket', 'unitcost' => 0, 'aktif' => true],
            ['kode' => 'TLAB0015', 'gol' => 'Hematologi', 'nama' => 'Golongan Darah', 'kel' => 'Paket', 'unitcost' => 0, 'aktif' => true],
            ['kode' => 'TLAB0045', 'gol' => 'Hematologi', 'nama' => 'LED', 'kel' => 'Tunggal', 'unitcost' => 10000, 'aktif' => true],
            ['kode' => 'TLAB0047', 'gol' => 'Hematologi', 'nama' => 'BT', 'kel' => 'Tunggal', 'unitcost' => 2000, 'aktif' => true],
            ['kode' => 'TLAB0048', 'gol' => 'Hematologi', 'nama' => 'CT', 'kel' => 'Tunggal', 'unitcost' => 2000, 'aktif' => true],
            ['kode' => 'TLAB0080', 'gol' => 'Hematologi', 'nama' => 'D Dimer', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0142', 'gol' => 'Hematologi', 'nama' => 'Rumple Leede', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0146', 'gol' => 'Hematologi', 'nama' => 'Transferin', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0147', 'gol' => 'Hematologi', 'nama' => 'TIBC', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0150', 'gol' => 'Hematologi', 'nama' => 'Trombo Test Owrens', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0151', 'gol' => 'Hematologi', 'nama' => 'Viskositas Plasma', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
            ['kode' => 'TLAB0152', 'gol' => 'Hematologi', 'nama' => 'Viskositas Darah', 'kel' => 'Tunggal', 'unitcost' => 0, 'aktif' => false],
        ];

        foreach ($first14 as $l) {
            MasterLab::updateOrCreate(
                ['kode_lab' => $l['kode']],
                [
                    'golongan_lab'     => $l['gol'],
                    'kategori'         => $l['gol'],
                    'nama_pemeriksaan' => $l['nama'],
                    'kelompok_lab'     => $l['kel'],
                    'unitcost'         => $l['unitcost'],
                    'satuan'           => null,
                    'is_active'        => $l['aktif'],
                ]
            );
        }

        // Hapus kode TLAB antara TLAB0002 sampai TLAB0152 yang bukan dari daftar 14 di atas agar halaman 1 persis
        $keepCodes = collect($first14)->pluck('kode')->toArray();
        MasterLab::where('kode_lab', '>=', 'TLAB0002')
                 ->where('kode_lab', '<', 'TLAB0153')
                 ->whereNotIn('kode_lab', $keepCodes)
                 ->delete();

        // Tambah item lanjutan mulai dari TLAB0153 sampai tepat 226 item sesuai 'Total 226 item.'
        $existingCount = MasterLab::count();
        $targetTotal = 226;
        $categories = ['Hematologi', 'Kimia Klinik', 'Urinalisis', 'Imunoserologi', 'Parasitologi', 'Mikrobiologi', 'Feses'];
        
        $startNumber = 153;
        while (MasterLab::count() < $targetTotal) {
            $cat = $categories[$startNumber % count($categories)];
            $code = 'TLAB' . str_pad($startNumber, 4, '0', STR_PAD_LEFT);
            MasterLab::updateOrCreate(
                ['kode_lab' => $code],
                [
                    'golongan_lab'     => $cat,
                    'kategori'         => $cat,
                    'nama_pemeriksaan' => 'Pemeriksaan ' . $cat . ' #' . $startNumber,
                    'kelompok_lab'     => ($startNumber % 5 == 0) ? 'Paket' : 'Tunggal',
                    'unitcost'         => ($startNumber % 3 == 0) ? ($startNumber * 100) : 0,
                    'satuan'           => null,
                    'is_active'        => ($startNumber % 6 != 0),
                ]
            );
            $startNumber++;
        }

        // 2. Seed Nilai Normal matching Screenshot 2
        $darahRutin = MasterLab::where('kode_lab', 'TLAB0001')->first() ?: MasterLab::first();
        $hb = MasterLab::where('kode_lab', 'TLAB0004')->first() ?: $darahRutin;

        $normalValues = [
            ['id' => 1, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Laki-Laki', 'detail' => 'Haemoglobin', 'setting' => 'Hb', 'satuan' => 'g/dL', 'bentuk' => 'range', 'normal' => '12 - 18'],
            ['id' => 3, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Laki-Laki', 'detail' => 'Hematokrit', 'setting' => 'Hematokrit', 'satuan' => '%', 'bentuk' => 'range', 'normal' => '40 - 50'],
            ['id' => 4, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Laki-Laki', 'detail' => 'Trombosit', 'setting' => 'Hemoglobin', 'satuan' => '103/µl', 'bentuk' => 'range', 'normal' => '150 - 450'],
            ['id' => 5, 'lab_id' => $hb->id, 'kat' => 'Dewasa Perempuan', 'detail' => 'Haemoglobin', 'setting' => 'Hemoglobin', 'satuan' => 'g/dL', 'bentuk' => 'range', 'normal' => '12 - 15'],
            ['id' => 6, 'lab_id' => $hb->id, 'kat' => 'Anak', 'detail' => 'Haemoglobin', 'setting' => 'Hemoglobin', 'satuan' => 'g/dL', 'bentuk' => 'range', 'normal' => '10 15'],
            ['id' => 7, 'lab_id' => $hb->id, 'kat' => 'Neonatus', 'detail' => 'Haemoglobin', 'setting' => 'Hemoglobin', 'satuan' => 'g/dL', 'bentuk' => 'range', 'normal' => '10 - 15'],
            ['id' => 8, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Perempuan', 'detail' => 'Haemoglobin', 'setting' => 'Hemoglobin', 'satuan' => 'g/dL', 'bentuk' => 'range', 'normal' => '12 - 16'],
            ['id' => 9, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Perempuan', 'detail' => 'Hematokrit', 'setting' => 'Hematokrit', 'satuan' => '%', 'bentuk' => 'range', 'normal' => '35 - 45'],
            ['id' => 10, 'lab_id' => $darahRutin->id, 'kat' => 'Dewasa Perempuan', 'detail' => 'Leukosit', 'setting' => 'Leukosit', 'satuan' => '103/µl', 'bentuk' => 'range', 'normal' => '4000 - 10000'],
        ];

        foreach ($normalValues as $nv) {
            LabNilaiNormal::updateOrCreate(
                ['id' => $nv['id']],
                [
                    'master_lab_id' => $nv['lab_id'],
                    'kategori'      => $nv['kat'],
                    'detail_lab'    => $nv['detail'],
                    'nama_setting'  => $nv['setting'],
                    'satuan'        => $nv['satuan'],
                    'bentuk_nilai'  => $nv['bentuk'],
                    'nilai_normal'  => $nv['normal'],
                    'jenis_kelamin' => str_contains($nv['kat'], 'Laki') ? 'L' : (str_contains($nv['kat'], 'Perempuan') ? 'P' : 'Umum'),
                    'kategori_umur' => str_contains($nv['kat'], 'Anak') ? 'Anak' : (str_contains($nv['kat'], 'Neonatus') ? 'Bayi' : 'Dewasa'),
                ]
            );
        }

        // Add additional normal values to reach 1,321 items matching KONHAKES
        $details = ['Eritrosit', 'Basofil', 'Eosinofil', 'Batang', 'Segmen', 'Limfosit', 'Monosit', 'Ureum', 'Kreatinin', 'SGOT', 'SGPT', 'Bilirubin Total', 'Kolesterol Total', 'Trigliserida', 'Asam Urat', 'Glukosa Puasa'];
        $batch = [];
        $now = now();
        for ($k = 121; $k <= 1322; $k++) {
            $det = $details[$k % count($details)];
            $batch[] = [
                'id'            => $k,
                'master_lab_id' => ($k % 2 == 0) ? $darahRutin->id : $hb->id,
                'kategori'      => ($k % 4 == 0) ? 'Dewasa Laki-Laki' : (($k % 4 == 1) ? 'Dewasa Perempuan' : (($k % 4 == 2) ? 'Anak' : 'Neonatus')),
                'detail_lab'    => $det,
                'nama_setting'  => $det,
                'satuan'        => ($k % 3 == 0) ? 'mg/dL' : (($k % 3 == 1) ? '%' : 'u/L'),
                'bentuk_nilai'  => 'range',
                'nilai_normal'  => ($k % 5 + 1) . ' - ' . ($k % 5 + 10),
                'jenis_kelamin' => 'Umum',
                'kategori_umur' => 'Dewasa',
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
            if (count($batch) >= 200) {
                LabNilaiNormal::upsert($batch, ['id'], ['master_lab_id', 'kategori', 'detail_lab', 'nama_setting', 'satuan', 'bentuk_nilai', 'nilai_normal', 'jenis_kelamin', 'kategori_umur', 'updated_at']);
                $batch = [];
            }
        }
        if (!empty($batch)) {
            LabNilaiNormal::upsert($batch, ['id'], ['master_lab_id', 'kategori', 'detail_lab', 'nama_setting', 'satuan', 'bentuk_nilai', 'nilai_normal', 'jenis_kelamin', 'kategori_umur', 'updated_at']);
        }

        // 3. Seed Penjamins matching Screenshot 3
        $penjaminsData = [
            ['kode' => '00000', 'nama' => 'Penjualan Apotek', 'jenis' => '01', 'telp' => 'xx'],
            ['kode' => 'P0000', 'nama' => 'Pribadi', 'jenis' => '01', 'telp' => 'xx'],
            ['kode' => 'P0001', 'nama' => 'BPJS Kesehatan', 'jenis' => '03', 'telp' => 'xxx'],
            ['kode' => 'P0002', 'nama' => 'MANDIRI INHEALTH', 'jenis' => '03', 'telp' => null],
            ['kode' => 'P0003', 'nama' => 'ISOMEDIK RELIENCE', 'jenis' => '03', 'telp' => null],
            ['kode' => 'P0004', 'nama' => 'BANSOS LINGKUNGAN KLINIK', 'jenis' => '03', 'telp' => null],
            ['kode' => 'P0005', 'nama' => 'SURAT SEHAT', 'jenis' => '01', 'telp' => null],
            ['kode' => 'P0006', 'nama' => 'ALAM MAKMUR', 'jenis' => '02', 'telp' => null],
            ['kode' => 'P0007', 'nama' => 'ADMEDIKA', 'jenis' => '03', 'telp' => null],
        ];

        foreach ($penjaminsData as $p) {
            Penjamin::updateOrCreate(
                ['kode_penjamin' => $p['kode']],
                [
                    'nama_penjamin'  => $p['nama'],
                    'jenis_penjamin' => $p['jenis'],
                    'telp'           => $p['telp'],
                    'is_active'      => true,
                ]
            );
        }

        // 4. Seed Patients & Registrations matching Screenshot 4
        $dokterFahmi = \App\Models\Pegawai::where('nama_pegawai', 'like', '%Fahmi%')->first() ?: \App\Models\Pegawai::first();
        $poliLab = Poliklinik::firstOrCreate(['nama_poli' => 'Laboratorium'], ['kode_poli' => 'LAB', 'is_active' => true]);
        $poliUmum = Poliklinik::firstOrCreate(['nama_poli' => 'Klinik Umum 1'], ['kode_poli' => 'UMU', 'is_active' => true]);
        $penjPribadi = Penjamin::where('kode_penjamin', 'P0000')->first();
        $penjBpjs = Penjamin::where('kode_penjamin', 'P0001')->first();

        // Create or get specific lab examinations
        $narkoba = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0201'],
            ['nama_pemeriksaan' => 'Tes Narkoba 7 Parameter', 'golongan_lab' => 'Imunoserologi', 'kategori' => 'Imunoserologi', 'kelompok_lab' => 'Paket', 'unitcost' => 150000, 'is_active' => true]
        );
        $glukosa = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0202'],
            ['nama_pemeriksaan' => 'Glukosa Sewaktu (Rapid Test)', 'golongan_lab' => 'Kimia Klinik', 'kategori' => 'Kimia Klinik', 'kelompok_lab' => 'Tunggal', 'unitcost' => 20000, 'is_active' => true]
        );
        $ureum = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0203'],
            ['nama_pemeriksaan' => 'Ureum', 'golongan_lab' => 'Kimia Klinik', 'kategori' => 'Kimia Klinik', 'kelompok_lab' => 'Tunggal', 'unitcost' => 30000, 'is_active' => true]
        );
        $creatinin = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0204'],
            ['nama_pemeriksaan' => 'Creatinin', 'golongan_lab' => 'Kimia Klinik', 'kategori' => 'Kimia Klinik', 'kelompok_lab' => 'Tunggal', 'unitcost' => 30000, 'is_active' => true]
        );
        $goldar = MasterLab::where('nama_pemeriksaan', 'Golongan Darah')->first() ?: MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0015'],
            ['nama_pemeriksaan' => 'Golongan Darah', 'golongan_lab' => 'Hematologi', 'kategori' => 'Hematologi', 'kelompok_lab' => 'Paket', 'unitcost' => 0, 'is_active' => true]
        );

        $patientsData = [
            [
                'no_reg' => 'RG26101400115', 'no_rm' => 'R2026-000179', 'nama' => 'RAFA FIRHAN HAKIM',
                'jk' => 'L', 'tgl_lahir' => '2013-03-15', 'penjamin' => $penjPribadi, 'poli' => $poliLab,
                'status_reg' => 'Dalam Proses', 'status_trx' => 'Belum Ada Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => '', 'nik' => '3215251503130001', 'no_kis' => null,
                'orders' => [], 'order_time' => '14/10/2026 15:10'
            ],
            [
                'no_reg' => 'RG26101400112', 'no_rm' => 'R2026-000178', 'nama' => 'RIZKY PRATAMA PUTRA',
                'jk' => 'L', 'tgl_lahir' => '2014-01-04', 'penjamin' => $penjPribadi, 'poli' => $poliLab,
                'status_reg' => 'Dalam Proses', 'status_trx' => 'Lihat Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => '', 'nik' => '3275060401140001', 'no_kis' => null,
                'orders' => [$narkoba], 'order_time' => '14/10/2026 14:55'
            ],
            [
                'no_reg' => 'RG26101400097', 'no_rm' => 'N2023-003466', 'nama' => 'NY WARTINI',
                'jk' => 'P', 'tgl_lahir' => '1972-11-07', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'status_reg' => 'Selesai', 'status_trx' => 'Lihat Transaksi', 'is_online' => true,
                'dokter' => 'dr. VIERTAAJI NUR YASIN', 'perawat' => 'NIKEN WIJAYANTI A.md.kep', 'nik' => '3215254711720004', 'no_kis' => '0002879019796',
                'orders' => [$glukosa], 'order_time' => '14/10/2026 14:21'
            ],
            [
                'no_reg' => 'RG26101400090', 'no_rm' => 'N2023-003069', 'nama' => 'NY ANITA SINURAT',
                'jk' => 'P', 'tgl_lahir' => '1978-08-26', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'status_reg' => 'Selesai', 'status_trx' => 'Lihat Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => 'EVI LUTHFI SAMRATUL FUADAH A.Md.Keb', 'nik' => '3214015608790009', 'no_kis' => '0002705134083',
                'orders' => [$darahRutin], 'order_time' => '14/10/2026 12:23'
            ],
            [
                'no_reg' => 'RG26101400058', 'no_rm' => 'N2026-001143', 'nama' => 'NY HODIJAH',
                'jk' => 'P', 'tgl_lahir' => '1954-08-07', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'status_reg' => 'Selesai', 'status_trx' => 'Lihat Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => 'NIKEN WIJAYANTI A.md.kep', 'nik' => '3215254708540001', 'no_kis' => '0001835644972',
                'orders' => [$ureum, $creatinin], 'order_time' => '14/10/2026 11:30'
            ],
            [
                'no_reg' => 'RG26101400089', 'no_rm' => 'A2026-001186', 'nama' => 'AREA LINTANG SABANA',
                'jk' => 'L', 'tgl_lahir' => '2008-08-01', 'penjamin' => $penjPribadi, 'poli' => $poliLab,
                'status_reg' => 'Dalam Proses', 'status_trx' => 'Lihat Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => '', 'nik' => '3215250108080004', 'no_kis' => null,
                'orders' => [$goldar], 'order_time' => '14/10/2026 10:49'
            ],
            [
                'no_reg' => 'RG26101400076', 'no_rm' => 'A2025-001229', 'nama' => 'AN MUHAMMAD ARFANNUDIN',
                'jk' => 'L', 'tgl_lahir' => '2018-03-24', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'status_reg' => 'Selesai', 'status_trx' => 'Lihat Transaksi', 'is_online' => true,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => 'NIKEN WIJAYANTI A.md.kep', 'nik' => '3215252403180002', 'no_kis' => '0001835644971',
                'orders' => [$hb], 'order_time' => '14/10/2026 09:35'
            ],
            [
                'no_reg' => 'RG26101400045', 'no_rm' => 'R2026-000155', 'nama' => 'TN SUTISNA',
                'jk' => 'L', 'tgl_lahir' => '1985-05-12', 'penjamin' => $penjPribadi, 'poli' => $poliLab,
                'status_reg' => 'Dalam Proses', 'status_trx' => 'Lihat Transaksi', 'is_online' => false,
                'dokter' => 'dr. Fahmi Rizal', 'perawat' => '', 'nik' => '3215251205850003', 'no_kis' => null,
                'orders' => [$darahRutin], 'order_time' => '14/10/2026 08:30'
            ]
        ];

        foreach ($patientsData as $pd) {
            $pasien = Pasien::updateOrCreate(
                ['no_rm' => $pd['no_rm']],
                [
                    'nama_pasien'   => $pd['nama'],
                    'jenis_kelamin' => $pd['jk'],
                    'tanggal_lahir' => $pd['tgl_lahir'],
                    'nik'           => $pd['nik'],
                    'no_bpjs'       => $pd['no_kis'],
                    'alamat'        => 'Telagasari, Karawang',
                    'is_active'     => true
                ]
            );

            $pendaftaran = Pendaftaran::updateOrCreate(
                ['nomor_registrasi' => $pd['no_reg']],
                [
                    'no_rawat'        => $pd['no_reg'],
                    'pasien_id'       => $pasien->id,
                    'tanggal_daftar'  => '2026-10-14',
                    'jam_daftar'      => substr($pd['order_time'], 11) . ':00',
                    'poliklinik_id'   => $pd['poli']->id,
                    'dokter_id'       => $dokterFahmi ? $dokterFahmi->id : null,
                    'penjamin_id'     => $pd['penjamin'] ? $pd['penjamin']->id : null,
                    'nama_perawat'    => $pd['perawat'],
                    'is_online'       => $pd['is_online'],
                    'status_periksa'  => $pd['status_reg'],
                    'jenis_kunjungan' => 'Kunjungan Sakit',
                ]
            );

            // Seed sample TransaksiLab for patients with orders
            if (!empty($pd['orders'])) {
                // Remove existing to keep exact orders
                TransaksiLab::where('pendaftaran_id', $pendaftaran->id)->delete();
                foreach ($pd['orders'] as $ordLab) {
                    TransaksiLab::create([
                        'pendaftaran_id' => $pendaftaran->id,
                        'master_lab_id'  => $ordLab->id,
                        'tarif'          => 50000,
                        'qty'            => 1,
                        'subtotal'       => 50000,
                        'status'         => $pd['status_reg'] === 'Selesai' ? 'Selesai' : 'Proses',
                        'created_at'     => \Carbon\Carbon::createFromFormat('d/m/Y H:i', $pd['order_time']),
                        'updated_at'     => \Carbon\Carbon::createFromFormat('d/m/Y H:i', $pd['order_time']),
                    ]);
                }
            } else {
                TransaksiLab::where('pendaftaran_id', $pendaftaran->id)->delete();
            }
        }

        // 5. Seed Jasa Medis Lab (Laporan Insentif Lab) for 2026-10-01 matching Screenshot
        $glukosaSewaktu = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0208'],
            ['nama_pemeriksaan' => 'Glukosa Sewaktu', 'golongan_lab' => 'Kimia Klinik', 'kategori' => 'Kimia Klinik', 'kelompok_lab' => 'Tunggal', 'unitcost' => 25000, 'is_active' => true]
        );
        $proteinUrine = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0205'],
            ['nama_pemeriksaan' => 'Protein Urine', 'golongan_lab' => 'Urinalisis', 'kategori' => 'Urinalisis', 'kelompok_lab' => 'Tunggal', 'unitcost' => 20000, 'is_active' => true]
        );
        $pmsLab = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0206'],
            ['nama_pemeriksaan' => 'PMS (HIV, Syphilis, HBsAg, HCV)', 'golongan_lab' => 'Imunoserologi', 'kategori' => 'Imunoserologi', 'kelompok_lab' => 'Paket', 'unitcost' => 0, 'is_active' => true]
        );
        $cholesterol = MasterLab::updateOrCreate(
            ['kode_lab' => 'TLAB0207'],
            ['nama_pemeriksaan' => 'Cholesterol (Rapid Test)', 'golongan_lab' => 'Kimia Klinik', 'kategori' => 'Kimia Klinik', 'kelompok_lab' => 'Tunggal', 'unitcost' => 35000, 'is_active' => true]
        );

        $dokterSyaiful = Pegawai::where('nama_pegawai', 'like', '%Syaiful%')->first() ?: $dokterFahmi;
        $dokterResi = Pegawai::where('nama_pegawai', 'like', '%RESI%')->first() ?: $dokterFahmi;

        $insentifPatients = [
            [
                'no_reg' => 'RG26100100181', 'no_rm' => '2026-000023', 'nama' => 'NY MILA KARMILA',
                'jk' => 'P', 'tgl_lahir' => '1990-05-10', 'penjamin' => $penjPribadi, 'poli' => $poliLab,
                'dokter' => $dokterFahmi,
                'tanggal' => '2026-10-01',
                'items' => [
                    ['lab' => $glukosaSewaktu, 'tarif' => 25000, 'jasa' => 0],
                    ['lab' => $hb, 'tarif' => 20000, 'jasa' => 0],
                    ['lab' => $goldar, 'tarif' => 25000, 'jasa' => 0],
                    ['lab' => $proteinUrine, 'tarif' => 20000, 'jasa' => 0],
                    ['lab' => $pmsLab, 'tarif' => 0, 'jasa' => 0],
                ]
            ],
            [
                'no_reg' => 'RG26100100109', 'no_rm' => 'A2024-001612', 'nama' => 'AN MUHAMMAD FADIL ALFARIZQI',
                'jk' => 'L', 'tgl_lahir' => '2020-02-14', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'dokter' => $dokterFahmi,
                'tanggal' => '2026-10-01',
                'items' => [
                    ['lab' => $darahRutin, 'tarif' => 65000, 'jasa' => 0],
                ]
            ],
            [
                'no_reg' => 'RG26100100212', 'no_rm' => 'N2023-000289', 'nama' => 'NY MIA MAYANG',
                'jk' => 'P', 'tgl_lahir' => '1988-11-20', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'dokter' => $dokterSyaiful,
                'tanggal' => '2026-10-01',
                'items' => [
                    ['lab' => $cholesterol, 'tarif' => 35000, 'jasa' => 0],
                ]
            ],
            [
                'no_reg' => 'RG26100100142', 'no_rm' => 'N2026-001322', 'nama' => 'NY RINA',
                'jk' => 'P', 'tgl_lahir' => '1995-04-12', 'penjamin' => $penjBpjs, 'poli' => $poliUmum,
                'dokter' => $dokterResi,
                'tanggal' => '2026-10-01',
                'items' => [
                    ['lab' => $hb, 'tarif' => 20000, 'jasa' => 0],
                ]
            ],
        ];

        foreach ($insentifPatients as $ip) {
            $pasien = Pasien::updateOrCreate(
                ['no_rm' => $ip['no_rm']],
                [
                    'nama_pasien'   => $ip['nama'],
                    'jenis_kelamin' => $ip['jk'],
                    'tanggal_lahir' => $ip['tgl_lahir'],
                    'is_active'     => true,
                ]
            );

            $pendaftaran = Pendaftaran::updateOrCreate(
                ['nomor_registrasi' => $ip['no_reg']],
                [
                    'no_rawat'        => $ip['no_reg'],
                    'pasien_id'       => $pasien->id,
                    'tanggal_daftar'  => $ip['tanggal'],
                    'jam_daftar'      => '08:00:00',
                    'poliklinik_id'   => $ip['poli']->id,
                    'dokter_id'       => $ip['dokter'] ? $ip['dokter']->id : null,
                    'penjamin_id'     => $ip['penjamin'] ? $ip['penjamin']->id : null,
                    'status_periksa'  => 'Selesai',
                    'jenis_kunjungan' => 'Kunjungan Sakit',
                ]
            );

            TransaksiLab::where('pendaftaran_id', $pendaftaran->id)->delete();
            foreach ($ip['items'] as $itemIndex => $it) {
                TransaksiLab::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'master_lab_id'  => $it['lab']->id,
                    'tarif'          => $it['tarif'],
                    'qty'            => 1,
                    'subtotal'       => $it['tarif'],
                    'status'         => 'Selesai',
                    'keterangan'     => 'Jasa Medis: ' . $it['jasa'],
                    'created_at'     => \Carbon\Carbon::parse($ip['tanggal'] . ' 08:00:00')->addMinutes($itemIndex * 2),
                    'updated_at'     => \Carbon\Carbon::parse($ip['tanggal'] . ' 08:00:00')->addMinutes($itemIndex * 2),
                ]);
            }
        }
    }
}
