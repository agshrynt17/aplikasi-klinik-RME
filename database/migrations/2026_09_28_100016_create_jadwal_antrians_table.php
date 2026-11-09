<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_antrian_id')->constrained('master_antrians')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->string('hari', 20); // Senin, Selasa, dst
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota')->default(0); // 0 = tak terbatas
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_antrians');
    }
};
