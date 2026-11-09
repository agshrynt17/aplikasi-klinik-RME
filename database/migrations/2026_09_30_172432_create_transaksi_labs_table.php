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
        Schema::create('transaksi_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('master_lab_id')->constrained('master_labs')->onDelete('cascade');
            $table->foreignId('analis_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Petugas lab / analis
            $table->integer('tarif')->default(0);
            $table->integer('qty')->default(1);
            $table->integer('subtotal')->default(0);
            $table->string('status', 30)->default('Pending'); // Pending, Proses, Selesai
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_labs');
    }
};
