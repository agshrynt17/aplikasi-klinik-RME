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
        Schema::create('hasil_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_lab_id')->constrained('transaksi_labs')->onDelete('cascade');
            $table->string('nilai_hasil', 100)->nullable();        // Nilai aktual hasil pemeriksaan
            $table->string('satuan', 30)->nullable();              // Satuan (mg/dL, g/dL, dll)
            $table->string('nilai_normal_text', 150)->nullable();  // Rentang normal (disalin dari master)
            $table->string('flag', 20)->default('Normal');         // Normal, Tinggi, Rendah, Abnormal
            $table->text('catatan')->nullable();                   // Catatan analis
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_labs');
    }
};
