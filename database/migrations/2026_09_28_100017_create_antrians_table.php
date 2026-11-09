<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_antrian_id')->constrained('master_antrians')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('nomor_antrian'); // 1, 2, 3
            $table->string('kode_nomor', 20); // A-001
            $table->foreignId('pasien_id')->nullable()->constrained('pasiens')->onDelete('set null');
            $table->string('status', 30)->default('Menunggu'); // Menunggu, Dipanggil, Selesai, Batal
            $table->integer('panggilan_ke')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
