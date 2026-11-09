<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mcus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            // Pengukuran Antropometri & TTV
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->string('tekanan_darah', 20)->nullable();
            $table->integer('nadi')->nullable();
            $table->decimal('suhu', 4, 1)->nullable();
            $table->integer('respirasi')->nullable();
            
            // Pemeriksaan Khusus MCU
            $table->string('buta_warna', 50)->nullable(); // Normal, Parsial, Total
            $table->string('golongan_darah', 10)->nullable(); // A, B, AB, O
            $table->string('visus_mata_kanan', 20)->nullable(); // 6/6
            $table->string('visus_mata_kiri', 20)->nullable(); // 6/6
            
            // Pemeriksaan Fisik Umum
            $table->text('pemeriksaan_fisik')->nullable(); // Kepala, Leher, Dada, Perut, Ekstremitas
            
            // Kesimpulan
            $table->text('kesimpulan')->nullable(); // Sehat, Sehat dengan catatan, Tidak sehat
            
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mcus');
    }
};
