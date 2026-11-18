<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use App\Models\StokGudang;

class StokBarangKonhakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudangFarmasi = MasterGudang::firstOrCreate(
            ['nama_gudang' => 'Gudang Farmasi'],
            ['kode_gudang' => 'GUD-001', 'lokasi' => 'Lantai 1 - Instalasi Farmasi', 'is_active' => true]
        );

        $items = [
            [
                'kode_barang'   => 'ANHEM-000006',
                'nama_barang'   => 'NARMIG 25MG',
                'jenis'         => 'Alat Kesehatan',
                'kelas_terapi'  => 'Anti Hemorrhoid',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -41,
            ],
            [
                'kode_barang'   => 'ALKES-000003',
                'nama_barang'   => 'ADMINISTRASI',
                'jenis'         => 'Alat Tulis Kantor (ATK)',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Pcs',
                'golongan_obat' => 'Bebas',
                'stok'          => 0,
            ],
            [
                'kode_barang'   => 'SKINC-000001',
                'nama_barang'   => 'ADMINISTRASI',
                'jenis'         => 'Alat Tulis Kantor (ATK)',
                'kelas_terapi'  => 'Skincare',
                'kemasan'       => 'Pcs',
                'golongan_obat' => 'Bebas',
                'stok'          => 0,
            ],
            [
                'kode_barang'   => 'BRO25-000001',
                'nama_barang'   => 'BRONKOSETIN TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -240,
            ],
            [
                'kode_barang'   => 'CLO26-000001',
                'nama_barang'   => 'CLONIDIN TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -90,
            ],
            [
                'kode_barang'   => 'GAL25-000001',
                'nama_barang'   => 'GALFLUX TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => 0,
            ],
            [
                'kode_barang'   => 'GPZ25-000001',
                'nama_barang'   => 'GPZ TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -126,
            ],
            [
                'kode_barang'   => 'MAG25-000001',
                'nama_barang'   => 'MAGTEN TABLET',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -796,
            ],
            [
                'kode_barang'   => 'SET25-000001',
                'nama_barang'   => 'SETRALINE TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => '-',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -233,
            ],
            [
                'kode_barang'   => 'ALKES-000001',
                'nama_barang'   => 'AERONIDE',
                'jenis'         => 'Obat',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Inhaler',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -7,
            ],
            [
                'kode_barang'   => 'ALKES-000004',
                'nama_barang'   => 'AILIN EYE DROP',
                'jenis'         => 'Obat',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Botol',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -209,
            ],
            [
                'kode_barang'   => 'ALKES-000005',
                'nama_barang'   => 'CACO3',
                'jenis'         => 'Obat',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Bebas',
                'stok'          => -78,
            ],
            [
                'kode_barang'   => 'ALKES-000006',
                'nama_barang'   => 'METROX TAB',
                'jenis'         => 'Obat',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => 0,
            ],
            [
                'kode_barang'   => 'ALKES-000002',
                'nama_barang'   => 'TAGYT',
                'jenis'         => 'Obat',
                'kelas_terapi'  => 'Alat Kesehatan',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'stok'          => -219,
            ],
        ];

        foreach ($items as $item) {
            $barang = MasterBarang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                [
                    'nama_barang'   => $item['nama_barang'],
                    'jenis'         => $item['jenis'],
                    'kelas_terapi'  => $item['kelas_terapi'],
                    'kemasan'       => $item['kemasan'],
                    'golongan_obat' => $item['golongan_obat'],
                    'stok'          => $item['stok'],
                    'is_active'     => true,
                ]
            );

            StokGudang::updateOrCreate(
                [
                    'master_gudang_id' => $gudangFarmasi->id,
                    'master_barang_id' => $barang->id,
                ],
                [
                    'stok'      => $item['stok'],
                    'stok_awal' => $item['stok'] > 0 ? $item['stok'] : 0,
                ]
            );
        }
    }
}
