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
        Schema::create('master_radiologis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rad', 20)->unique();
            $table->string('kategori', 100)->nullable(); // e.g. Rontgen, USG, CT Scan, MRI
            $table->string('nama_pemeriksaan', 200);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_radiologis');
    }
};
