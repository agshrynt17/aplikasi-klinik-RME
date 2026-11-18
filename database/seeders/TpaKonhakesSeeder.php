<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tpa;

class TpaKonhakesSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            [
                'kode_tpa'  => '011',
                'nama_tpa'  => 'ADMEDIKA',
                'alamat'    => 'Jakarta',
                'telp'      => '021-22109090',
                'fax'       => '021-9902226',
                'is_active' => true,
            ],
            [
                'kode_tpa'  => '012',
                'nama_tpa'  => 'ISOMEDIK RELIANCE',
                'alamat'    => null,
                'telp'      => null,
                'fax'       => null,
                'is_active' => true,
            ],
            [
                'kode_tpa'  => '02',
                'nama_tpa'  => 'MANDIRI INHEALTH',
                'alamat'    => 'karawang',
                'telp'      => null,
                'fax'       => null,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $row) {
            Tpa::updateOrCreate(['kode_tpa' => $row['kode_tpa']], $row);
        }
    }
}
