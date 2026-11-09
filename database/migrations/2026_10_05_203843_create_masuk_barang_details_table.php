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
        Schema::create('masuk_barang_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masuk_barang_id')->constrained('masuk_barangs')->cascadeOnDelete();
            $table->foreignId('master_barang_id')->constrained('master_barangs');
            $table->integer('jumlah_terima');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->string('nomor_batch', 100)->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masuk_barang_details');
    }
};
