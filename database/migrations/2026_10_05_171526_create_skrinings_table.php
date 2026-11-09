<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skrinings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            // Nyeri
            $table->string('skrining_nyeri')->nullable(); // Ya, Tidak
            $table->integer('skor_nyeri')->nullable(); // 0-10
            
            // Resiko Jatuh
            $table->string('skrining_jatuh')->nullable(); // Ya, Tidak
            $table->string('resiko_jatuh')->nullable(); // Rendah, Sedang, Tinggi
            
            // Gizi
            $table->string('skrining_gizi')->nullable(); // Baik, Buruk, Beresiko
            
            // Alergi
            $table->string('status_alergi')->nullable(); // Ya, Tidak, Tidak Tahu
            $table->text('detail_alergi')->nullable(); // Detail obat/makanan
            
            // Kesimpulan
            $table->text('kesimpulan_skrining')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skrinings');
    }
};
