<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satusehat_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_satusehat');
            $table->unsignedBigInteger('unit_layanan_id')->nullable();
            $table->string('unit_aplikasi');
            $table->string('location_id_satusehat')->nullable();
            $table->string('status')->default('Aktif');
            $table->dateTime('diubah_pada')->nullable();
            $table->timestamps();
        });

        // Seed record matching screenshot
        DB::table('satusehat_units')->insert([
            'unit_satusehat' => 'Klinik Telagasari 24 Jam',
            'unit_layanan_id' => 4,
            'unit_aplikasi' => 'Pelayanan',
            'location_id_satusehat' => '100064741',
            'status' => 'Aktif',
            'diubah_pada' => '2023-12-25 10:04:44',
            'created_at' => '2023-12-25 10:04:44',
            'updated_at' => '2023-12-25 10:04:44',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('satusehat_units');
    }
};

