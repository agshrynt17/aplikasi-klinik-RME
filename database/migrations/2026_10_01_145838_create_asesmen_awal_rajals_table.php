<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asesmen_awal_rajals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->dateTime('tanggal_asesmen');
            
            // Subjektif
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->text('riwayat_penyakit_dahulu')->nullable();
            $table->string('riwayat_alergi')->nullable();
            
            // Objektif
            $table->string('tensi', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('rr', 20)->nullable();
            $table->string('berat_badan', 20)->nullable();
            $table->string('tinggi_badan', 20)->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            
            // Asesmen & Plan
            $table->text('diagnosis')->nullable();
            $table->text('tindakan_rencana')->nullable();
            
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmen_awal_rajals');
    }
};
