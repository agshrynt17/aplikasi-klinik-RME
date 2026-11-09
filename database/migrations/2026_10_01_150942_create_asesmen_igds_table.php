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
        Schema::create('asesmen_igds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->dateTime('tanggal_asesmen');
            
            // Triage (Airway, Breathing, Circulation, Disability)
            $table->string('skala_triage', 20)->nullable(); // Misal: Merah, Kuning, Hijau, Hitam
            $table->string('jalan_napas', 50)->nullable(); // Bebas, Tersumbat
            $table->string('pernapasan', 50)->nullable(); // Normal, Sesak
            $table->string('sirkulasi', 50)->nullable(); // Normal, Pucat, Sianosis
            $table->string('kesadaran', 50)->nullable(); // Compos Mentis, Apatis, dll
            
            // Anamnesa & Vital Sign
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            
            $table->string('tensi', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('rr', 20)->nullable();
            
            // Fisik, Diagnosis & Plan
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('tindakan_rencana')->nullable();
            
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Jaga
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmen_igds');
    }
};
