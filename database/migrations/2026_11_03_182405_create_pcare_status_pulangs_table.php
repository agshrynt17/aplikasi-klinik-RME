<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_status_pulangs', function (Blueprint $table) {
            $table->id();
            $table->string('kd_status_pulang', 20)->unique();
            $table->string('nm_status_pulang');
            $table->string('status_pulang_pcare')->nullable();
            $table->boolean('is_rawat_inap')->default(false);
            $table->boolean('is_rawat_jalan')->default(false);
            $table->string('status_pulang_aplikasi')->nullable();
            $table->timestamps();
        });

        // Seed 7 data status pulang standar PCare BPJS sesuai PROFASKES 1:1
        DB::table('pcare_status_pulangs')->insert([
            [
                'kd_status_pulang'       => '0',
                'nm_status_pulang'       => 'Sembuh',
                'status_pulang_pcare'    => 'Sembuh',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => false,
                'status_pulang_aplikasi' => 'Sembuh',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '1',
                'nm_status_pulang'       => 'Meninggal',
                'status_pulang_pcare'    => 'Meninggal',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => true,
                'status_pulang_aplikasi' => 'Meninggal',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '2',
                'nm_status_pulang'       => 'Pulang Paksa',
                'status_pulang_pcare'    => 'Pulang Paksa',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => false,
                'status_pulang_aplikasi' => 'Pulang Paksa',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '3',
                'nm_status_pulang'       => 'Berobat Jalan',
                'status_pulang_pcare'    => 'Berobat Jalan',
                'is_rawat_inap'          => false,
                'is_rawat_jalan'         => true,
                'status_pulang_aplikasi' => 'Berobat Jalan',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '4',
                'nm_status_pulang'       => 'Rujuk Vertikal',
                'status_pulang_pcare'    => 'Rujuk Vertikal',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => true,
                'status_pulang_aplikasi' => 'Rujuk Vertikal',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '5',
                'nm_status_pulang'       => 'Rujuk Horizontal',
                'status_pulang_pcare'    => 'Rujuk Horizontal',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => true,
                'status_pulang_aplikasi' => 'Rujuk Horizontal',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
            [
                'kd_status_pulang'       => '9',
                'nm_status_pulang'       => 'Lain-lain',
                'status_pulang_pcare'    => 'Lain-lain',
                'is_rawat_inap'          => true,
                'is_rawat_jalan'         => false,
                'status_pulang_aplikasi' => 'Lain-Lain',
                'created_at'             => '2024-08-09 11:43:26',
                'updated_at'             => '2024-08-09 11:43:26',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_status_pulangs');
    }
};
