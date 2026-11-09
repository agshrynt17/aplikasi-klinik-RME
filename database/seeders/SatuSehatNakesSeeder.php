<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class SatuSehatNakesSeeder extends Seeder
{
    public function run(): void
    {
        $nakesData = [
            [
                'nik' => '1307074511930001',
                'nama_pegawai' => 'RESI ANGRAINI, A.md Keb',
                'nama_satusehat' => 'RESI ANGRAINI',
                'tanggal_lahir' => '1993-11-05',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10013998756',
            ],
            [
                'nik' => '1871021101890003',
                'nama_pegawai' => 'Apt.Fariddon Ricky Wahyudi, S.Farm',
                'nama_satusehat' => 'FA*** RI*** WA***',
                'tanggal_lahir' => '1989-01-11',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'L',
                'kode_ihs_satusehat' => '10007149571',
            ],
            [
                'nik' => '1971041103870003',
                'nama_pegawai' => 'dr. FARISI',
                'nama_satusehat' => 'FARISI',
                'tanggal_lahir' => '1987-03-11',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'L',
                'kode_ihs_satusehat' => '10005292968',
            ],
            [
                'nik' => '3207365903960004',
                'nama_pegawai' => 'dr. Sheila Asyiah Granitya',
                'nama_satusehat' => 'SHEILA ASYIAH GRANITYA',
                'tanggal_lahir' => '1996-03-19',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10028603571',
            ],
            [
                'nik' => '3213054209880004',
                'nama_pegawai' => 'SITI MAEMUNAH, A.md Keb',
                'nama_satusehat' => 'SITI MAEMUNAH',
                'tanggal_lahir' => '1988-09-02',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10009437293',
            ],
            [
                'nik' => '3213240112940001',
                'nama_pegawai' => 'dr. Harry Putra Pratama',
                'nama_satusehat' => 'HARRY PUTRA PRATAMA',
                'tanggal_lahir' => '1995-01-08',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'L',
                'kode_ihs_satusehat' => '11129013282',
            ],
            [
                'nik' => '3215130508880001',
                'nama_pegawai' => 'dr. Fahmi Rizal',
                'nama_satusehat' => 'FAHMI RIZAL',
                'tanggal_lahir' => '1988-08-05',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'L',
                'kode_ihs_satusehat' => '10005476657',
            ],
            [
                'nik' => '3215135105940004',
                'nama_pegawai' => 'Dr Rani Puspita',
                'nama_satusehat' => 'RANI PUSPITA',
                'tanggal_lahir' => '1994-05-11',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10033029491',
            ],
            [
                'nik' => '3215135409960001',
                'nama_pegawai' => 'NOVIA GANTINI, A.md Keb',
                'nama_satusehat' => 'NOVIA GANTINI',
                'tanggal_lahir' => '1996-09-14',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '11131765400',
            ],
            [
                'nik' => '3215135405970001',
                'nama_pegawai' => 'PUTRI IRIANTI',
                'nama_satusehat' => null,
                'tanggal_lahir' => '1997-09-14',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => null,
            ],
            [
                'nik' => '3215135711850006',
                'nama_pegawai' => 'drg. Tia Novianti',
                'nama_satusehat' => 'TIA NOVIANTI',
                'tanggal_lahir' => '1985-11-17',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10002131893',
            ],
            [
                'nik' => '3215135803850001',
                'nama_pegawai' => 'LALA WIJAYANTI AMD.KEB',
                'nama_satusehat' => 'LA*** WI***',
                'tanggal_lahir' => '1986-02-18',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '12704884526',
            ],
            [
                'nik' => '3215164601950001',
                'nama_pegawai' => 'LUSI FITRIYANI',
                'nama_satusehat' => 'LUSI FITRIYANI',
                'tanggal_lahir' => '1995-01-06',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '11129907982',
            ],
            [
                'nik' => '3215166611960001',
                'nama_pegawai' => 'KURNIAWATI DEWI S.Farm',
                'nama_satusehat' => 'KURNIAWATI DEWI',
                'tanggal_lahir' => '1996-11-06',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10010218534',
            ],
            [
                'nik' => '3215250508840005',
                'nama_pegawai' => 'dr. Syaiful Bachtiar',
                'nama_satusehat' => 'SYAIFUL BACHTIAR',
                'tanggal_lahir' => '1984-08-05',
                'fungsional_medis' => 'DOKTER',
                'jenis_kelamin' => 'L',
                'kode_ihs_satusehat' => '10002324924',
            ],
            [
                'nik' => '3215254106960005',
                'nama_pegawai' => 'EVI LUTHFI SAMRATUL FUADAH A.Md.Keb',
                'nama_satusehat' => null,
                'tanggal_lahir' => '1996-09-13',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => null,
            ],
            [
                'nik' => '3215255001960002',
                'nama_pegawai' => 'SISKA SRI REJEKI Amd,Keb',
                'nama_satusehat' => 'SISKA SRI REJEKI',
                'tanggal_lahir' => '1996-01-10',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10033177687',
            ],
            [
                'nik' => '3215255008840015',
                'nama_pegawai' => 'NIKEN WIJAYANTI A.md.keb',
                'nama_satusehat' => 'NIKEN WIJAYANTI',
                'tanggal_lahir' => '1984-08-10',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10006814036',
            ],
            [
                'nik' => '3215016008980002',
                'nama_pegawai' => 'LESTARI PUJI RAHAYU A.md.Ak',
                'nama_satusehat' => 'LESTARI PUJI RAHAYU',
                'tanggal_lahir' => '1998-08-20',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10008432190',
            ],
            [
                'nik' => '3329026110800020',
                'nama_pegawai' => 'NUR AENI OKTAVIA A md kep',
                'nama_satusehat' => 'NUR AENI OKTAVIA',
                'tanggal_lahir' => '1980-10-21',
                'fungsional_medis' => 'TENAGA KESEHATAN',
                'jenis_kelamin' => 'P',
                'kode_ihs_satusehat' => '10009124451',
            ],
        ];

        foreach ($nakesData as $idx => $item) {
            $pegawai = Pegawai::where('nik', $item['nik'])->first();
            if ($pegawai) {
                $pegawai->update([
                    'nama_pegawai' => $item['nama_pegawai'],
                    'nama_satusehat' => $item['nama_satusehat'],
                    'tanggal_lahir' => $item['tanggal_lahir'],
                    'fungsional_medis' => $item['fungsional_medis'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'kode_ihs_satusehat' => $item['kode_ihs_satusehat'],
                    'is_active' => true,
                ]);
            } else {
                $nextNum = Pegawai::max('id') + 1 + $idx;
                Pegawai::create([
                    'kode_pegawai' => 'PEG-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT),
                    'nik' => $item['nik'],
                    'nama_pegawai' => $item['nama_pegawai'],
                    'nama_satusehat' => $item['nama_satusehat'],
                    'tanggal_lahir' => $item['tanggal_lahir'],
                    'fungsional_medis' => $item['fungsional_medis'],
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'kode_ihs_satusehat' => $item['kode_ihs_satusehat'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
