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
        Schema::create('transaksi_radiologis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('master_radiologi_id')->constrained('master_radiologis')->onDelete('cascade');
            $table->foreignId('radiografer_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->enum('status', ['Pending', 'Proses', 'Selesai'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_radiologis');
    }
};
