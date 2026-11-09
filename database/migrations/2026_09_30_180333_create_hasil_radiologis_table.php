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
        Schema::create('hasil_radiologis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_radiologi_id')->constrained('transaksi_radiologis')->onDelete('cascade');
            $table->longText('hasil_ekspertise')->nullable(); // Kesimpulan/bacaan rontgen dll
            $table->string('file_gambar')->nullable(); // Path untuk gambar radiologi
            $table->foreignId('dokter_pemeriksa_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Radiolog
            $table->timestamp('waktu_verifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_radiologis');
    }
};
