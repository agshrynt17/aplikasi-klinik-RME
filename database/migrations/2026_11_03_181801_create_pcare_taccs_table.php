<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_taccs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tacc', 20)->unique();
            $table->string('nama_tacc');
            $table->text('alasan_tacc')->nullable();
            $table->timestamps();
        });

        // Seed 5 data TACC standar PCare BPJS sesuai KONHAKES 1:1
        DB::table('pcare_taccs')->insert([
            [
                'kode_tacc'   => '-1',
                'nama_tacc'   => 'Tanpa TACC',
                'alasan_tacc' => '[""]',
                'created_at'  => '2022-11-10 09:35:10',
                'updated_at'  => '2022-11-10 09:35:10',
            ],
            [
                'kode_tacc'   => '1',
                'nama_tacc'   => 'Time',
                'alasan_tacc' => '["< 3 Hari",">= 3 - 7 Hari",">= 7 Hari"]',
                'created_at'  => '2022-11-10 09:38:02',
                'updated_at'  => '2022-11-10 09:38:02',
            ],
            [
                'kode_tacc'   => '2',
                'nama_tacc'   => 'Age',
                'alasan_tacc' => '["< 1 Bulan",">= 1 Bulan s/d < 12 Bulan",">= 1 Tahun s/d < 5 Tahun",">= 5 Tahun s/d < 12 Tahun",">= 12 Tahun s/d < 55 Tahun",">= 55 Tahun"]',
                'created_at'  => '2022-11-10 09:39:49',
                'updated_at'  => '2022-11-10 09:39:49',
            ],
            [
                'kode_tacc'   => '3',
                'nama_tacc'   => 'Complication',
                'alasan_tacc' => '["(format : kdDiagnosa + \" - \" + NamaDiagnosa, contoh : \"A09 - Diarrhoea and gastroenteritis \")"]',
                'created_at'  => '2022-11-10 09:40:54',
                'updated_at'  => '2022-11-10 09:40:54',
            ],
            [
                'kode_tacc'   => '4',
                'nama_tacc'   => 'Comorbidity',
                'alasan_tacc' => '["< 3 Hari",">= 3 - 7 Hari",">= 7 Hari"]',
                'created_at'  => '2022-11-10 09:41:46',
                'updated_at'  => '2022-11-10 09:41:46',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_taccs');
    }
};
