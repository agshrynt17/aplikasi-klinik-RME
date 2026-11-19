<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_prognosas', function (Blueprint $table) {
            $table->id();
            $table->string('kd_prognosa', 10);
            $table->string('nm_prognosa', 255);
            $table->string('kd_prognosa_reff', 255)->nullable();
            $table->timestamps();
        });

        $data = [
            [
                'kd_prognosa'      => '01',
                'nm_prognosa'      => 'Sanam (Sembuh)',
                'kd_prognosa_reff' => null,
            ],
            [
                'kd_prognosa'      => '02',
                'nm_prognosa'      => 'Bonam (Baik)',
                'kd_prognosa_reff' => null,
            ],
            [
                'kd_prognosa'      => '03',
                'nm_prognosa'      => 'Malam (Buruk/Jelek)',
                'kd_prognosa_reff' => null,
            ],
            [
                'kd_prognosa'      => '04',
                'nm_prognosa'      => 'Dubia Ad Sanam/Bonam (Tidak tentu/Ragu-ragu, Cenderung Sembuh/Baik)',
                'kd_prognosa_reff' => null,
            ],
            [
                'kd_prognosa'      => '05',
                'nm_prognosa'      => 'Dubia Ad Malam (Tidak tentu/Ragu-ragu, Cenderung Sembuh/Baik)',
                'kd_prognosa_reff' => null,
            ],
        ];

        foreach ($data as $row) {
            DB::table('pcare_prognosas')->insert(array_merge($row, [
                'created_at' => '2024-08-07 15:10:21',
                'updated_at' => '2024-08-07 15:10:21',
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_prognosas');
    }
};

