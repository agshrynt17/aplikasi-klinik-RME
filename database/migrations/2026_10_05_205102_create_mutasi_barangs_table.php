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
        Schema::create('mutasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_mutasi', 50)->unique();
            $table->date('tanggal_mutasi');
            $table->string('unit_tujuan', 100);
            $table->string('status', 50)->default('Dikirim'); // Dikirim, Diterima, Dibatalkan
            $table->date('tanggal_terima')->nullable();
            $table->string('penerima')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_barangs');
    }
};
