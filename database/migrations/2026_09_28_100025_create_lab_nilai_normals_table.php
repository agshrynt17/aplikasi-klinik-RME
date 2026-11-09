<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_nilai_normals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_lab_id');
            $table->string('jenis_kelamin', 20)->default('Umum'); // Laki-laki / Perempuan / Umum
            $table->string('kategori_umur', 30)->default('Umum'); // Dewasa / Anak / Bayi / Umum
            $table->string('nilai_min', 50)->nullable();
            $table->string('nilai_max', 50)->nullable();
            $table->string('nilai_teks', 100)->nullable(); // Jika bukan rentang angka (misal: "Negatif")
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('master_lab_id')->references('id')->on('master_labs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_nilai_normals');
    }
};
