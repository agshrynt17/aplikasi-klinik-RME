<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_kesadarans', function (Blueprint $table) {
            $table->id();
            $table->string('kd_sadar', 20)->unique();
            $table->string('nm_sadar');
            $table->string('kd_sadar_reff')->nullable();
            $table->timestamps();
        });

        // Seed 4 data kesadaran standar PCare BPJS sesuai PROFASKES 1:1
        DB::table('pcare_kesadarans')->insert([
            [
                'kd_sadar'      => '01',
                'nm_sadar'      => 'Compos mentis',
                'kd_sadar_reff' => 'Sadar baik',
                'created_at'    => '2024-01-25 10:49:51',
                'updated_at'    => '2024-01-25 10:49:51',
            ],
            [
                'kd_sadar'      => '02',
                'nm_sadar'      => 'Somnolence',
                'kd_sadar_reff' => 'Berespon dengan kata-kata',
                'created_at'    => '2024-01-25 10:49:51',
                'updated_at'    => '2024-01-25 10:49:51',
            ],
            [
                'kd_sadar'      => '03',
                'nm_sadar'      => 'Sopor',
                'kd_sadar_reff' => 'Berespon jika dirangsang nyeri',
                'created_at'    => '2024-01-25 10:49:51',
                'updated_at'    => '2024-01-25 10:49:51',
            ],
            [
                'kd_sadar'      => '04',
                'nm_sadar'      => 'Coma',
                'kd_sadar_reff' => 'Pasien tidak sadar',
                'created_at'    => '2024-01-25 10:49:51',
                'updated_at'    => '2024-01-25 10:49:51',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_kesadarans');
    }
};
