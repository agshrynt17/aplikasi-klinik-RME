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
        Schema::create('triases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Petugas Triase
            
            $table->dateTime('waktu_triase');
            $table->string('cara_datang', 100)->nullable();
            $table->string('asal_rujukan', 100)->nullable();
            
            // Primary Survey
            $table->string('jalan_napas', 50)->nullable();
            $table->string('pernapasan', 50)->nullable();
            $table->string('sirkulasi', 50)->nullable();
            $table->string('kesadaran', 50)->nullable();
            
            $table->text('keluhan_utama')->nullable();
            $table->string('kategori_triase', 50); // ATS 1 s.d. ATS 5
            
            // Vital Signs
            $table->string('tensi', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('rr', 20)->nullable();
            $table->string('spo2', 20)->nullable();
            $table->string('gcs', 20)->nullable();
            
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triases');
    }
};
