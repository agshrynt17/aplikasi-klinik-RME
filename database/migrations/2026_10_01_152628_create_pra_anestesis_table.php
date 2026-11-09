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
        Schema::create('pra_anestesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('dokter_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Dokter Anestesi
            
            $table->dateTime('waktu_pengkajian');
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable(); // Penyakit sistemik
            $table->text('riwayat_operasi')->nullable();
            $table->text('riwayat_anestesi')->nullable();
            
            // Pemeriksaan Fisik & Vital Sign
            $table->string('tensi', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('rr', 20)->nullable();
            $table->string('tb', 20)->nullable();
            $table->string('bb', 20)->nullable();
            $table->string('jalan_napas', 100)->nullable(); // Mallampati, Gigi Palsu
            $table->text('pemeriksaan_fisik')->nullable(); // Jantung, Paru dll
            $table->text('pemeriksaan_penunjang')->nullable(); // Lab, EKG
            
            // Kesimpulan & Rencana
            $table->string('asa_score', 50)->nullable(); // ASA 1, 2, 3, 4, 5, E
            $table->string('rencana_anestesi', 100)->nullable(); // Umum, Regional, Lokal
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pra_anestesis');
    }
};
