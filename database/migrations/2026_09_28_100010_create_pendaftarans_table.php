<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 30)->unique(); // Contoh: RJ-20231024-001
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->date('tanggal_daftar');
            $table->time('jam_daftar')->nullable();
            $table->foreignId('poliklinik_id')->nullable()->constrained('polikliniks')->onDelete('set null');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->foreignId('penjamin_id')->nullable()->constrained('penjamins')->onDelete('set null');
            $table->string('status_periksa', 30)->default('Belum Diperiksa'); // Belum Diperiksa, Sedang Diperiksa, Selesai, Batal
            $table->text('keluhan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
