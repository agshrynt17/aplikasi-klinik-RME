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
        Schema::create('ancs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Kandungan / Bidan
            
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->string('usia_kehamilan', 50)->nullable();
            $table->string('gpa', 50)->nullable(); // G.. P.. A..
            
            $table->string('tensi', 20)->nullable();
            $table->decimal('bb', 5, 2)->nullable();
            $table->decimal('tb', 5, 2)->nullable();
            $table->decimal('lila', 5, 2)->nullable();
            
            $table->string('tfu', 50)->nullable();
            $table->string('djj', 50)->nullable();
            $table->string('letak_janin', 50)->nullable();
            $table->string('imunisasi_tt', 50)->nullable();
            
            $table->text('keluhan')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ancs');
    }
};
