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
        Schema::create('pra_bedahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Bedah
            
            $table->dateTime('waktu_pengkajian');
            $table->text('diagnosa_pra_bedah')->nullable();
            $table->text('rencana_tindakan')->nullable(); // Rencana Operasi
            $table->text('riwayat_penyakit')->nullable(); // Asma, DM, HT, dll
            
            $table->string('persiapan_darah', 50)->nullable(); // Misal: 2 Kantong PRC, Tidak ada
            $table->string('puasa_mulai', 50)->nullable(); // Misal: Jam 22:00
            
            // Tanda Vital & Penunjang
            $table->string('tensi', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('rr', 20)->nullable();
            $table->text('pemeriksaan_penunjang')->nullable(); // Lab, Rad, EKG
            
            $table->string('resiko_operasi', 50)->nullable(); // Rendah, Sedang, Tinggi
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pra_bedahs');
    }
};
