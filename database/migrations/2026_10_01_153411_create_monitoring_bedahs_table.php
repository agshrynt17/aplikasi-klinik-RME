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
        Schema::create('monitoring_bedahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Anestesi / Bedah
            
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            
            $table->string('cairan_masuk', 100)->nullable();
            $table->string('cairan_keluar', 100)->nullable();
            
            // Kolom JSON untuk menyimpan data grid time series
            // Format: [{"waktu":"10:00", "tensi":"120/80", "nadi":"80", "suhu":"36.5", "rr":"20", "spo2":"98"}, ...]
            $table->json('observasi_json')->nullable();
            
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_bedahs');
    }
};
