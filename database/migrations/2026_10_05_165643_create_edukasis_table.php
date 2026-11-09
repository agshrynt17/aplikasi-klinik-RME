<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edukasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            $table->foreignId('edukator_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Pegawai yang memberikan edukasi
            
            $table->dateTime('waktu_edukasi');
            $table->text('materi_edukasi')->nullable(); // Materi yang disampaikan
            $table->string('metode_edukasi', 100)->nullable(); // Lisan, Tertulis/Leaflet, Demonstrasi
            $table->string('penerima_edukasi', 100)->nullable(); // Pasien, Keluarga Pasien
            $table->text('evaluasi_pemahaman')->nullable(); // Paham, Re-edukasi, dll.
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edukasis');
    }
};
