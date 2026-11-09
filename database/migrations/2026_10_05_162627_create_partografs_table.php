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
        Schema::create('partografs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Penolong persalinan / Bidan
            
            $table->dateTime('waktu_mulas')->nullable();
            $table->dateTime('waktu_ketuban_pecah')->nullable();
            
            $table->string('gpa', 50)->nullable(); // G.. P.. A.. (Gravida, Partus, Abortus)
            $table->string('usia_kehamilan', 50)->nullable();
            
            // JSON format for observational graph data
            // Structure e.g. [{"waktu": "10:30", "djj": 140, "pembukaan": 4, "nadi": 80, ...}]
            $table->json('observasi')->nullable();
            
            // Catatan akhir persalinan (optional)
            $table->text('catatan_persalinan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partografs');
    }
};
