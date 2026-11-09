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
        Schema::create('ohis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Gigi
            
            // JSON detail gigi 16, 11, 26, 36, 31, 46 -> { "16": {"di": 0, "ci": 1}, ... }
            $table->json('skor_gigi_json')->nullable();
            
            $table->decimal('di_s', 4, 2)->nullable();
            $table->decimal('ci_s', 4, 2)->nullable();
            $table->decimal('ohi_s', 4, 2)->nullable();
            
            $table->string('kriteria', 20)->nullable(); // Baik, Sedang, Buruk
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ohis');
    }
};
