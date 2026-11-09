<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_antrians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_antrian', 5); // Contoh: A, B, P, K
            $table->string('nama_antrian', 100); // Contoh: Loket Pendaftaran 1, Poli Umum
            $table->string('jenis', 30); // Pendaftaran, Poliklinik, Kasir, Farmasi
            $table->foreignId('poliklinik_id')->nullable()->constrained('polikliniks')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_antrians');
    }
};
