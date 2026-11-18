<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterGudang;

class MasterGudangKonhakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudangs = [
            [
                'kode_gudang' => '001',
                'nama_gudang' => 'Gudang Farmasi',
                'lokasi'      => 'Instalasi Farmasi',
                'keterangan'  => 'Gudang Utama Farmasi',
            ],
            [
                'kode_gudang' => '002',
                'nama_gudang' => 'GUDANG AWAL',
                'lokasi'      => 'Logistik Medik',
                'keterangan'  => 'Gudang Awal Penerimaan',
            ],
        ];

        foreach ($gudangs as $item) {
            MasterGudang::firstOrCreate(
                ['kode_gudang' => $item['kode_gudang']],
                $item
            );
        }
    }
}
