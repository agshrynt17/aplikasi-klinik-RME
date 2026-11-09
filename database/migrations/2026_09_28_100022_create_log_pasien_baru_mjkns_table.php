<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_pasien_baru_mjkns', function (Blueprint $table) {
            $table->id();
            $table->string('no_kartu', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('nama_pasien')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status_import')->default(false); // Apakah sudah diimport ke Master Pasien lokal?
            $table->text('pesan_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pasien_baru_mjkns');
    }
};
