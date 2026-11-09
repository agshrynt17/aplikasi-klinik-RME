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
        Schema::create('order_pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_pembelian_id')->constrained('order_pembelians')->cascadeOnDelete();
            $table->foreignId('master_barang_id')->constrained('master_barangs');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pembelian_details');
    }
};
