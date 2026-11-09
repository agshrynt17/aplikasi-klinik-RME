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
        Schema::create('master_diagnosas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_diagnosa', 20)->unique(); // Misal kode ICD-10
            $table->text('nama_diagnosa');                 // Nama penyakit / diagnosa
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_diagnosas');
    }
};
