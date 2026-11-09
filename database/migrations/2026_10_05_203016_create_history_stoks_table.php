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
        Schema::create('history_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
            $table->string('jenis_penyesuaian', 50); // Penambahan / Pengurangan
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('keterangan')->nullable();
            $table->string('status', 50)->default('Berhasil'); // Berhasil / Dibatalkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_stoks');
    }
};
