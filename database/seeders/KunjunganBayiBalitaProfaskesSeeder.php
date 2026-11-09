<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\AsesmenAwalRajal;
use App\Models\Pegawai;
use App\Models\Poliklinik;
use App\Models\Penjamin;
use Carbon\Carbon;

class KunjunganBayiBalitaProfaskesSeeder extends Seeder
{
    public function run(): void
    {
        $bpjs = Penjamin::firstOrCreate(
            ['kode_penjamin' => 'BPJS'],
            ['nama_penjamin' => 'BPJS Kesehatan', 'jenis_penjamin' => 'BPJS', 'is_active' => true]
        );

        $poliUmum = Poliklinik::firstOrCreate(
            ['nama_poli' => 'Klinik Umum 1'],
            ['kode_poli' => 'POLI-UMUM-1', 'is_active' => true]
        );

        $drFahmi = Pegawai::firstOrCreate(
            ['nama_pegawai' => 'dr. Fahmi Rizal'],
            [
                'kode_pegawai' => 'DR-001',
                'fungsional_medis' => 'Dokter Umum',
                'jabatan' => 'Dokter Pelaksana',
                'is_active' => true
            ]
        );

        $bayiData = [
            [
                'nik' => '3215252105220001',
                'nama' => 'AN KAZU HAIDAR SARFARAZ',
                'tgl_lahir' => '2022-05-21',
                'jk' => 'L',
                'ibu' => 'NY NUR',
                'alamat' => 'PERUM MUTIARA GARDEN, CIKAMPEK UTARA, Kec. KOTABARU, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Utara',
                'kecamatan' => 'Kotabaru',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R50.9-Fever, unspecified',
                'no_reg' => 'RG2610010001',
                'jam' => '08:15:00',
            ],
            [
                'nik' => '3215136907230002',
                'nama' => 'AN ALIFA ALMAHYRA',
                'tgl_lahir' => '2023-07-29',
                'jk' => 'P',
                'ibu' => 'NY ALFI',
                'alamat' => 'KP SUKAJAD , CIKAMPEK BARAT, Kec. CIKAMPEK, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Barat',
                'kecamatan' => 'Cikampek',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'J00-Common Cold (Acute nasopharyngitis)',
                'no_reg' => 'RG2610010002',
                'jam' => '08:30:00',
            ],
            [
                'nik' => '3215252811230001',
                'nama' => 'AN RYUGA RIPALA ABI SHEVA',
                'tgl_lahir' => '2023-11-28',
                'jk' => 'L',
                'ibu' => 'NY REVA MUTIARA',
                'alamat' => 'PERUM GRIYA PERMAI, CIKAMPEK UTARA, Kec. KOTABARU, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Utara',
                'kecamatan' => 'Kotabaru',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R05-Cough',
                'no_reg' => 'RG2610010003',
                'jam' => '08:45:00',
            ],
            [
                'nik' => '3215135911240002',
                'nama' => 'AN HANA ZEA FATIMAH',
                'tgl_lahir' => '2024-11-19',
                'jk' => 'P',
                'ibu' => 'NY MURYATI',
                'alamat' => 'PAWARENGAN, CIKAMPEK BARAT, Kec. CIKAMPEK, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Barat',
                'kecamatan' => 'Cikampek',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R50.9-Fever, unspecified',
                'no_reg' => 'RG2610010004',
                'jam' => '09:00:00',
            ],
            [
                'nik' => '3215250909220001',
                'nama' => 'AN RAKA RIVANO AL ZAFRAN',
                'tgl_lahir' => '2022-09-09',
                'jk' => 'L',
                'ibu' => 'NY YUNI',
                'alamat' => 'P GRIYA PUSPITA ASRI B3 NO 25, WANCIMEKAR, Kec. KOTABARU, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Wancimekar',
                'kecamatan' => 'Kotabaru',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R05-Cough',
                'no_reg' => 'RG2610010005',
                'jam' => '09:15:00',
            ],
            [
                'nik' => '3403011112230001',
                'nama' => 'DEVANKA MALIK ALVARENDRA',
                'tgl_lahir' => '2023-12-11',
                'jk' => 'L',
                'ibu' => 'imah',
                'alamat' => 'p bip blok db 13 no 9, CIKAMPEK BARAT, Kec. CIKAMPEK, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Barat',
                'kecamatan' => 'Cikampek',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => "H10-Conjunctivitis\nJ30.4-Allergic rhinitis, unspecified",
                'no_reg' => 'RG2610010006',
                'jam' => '09:30:00',
            ],
            [
                'nik' => '3216587410029852',
                'nama' => 'AN SHAREEN JEMIMA AZZAIRA',
                'tgl_lahir' => '2024-01-20',
                'jk' => 'P',
                'ibu' => 'NY',
                'alamat' => 'KP SUKAMANAH, CIKAMPEK UTARA, Kec. KOTABARU, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Cikampek Utara',
                'kecamatan' => 'Kotabaru',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R50.9-Fever, unspecified',
                'no_reg' => 'RG2610010007',
                'jam' => '09:45:00',
            ],
            [
                'nik' => '3215251912250004',
                'nama' => 'BY MUHAMMAD ZAVIAN',
                'tgl_lahir' => '2025-12-19',
                'jk' => 'L',
                'ibu' => 'NY',
                'alamat' => 'PUCUNG, WANCIMEKAR, Kec. KOTABARU, Kab/Kota. KABUPATEN KARAWANG, JAWA BARAT',
                'desa' => 'Wancimekar',
                'kecamatan' => 'Kotabaru',
                'kabupaten' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'diagnosa' => 'R50.9-Fever, unspecified',
                'no_reg' => 'RG2610010008',
                'jam' => '10:00:00',
            ],
        ];

        foreach ($bayiData as $idx => $b) {
            $pasien = Pasien::updateOrCreate(
                ['nik' => $b['nik']],
                [
                    'no_rm' => '2026-0009' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'nama_pasien' => $b['nama'],
                    'tempat_lahir' => 'Karawang',
                    'tanggal_lahir' => $b['tgl_lahir'],
                    'jenis_kelamin' => $b['jk'],
                    'nama_ibu_kandung' => $b['ibu'],
                    'alamat' => $b['alamat'],
                    'desa' => $b['desa'],
                    'kecamatan' => $b['kecamatan'],
                    'kabupaten' => $b['kabupaten'],
                    'provinsi' => $b['provinsi'],
                    'is_active' => true,
                ]
            );

            $pendaftaran = Pendaftaran::updateOrCreate(
                ['nomor_registrasi' => $b['no_reg']],
                [
                    'no_rawat' => $b['no_reg'],
                    'pasien_id' => $pasien->id,
                    'penjamin_id' => $bpjs->id,
                    'poliklinik_id' => $poliUmum->id,
                    'dokter_id' => $drFahmi->id,
                    'tanggal_daftar' => '2026-10-01 00:00:00',
                    'jam_daftar' => $b['jam'],
                    'status_periksa' => 'Selesai',
                    'jenis_kunjungan' => 'Kunjungan Sakit',
                    'antrian_tipe' => 'Dengan Antrian',
                    'no_antrian' => 'A' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT),
                ]
            );

            AsesmenAwalRajal::updateOrCreate(
                ['pendaftaran_id' => $pendaftaran->id],
                [
                    'pasien_id' => $pasien->id,
                    'tanggal_asesmen' => '2026-10-01',
                    'dokter_id' => $drFahmi->id,
                    'diagnosis' => $b['diagnosa'],
                    'keluhan_utama' => explode("\n", $b['diagnosa'])[0],
                ]
            );
        }
    }
}
