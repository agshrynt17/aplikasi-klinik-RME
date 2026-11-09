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
        Schema::create('surat_keterangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter pembuat surat
            
            $table->string('jenis_surat', 100)->nullable(); // Surat Sakit, Surat Sehat, dll
            $table->string('nomor_surat', 100)->nullable();
            $table->date('tanggal_surat')->nullable();
            
            $table->date('mulai_tanggal')->nullable();
            $table->date('sampai_tanggal')->nullable();
            
            $table->text('keterangan')->nullable(); // Keterangan diagnosis / alasan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keterangans');
    }
};
