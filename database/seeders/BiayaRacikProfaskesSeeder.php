<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjamin;
use App\Models\BiayaRacik;

class BiayaRacikProfaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjamins = Penjamin::all();
        $defaultKomponen = [
            'Biaya racik puyer',
            'Biaya racik krim',
            'Biaya racik lotion',
            'Biaya racik kapsul',
        ];

        foreach ($penjamins as $p) {
            foreach ($defaultKomponen as $k) {
                BiayaRacik::firstOrCreate(
                    [
                        'penjamin_id' => $p->id,
                        'nama_tarif'  => $k,
                    ],
                    [
                        'biaya'       => 0,
                    ]
                );
            }
        }
    }
}
