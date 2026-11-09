<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            // Subjektif
            $table->date('tanggal_persalinan')->nullable();
            $table->string('hari_ke', 50)->nullable();
            $table->text('keluhan_utama')->nullable();
            
            // Objektif
            $table->string('tekanan_darah', 20)->nullable();
            $table->integer('nadi')->nullable();
            $table->integer('respirasi')->nullable();
            $table->decimal('suhu', 4, 1)->nullable();
            
            // Pemeriksaan Fisik PNC
            $table->string('payudara_laktasi', 100)->nullable(); // Normal, bengkak, asi kurang, dll
            $table->string('tfu', 50)->nullable(); // Tinggi Fundus Uteri
            $table->string('kontraksi_uterus', 100)->nullable(); // Keras/Lembek
            $table->string('lochea', 100)->nullable(); // Rubra, Sanguinolenta, Serosa, Alba
            $table->string('perineum', 100)->nullable(); // Utuh, Luka episiotomi, Tanda infeksi
            $table->string('kandung_kemih', 100)->nullable();
            
            // Assessment & Plan
            $table->text('masalah_diagnosa')->nullable();
            $table->text('tatalaksana')->nullable();
            $table->text('edukasi')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pncs');
    }
};
