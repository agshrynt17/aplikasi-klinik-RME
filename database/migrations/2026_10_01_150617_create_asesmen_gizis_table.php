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
        Schema::create('asesmen_gizis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->dateTime('tanggal_asesmen');
            
            // Asesmen (A)
            $table->text('antropometri')->nullable();
            $table->text('biokimia')->nullable();
            $table->text('klinis_fisik')->nullable();
            $table->text('riwayat_makan')->nullable();
            $table->text('riwayat_personal')->nullable();
            
            // Diagnosis, Intervensi, Monev (DIME)
            $table->text('diagnosis_gizi')->nullable();
            $table->text('intervensi_gizi')->nullable();
            $table->text('monitoring_evaluasi')->nullable();
            
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Ahli Gizi
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmen_gizis');
    }
};
