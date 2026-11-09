<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_antrian_mjkns', function (Blueprint $table) {
            $table->id();
            $table->string('nomorkartu', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('nohp', 20)->nullable();
            $table->string('kodepoli', 20)->nullable();
            $table->date('tanggalperiksa')->nullable();
            $table->string('kodedokter', 50)->nullable();
            $table->string('nomorantrean', 20)->nullable();
            $table->string('angkaantrean', 10)->nullable();
            $table->integer('estimasidilayani')->nullable(); // timestamp unix
            $table->boolean('status_sinkronisasi')->default(false);
            $table->text('pesan_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_antrian_mjkns');
    }
};
