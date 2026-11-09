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
        Schema::create('informed_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Edukator / Pelaksana Tindakan
            
            $table->string('jenis_tindakan');
            $table->dateTime('tanggal_persetujuan');
            $table->enum('status_persetujuan', ['Setuju', 'Tolak'])->default('Setuju');
            
            $table->string('pihak_menyatakan', 50)->default('Pasien'); // Pasien / Keluarga
            $table->string('nama_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            
            $table->text('keterangan')->nullable();
            $table->string('saksi_keluarga')->nullable();
            $table->string('saksi_rs')->nullable(); // Saksi pihak Rumah Sakit / Klinik
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informed_consents');
    }
};
