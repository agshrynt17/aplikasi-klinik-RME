<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_labs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lab', 20)->unique();
            $table->string('kategori', 50)->nullable(); // Misal: Hematologi, Kimia Darah
            $table->string('nama_pemeriksaan');
            $table->string('satuan', 30)->nullable(); // Misal: mg/dL, g/dL
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_labs');
    }
};
