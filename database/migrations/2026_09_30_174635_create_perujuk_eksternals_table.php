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
        Schema::create('perujuk_eksternals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perujuk', 20)->unique();
            $table->string('nama_perujuk');                        // Nama dokter / klinik / RS
            $table->string('jenis_perujuk', 30)->default('Dokter'); // Dokter, Klinik, RS, Puskesmas, Lainnya
            $table->string('institusi')->nullable();               // Nama RS/Klinik tempat perujuk bertugas
            $table->string('spesialisasi', 100)->nullable();       // Khusus dokter: Sp. PD, Sp. A, dll
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perujuk_eksternals');
    }
};
