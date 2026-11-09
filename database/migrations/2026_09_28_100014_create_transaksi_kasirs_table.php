<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_kasirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->string('no_kwitansi', 50)->unique();
            $table->datetime('waktu_bayar');
            $table->integer('total_tindakan')->default(0);
            $table->integer('total_obat')->default(0);
            $table->integer('diskon')->default(0);
            $table->integer('total_tagihan')->default(0); // (tindakan + obat) - diskon
            $table->integer('uang_diterima')->default(0);
            $table->integer('kembalian')->default(0);
            $table->foreignId('akun_pembayaran_id')->nullable()->constrained('akun_pembayarans')->onDelete('set null');
            $table->string('status_pembayaran', 20)->default('Lunas'); // Lunas, Batal
            $table->foreignId('kasir_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_kasirs');
    }
};
