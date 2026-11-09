<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->string('nama_pj', 100);
            $table->string('hubungan_pj', 50);
            $table->string('no_hp_pj', 20)->nullable();
            $table->text('alamat_pj')->nullable();
            $table->longText('ttd_pj')->nullable(); // Signature base64
            $table->longText('ttd_petugas')->nullable(); // Signature base64
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_consents');
    }
};
