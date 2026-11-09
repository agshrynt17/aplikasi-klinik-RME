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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_resep', 50)->unique();
            $table->date('tanggal_resep');
            $table->foreignId('pasien_id')->constrained('pasiens');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais'); // Dokter
            $table->string('status', 50)->default('Menunggu'); // Menunggu, Dilayani, Dibatalkan
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
