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
        Schema::create('diagnosa_keperawatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_diagnosa', 20)->unique(); // Kode SDKI
            $table->text('nama_diagnosa');                 // Nama Diagnosa Keperawatan
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
        Schema::dropIfExists('diagnosa_keperawatans');
    }
};
