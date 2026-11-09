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
        Schema::create('penjualan_apotek_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_apotek_id')->constrained('penjualan_apoteks')->cascadeOnDelete();
            $table->foreignId('master_barang_id')->constrained('master_barangs');
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_apotek_details');
    }
};
