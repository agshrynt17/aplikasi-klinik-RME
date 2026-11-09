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
        Schema::create('incs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('pegawais')->onDelete('set null'); // Penolong persalinan / Bidan / Dokter
            
            // Kala I - IV
            $table->text('catatan_kala_1')->nullable();
            $table->text('catatan_kala_2')->nullable();
            $table->text('catatan_kala_3')->nullable();
            $table->text('catatan_kala_4')->nullable();
            
            // Data Kelahiran
            $table->dateTime('waktu_lahir')->nullable();
            $table->string('jenis_persalinan', 100)->nullable(); // Normal, SC, Vakum, dll
            
            // Data Bayi
            $table->string('jenis_kelamin_bayi', 20)->nullable(); // Laki-laki / Perempuan
            $table->decimal('berat_badan_bayi', 5, 2)->nullable(); // gram atau kg
            $table->decimal('panjang_badan_bayi', 5, 2)->nullable(); // cm
            $table->string('apgar_score', 20)->nullable();
            $table->text('keadaan_bayi')->nullable();
            
            // Komplikasi / Tindakan
            $table->text('komplikasi')->nullable();
            $table->text('tindakan_resusitasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incs');
    }
};
