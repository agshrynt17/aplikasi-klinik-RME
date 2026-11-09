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
        Schema::create('batch_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
            $table->string('nomor_batch', 100);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->integer('stok')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_barangs');
    }
};
