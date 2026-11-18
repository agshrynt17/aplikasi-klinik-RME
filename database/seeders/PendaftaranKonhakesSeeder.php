<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Pegawai;
use App\Models\Penjamin;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class PendaftaranKonhakesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Penjamin BPJS & Pribadi Ada
        $bpjs = Penjamin::firstOrCreate(
            ['kode_penjamin' => 'BPJS'],
            [
                'nama_penjamin' => 'BPJS Kesehatan',
                'jenis_penjamin' => 'BPJS',
                'is_active' => true,
            ]
        );

        $pribadi = Penjamin::firstOrCreate(
            ['kode_penjamin' => 'UMUM'],
            [
                'nama_penjamin' => 'Pribadi / Umum',
                'jenis_penjamin' => 'Umum',
                'is_active' => true,
            ]
        );

        // 2. Pastikan Poliklinik Sesuai Screenshot Ada
        $poliUmum = Poliklinik::firstOrCreate(
            ['nama_poli' => 'Klinik Umum 1'],
            ['kode_poli' => 'POLI-UMUM-1', 'is_active' => true]
        );
        $poliGigi = Poliklinik::firstOrCreate(
            ['nama_poli' => 'Klinik Gigi'],
            ['kode_poli' => 'POLI-GIGI', 'is_active' => true]
        );
        $poliLab = Poliklinik::firstOrCreate(
            ['nama_poli' => 'Laboratorium'],
            ['kode_poli' => 'POLI-LAB', 'is_active' => true, 'is_lab' => true]
        );
        $poliKia = Poliklinik::firstOrCreate(
            ['nama_poli' => 'KIA / Kebidanan'],
            ['kode_poli' => 'POLI-KIA', 'is_active' => true]
        );

        // 3. Pastikan Dokter & Perawat Sesuai Screenshot Ada
        $drFahmi = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'dr. Fahmi Rizal'],
            [
                'kode_pegawai' => 'DR-001',
                'fungsional_medis' => 'Dokter Umum',
                'jabatan' => 'Dokter Pelaksana',
                'is_active' => true,
            ]
        );

        $drVierta = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'dr. VIERTA AJI NUR YASIN'],
            [
                'kode_pegawai' => 'DR-002',
                'fungsional_medis' => 'Dokter Umum',
                'jabatan' => 'Dokter Penanggung Jawab',
                'is_active' => true,
            ]
        );

        $drgApriani = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'drg. APRIANI DEVY MEILITA'],
            [
                'kode_pegawai' => 'DRG-001',
                'fungsional_medis' => 'Dokter Gigi',
                'jabatan' => 'Dokter Gigi',
                'is_active' => true,
            ]
        );

        $perawatNiken = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'NIKEN WIJAYANTI A.md.kep'],
            [
                'kode_pegawai' => 'PRW-001',
                'fungsional_medis' => 'Perawat',
                'jabatan' => 'Perawat Pelaksana',
                'is_active' => true,
            ]
        );

        $bidanLala = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'LALA WIJAYANTI AMD.KEB'],
            [
                'kode_pegawai' => 'BDN-001',
                'fungsional_medis' => 'Bidan',
                'jabatan' => 'Bidan Pelaksana',
                'is_active' => true,
            ]
        );

        // 4. Data Pendaftaran Sesuai Screenshot 1 & 3
        $sampleData = [
            [
                'nomor_registrasi' => 'RG26101400116',
                'no_rm'            => 'N2026-000922',
                'nama_pasien'      => 'NY SUMIATI',
                'jenis_kelamin'    => 'P',
                'tanggal_lahir'    => '1973-01-16',
                'nik'              => '3215255601730002',
                'no_bpjs'          => '0001811692823',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliUmum->id,
                'dokter_id'        => $drVierta->id,
                'perawat_id'       => $bidanLala->id,
                'nama_perawat'     => 'LALA WIJAYANTI AMD.KEB',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'A093',
                'is_online'        => false,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Sudah Pelayanan',
                'status_satusehat' => 'In Progress',
                'jam_daftar'       => '14:35:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400115',
                'no_rm'            => 'R2026-000179',
                'nama_pasien'      => 'RAFA FIRHAN HAKIM',
                'jenis_kelamin'    => 'L',
                'tanggal_lahir'    => '2013-03-15',
                'nik'              => '3215251503130003',
                'no_bpjs'          => null,
                'penjamin_id'      => $pribadi->id,
                'poliklinik_id'    => $poliLab->id,
                'dokter_id'        => $drFahmi->id,
                'perawat_id'       => null,
                'nama_perawat'     => null,
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Tanpa Antrian',
                'no_antrian'       => null,
                'is_online'        => false,
                'status_antrol_bpjs'=> null,
                'status_pcare'     => null,
                'status_satusehat' => 'Belum Kirim',
                'jam_daftar'       => '14:30:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400114',
                'no_rm'            => 'A2023-001263',
                'nama_pasien'      => 'AN KARINA DESTYA DARYONO',
                'jenis_kelamin'    => 'P',
                'tanggal_lahir'    => '2019-09-27',
                'nik'              => '3215166709190002',
                'no_bpjs'          => '0002896186577',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliGigi->id,
                'dokter_id'        => $drgApriani->id,
                'perawat_id'       => $perawatNiken->id,
                'nama_perawat'     => 'NIKEN WIJAYANTI A.md.kep',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'BE002',
                'is_online'        => true,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Baru Kirim',
                'status_satusehat' => 'Belum Kirim',
                'jam_daftar'       => '14:25:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400113',
                'no_rm'            => 'N2025-001812',
                'nama_pasien'      => 'NY EPON NURAENI',
                'jenis_kelamin'    => 'P',
                'tanggal_lahir'    => '1971-05-08',
                'nik'              => '3215254105710001',
                'no_bpjs'          => '0003110548781',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliUmum->id,
                'dokter_id'        => $drVierta->id,
                'perawat_id'       => $perawatNiken->id,
                'nama_perawat'     => 'NIKEN WIJAYANTI A.md.kep',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'A092',
                'is_online'        => false,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Sudah Pelayanan',
                'status_satusehat' => 'In Progress',
                'jam_daftar'       => '14:15:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400112',
                'no_rm'            => 'R2026-000178',
                'nama_pasien'      => 'RIZKY PRATAMA PUTRA',
                'jenis_kelamin'    => 'L',
                'tanggal_lahir'    => '2014-01-04',
                'nik'              => '3275060401140001',
                'no_bpjs'          => null,
                'penjamin_id'      => $pribadi->id,
                'poliklinik_id'    => $poliLab->id,
                'dokter_id'        => $drFahmi->id,
                'perawat_id'       => null,
                'nama_perawat'     => null,
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Tanpa Antrian',
                'no_antrian'       => null,
                'is_online'        => false,
                'status_antrol_bpjs'=> null,
                'status_pcare'     => null,
                'status_satusehat' => 'Belum Kirim',
                'jam_daftar'       => '14:05:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400111',
                'no_rm'            => 'A2026-000851',
                'nama_pasien'      => 'AN ARSYLA HUMAIRA',
                'jenis_kelamin'    => 'P',
                'tanggal_lahir'    => '2026-04-27',
                'nik'              => '3215256704260001',
                'no_bpjs'          => '0003960516307',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliUmum->id,
                'dokter_id'        => $drVierta->id,
                'perawat_id'       => $perawatNiken->id,
                'nama_perawat'     => 'NIKEN WIJAYANTI A.md.kep',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'A090',
                'is_online'        => false,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Sudah Pelayanan',
                'status_satusehat' => 'Belum Kirim',
                'jam_daftar'       => '13:50:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400110',
                'no_rm'            => 'N2023-003105',
                'nama_pasien'      => 'NN HAFIZAH SALSABILA',
                'jenis_kelamin'    => 'P',
                'tanggal_lahir'    => '2006-01-20',
                'nik'              => '3215136001060003',
                'no_bpjs'          => '0001811692822',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliUmum->id,
                'dokter_id'        => $drVierta->id,
                'perawat_id'       => $perawatNiken->id,
                'nama_perawat'     => 'NIKEN WIJAYANTI A.md.kep',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Selesai',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'A089',
                'is_online'        => true,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Sudah Pelayanan',
                'status_satusehat' => 'Finished',
                'jam_daftar'       => '13:30:00',
            ],
            [
                'nomor_registrasi' => 'RG26101400109',
                'no_rm'            => 'S2023-000236',
                'nama_pasien'      => 'SDR TEGUH MUHAMAD NURHAKIM',
                'jenis_kelamin'    => 'L',
                'tanggal_lahir'    => '2005-01-07',
                'nik'              => '3215160701050004',
                'no_bpjs'          => '0001456656017',
                'penjamin_id'      => $bpjs->id,
                'poliklinik_id'    => $poliUmum->id,
                'dokter_id'        => $drVierta->id,
                'perawat_id'       => $perawatNiken->id,
                'nama_perawat'     => 'NIKEN WIJAYANTI A.md.kep',
                'ruang_pemeriksaan'=> 'Ruang Rawat 1',
                'status_periksa'   => 'Dalam Proses',
                'jenis_kunjungan'  => 'Kunjungan Sakit',
                'antrian_tipe'     => 'Dengan Antrian',
                'no_antrian'       => 'A088',
                'is_online'        => false,
                'status_antrol_bpjs'=> 'Hadir',
                'status_pcare'     => 'Sudah Pelayanan',
                'status_satusehat' => 'In Progress',
                'jam_daftar'       => '13:10:00',
            ],
        ];

        $today = '2026-10-14';

        foreach ($sampleData as $item) {
            $pasien = Pasien::firstOrCreate(
                ['no_rm' => $item['no_rm']],
                [
                    'nama_pasien'   => $item['nama_pasien'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'tanggal_lahir' => $item['tanggal_lahir'],
                    'nik'           => $item['nik'],
                    'no_bpjs'       => $item['no_bpjs'],
                    'alamat'        => 'Telagasari, Karawang',
                    'is_active'     => true,
                ]
            );

            Pendaftaran::updateOrCreate(
                ['no_rawat' => $item['nomor_registrasi']],
                [
                    'nomor_registrasi'   => $item['nomor_registrasi'],
                    'pasien_id'          => $pasien->id,
                    'tanggal_daftar'     => $today,
                    'jam_daftar'         => $item['jam_daftar'],
                    'poliklinik_id'      => $item['poliklinik_id'],
                    'dokter_id'          => $item['dokter_id'],
                    'perawat_id'         => $item['perawat_id'],
                    'nama_perawat'       => $item['nama_perawat'],
                    'ruang_pemeriksaan'  => $item['ruang_pemeriksaan'],
                    'penjamin_id'        => $item['penjamin_id'],
                    'status_periksa'     => $item['status_periksa'],
                    'jenis_kunjungan'    => $item['jenis_kunjungan'],
                    'antrian_tipe'       => $item['antrian_tipe'],
                    'no_antrian'         => $item['no_antrian'],
                    'is_online'          => $item['is_online'],
                    'status_antrol_bpjs' => $item['status_antrol_bpjs'],
                    'status_pcare'       => $item['status_pcare'],
                    'status_satusehat'   => $item['status_satusehat'],
                    'skrining_visual'    => 'Pasien stabil',
                    'keluhan'            => 'Demam dan batuk pilek sejak 2 hari yang lalu',
                ]
            );
        }

        // 5. Antrian Menunggu (Screenshot 3)
        $antrianData = [
            [
                'no_antrian'        => 'BA001',
                'sumber_antrian'    => 'Booking',
                'penjamin_tipe'     => 'NON BPJS',
                'nama_pasien'       => 'NY HIKMATUS SARIAH YULIA WARDANI',
                'nik'               => '3215164307990002',
                'no_kis'            => null,
                'waktu_cetak'       => '14:21:02',
                'status_kirim_bpjs' => null,
            ],
            [
                'no_antrian'        => 'BA002',
                'sumber_antrian'    => 'Booking',
                'penjamin_tipe'     => 'NON BPJS',
                'nama_pasien'       => 'TN ALFIAN',
                'nik'               => '3215132712830002',
                'no_kis'            => null,
                'waktu_cetak'       => '14:21:28',
                'status_kirim_bpjs' => null,
            ],
            [
                'no_antrian'        => 'CA001',
                'sumber_antrian'    => 'Mobile JKN',
                'penjamin_tipe'     => 'BPJS',
                'nama_pasien'       => 'AN ANINDYA KHAIRUNISA',
                'nik'               => '3215255403210001',
                'no_kis'            => '0003980268369',
                'waktu_cetak'       => '15:43:51',
                'status_kirim_bpjs' => 'Terkirim',
            ],
        ];

        foreach ($antrianData as $idx => $ant) {
            $pas = Pasien::firstOrCreate(
                ['nik' => $ant['nik']],
                [
                    'no_rm'         => '2026-000' . (200 + $idx),
                    'nama_pasien'   => $ant['nama_pasien'],
                    'no_bpjs'       => $ant['no_kis'],
                    'jenis_kelamin' => str_contains($ant['nama_pasien'], 'NY') || str_contains($ant['nama_pasien'], 'AN') ? 'P' : 'L',
                    'alamat'        => 'Telagasari, Karawang',
                    'is_active'     => true,
                ]
            );

            // Antrian belum daftar periksa (status_periksa: Menunggu Antrian)
            Pendaftaran::updateOrCreate(
                ['no_rawat' => 'ANT-' . $today . '-' . $ant['no_antrian']],
                [
                    'nomor_registrasi'   => 'ANT-' . $ant['no_antrian'],
                    'pasien_id'          => $pas->id,
                    'tanggal_daftar'     => $today,
                    'jam_daftar'         => $ant['waktu_cetak'],
                    'waktu_cetak'        => $ant['waktu_cetak'],
                    'penjamin_id'        => $ant['penjamin_tipe'] === 'BPJS' ? $bpjs->id : $pribadi->id,
                    'status_periksa'     => 'Menunggu Antrian',
                    'status_antrian'     => 'Menunggu',
                    'sumber_antrian'     => $ant['sumber_antrian'],
                    'no_antrian'         => $ant['no_antrian'],
                    'status_antrol_bpjs' => $ant['status_kirim_bpjs'],
                ]
            );
        }
    }
}
