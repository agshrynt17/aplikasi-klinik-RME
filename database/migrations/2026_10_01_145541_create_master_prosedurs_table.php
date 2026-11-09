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
        Schema::create('master_prosedurs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prosedur', 20)->unique(); // Kode ICD-9 CM
            $table->text('nama_prosedur');                 // Nama Prosedur
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
        Schema::dropIfExists('master_prosedurs');
    }
};
