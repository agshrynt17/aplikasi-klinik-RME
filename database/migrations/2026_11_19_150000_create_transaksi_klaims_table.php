<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transaksi_klaims')) {
            Schema::create('transaksi_klaims', function (Blueprint $table) {
                $table->id();
                $table->string('status_klaim', 30)->default('Draft'); // Draft, Diajukan, Selesai, etc.
                $table->date('tgl_klaim');
                $table->string('no_klaim', 50)->unique();
                $table->foreignId('penjamin_id')->nullable()->constrained('penjamins')->onDelete('set null');
                $table->string('nama_penjamin', 100)->nullable();
                $table->date('tgl_transaksi_awal')->nullable();
                $table->date('tgl_transaksi_akhir')->nullable();
                $table->integer('jumlah_hari')->default(0);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('diskon', 15, 2)->default(0);
                $table->decimal('total_tagihan', 15, 2)->default(0);
                $table->decimal('total_realisasi', 15, 2)->default(0);
                $table->decimal('selisih', 15, 2)->default(0);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_klaims');
    }
};

