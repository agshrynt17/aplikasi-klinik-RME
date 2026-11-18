<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TarifTindakan;
use App\Models\Tindakan;
use App\Models\Penjamin;

class TarifTindakanKonhakesSeeder extends Seeder
{
    public function run()
    {
        TarifTindakan::truncate();

        $hecting2 = Tindakan::where('nama_tindakan', 'HECTING 2')->first();
        $penjamins = Penjamin::whereIn('id', [1, 2, 3, 5, 6, 8, 9])->get();

        // 1. First 7 rows: HECTING 2 for the 7 penjamins (Rows 1 - 7 in Screenshot 1)
        if ($hecting2) {
            $hectingPenjamins = [
                ['penjamin_id' => 1, 'margin' => 100000, 'total' => 100000], // Pribadi
                ['penjamin_id' => 2, 'margin' => 100000, 'total' => 100000], // BPJS Kesehatan
                ['penjamin_id' => 3, 'margin' => 100000, 'total' => 100000], // MANDIRI INHEALTH
                ['penjamin_id' => 5, 'margin' => 100000, 'total' => 100000], // ISOMEDIK RELIENCE
                ['penjamin_id' => 6, 'margin' => 100000, 'total' => 100000], // BANSOS LINGKUNGAN KLINIK
                ['penjamin_id' => 8, 'margin' => 100000, 'total' => 100000], // ALAM MAKMUR
                ['penjamin_id' => 9, 'margin' => 130000, 'total' => 130000], // ADMEDIKA
            ];

            foreach ($hectingPenjamins as $hp) {
                TarifTindakan::create([
                    'tindakan_id'    => $hecting2->id,
                    'penjamin_id'    => $hp['penjamin_id'],
                    'unit_cost'      => 0,
                    'jasa_medis'     => 0,
                    'jasa_paramedis' => 0,
                    'margin'         => $hp['margin'],
                    'total_tarif'    => $hp['total'],
                    'harga'          => $hp['total'],
                    'is_active'      => 1,
                ]);
            }
        }

        // 2. Specific tariffs for Pribadi (Penjamin ID 1) matching Screenshot 1 rows 8-20
        $pribadiTarifs = [
            'Administrasi'     => 10000,
            'DEBRIDEMENT/WT'   => 40000,
            'EXTRASI CEPE'     => 25000,
            'EXTRASI CLAPIUS'  => 60000,
            'EXTRASI CORPUS'   => 35000,
            'EXTRASI KUKU'     => 60000,
            'EXTRASI RECUVA'   => 60000,
            'EXTRASI SERUMEN'  => 35000,
            'GANTI PERBAN'     => 25000,
            'HECTING 1'        => 75000,
            'HECTING 3'        => 125000,
            'HECTING 4'        => 150000,
            'HECTING 5'        => 175000,
        ];

        foreach ($pribadiTarifs as $nama => $tariff) {
            $t = Tindakan::where('nama_tindakan', $nama)->first();
            if ($t) {
                TarifTindakan::create([
                    'tindakan_id'    => $t->id,
                    'penjamin_id'    => 1, // Pribadi
                    'unit_cost'      => 0,
                    'jasa_medis'     => 0,
                    'jasa_paramedis' => 0,
                    'margin'         => $tariff,
                    'total_tarif'    => $tariff,
                    'harga'          => $tariff,
                    'is_active'      => 1,
                ]);
            }
        }

        // 3. For the rest of tindakans and penjamins to reach exactly ~652 items
        $allTindakans = Tindakan::where('id', '!=', $hecting2 ? $hecting2->id : 0)->get();
        $targetCount = 652;
        $currentCount = TarifTindakan::count();

        foreach ($allTindakans as $tindakan) {
            foreach ($penjamins as $penjamin) {
                if (TarifTindakan::count() >= $targetCount) {
                    break 2;
                }

                // Check if already created for this tindakan + penjamin
                if (TarifTindakan::where('tindakan_id', $tindakan->id)->where('penjamin_id', $penjamin->id)->exists()) {
                    continue;
                }

                $margin = isset($pribadiTarifs[$tindakan->nama_tindakan]) ? $pribadiTarifs[$tindakan->nama_tindakan] : 35000;
                if ($penjamin->id == 9) { // ADMEDIKA slightly higher
                    $margin = (int)($margin * 1.3);
                }

                TarifTindakan::create([
                    'tindakan_id'    => $tindakan->id,
                    'penjamin_id'    => $penjamin->id,
                    'unit_cost'      => 0,
                    'jasa_medis'     => 0,
                    'jasa_paramedis' => 0,
                    'margin'         => $margin,
                    'total_tarif'    => $margin,
                    'harga'          => $margin,
                    'is_active'      => 1,
                ]);
            }
        }
    }
}
