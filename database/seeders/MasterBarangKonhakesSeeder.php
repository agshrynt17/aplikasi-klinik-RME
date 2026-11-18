<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterBarang;

class MasterBarangKonhakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'kode_barang'   => 'ANFEK-000002',
                'nama_barang'   => 'AIPI 45\'S 22/12',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 4500,
                'minimum_stok'  => 1,
                'keterangan'    => null,
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-002828',
                'nama_barang'   => 'ABSOLUTE CHAMOMILE EXTRACT 14-',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 47600,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-001700',
                'nama_barang'   => 'ACARBOSE TAB 100MG',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 1911,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-001701',
                'nama_barang'   => 'ACARBOSE TAB 50MG',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 1185,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-000237',
                'nama_barang'   => 'ACC BREAST PUMP W-BTL DODO 26-',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 81485,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-002567',
                'nama_barang'   => 'ACC BREASTMILK BAG 150ML DODO',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 33552,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-002700',
                'nama_barang'   => 'ACETYLCYSTEINE CAP 200MG 28-10',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 565,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-002393',
                'nama_barang'   => 'ACIFAR CREAM 5 GR',
                'jenis'         => 'Obat',
                'kemasan'       => 'Tablet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 6311,
                'minimum_stok'  => 1,
                'keterangan'    => '0',
                'is_active'     => true,
            ],
            [
                'kode_barang'   => 'ANFEK-002871',
                'nama_barang'   => 'ACIFAR TAB 400MG',
                'jenis'         => 'Obat',
                'kemasan'       => 'Kaplet',
                'golongan_obat' => 'Obat Keras',
                'kelas_terapi'  => 'Antiinfeksi (Antibiotik/jamur/virus)',
                'hna'           => 1500,
                'minimum_stok'  => 1,
                'keterangan'    => 'ANTI VIRUS (HABISKAN)',
                'is_active'     => true,
            ],
        ];

        foreach ($barangs as $item) {
            MasterBarang::firstOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
        }
    }
}
