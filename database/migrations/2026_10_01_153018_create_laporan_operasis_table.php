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
        Schema::create('laporan_operasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_operator_id')->nullable()->constrained('pegawais')->onDelete('set null');
            
            $table->string('asisten_operator', 100)->nullable();
            $table->string('perawat_instrumen', 100)->nullable();
            $table->string('dokter_anestesi_id')->nullable();
            $table->string('jenis_anestesi', 100)->nullable();
            
            $table->text('diagnosa_pra_bedah')->nullable();
            $table->text('diagnosa_pasca_bedah')->nullable();
            $table->string('tindakan_operasi', 200)->nullable();
            $table->string('jaringan_dieksisi', 200)->nullable();
            
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            
            $table->text('laporan_operasi')->nullable(); // Rincian operasi
            $table->text('instruksi_pasca_bedah')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_operasis');
    }
};
