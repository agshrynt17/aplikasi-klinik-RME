<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_tindakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('tindakan_id')->constrained('tindakans')->onDelete('cascade');
            $table->foreignId('pelaksana_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter / Perawat
            $table->integer('tarif')->default(0);
            $table->integer('qty')->default(1);
            $table->integer('subtotal')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_tindakans');
    }
};
