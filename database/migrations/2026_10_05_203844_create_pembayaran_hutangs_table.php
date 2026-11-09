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
        Schema::create('pembayaran_hutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hutang_id')->constrained('hutangs')->cascadeOnDelete();
            $table->string('nomor_bukti', 100)->nullable();
            $table->date('tanggal_bayar');
            $table->decimal('nominal_bayar', 15, 2);
            $table->string('metode_pembayaran', 50)->default('Transfer');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutangs');
    }
};
