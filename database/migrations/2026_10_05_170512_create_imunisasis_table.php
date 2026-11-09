<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imunisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            $table->date('tanggal_imunisasi');
            $table->string('jenis_imunisasi'); // BCG, Polio, DPT, HB, Campak, dll
            $table->string('batch_vaksin', 100)->nullable();
            $table->string('dosis', 50)->nullable(); // Misal 0.5 ml
            $table->string('lokasi_suntikan', 100)->nullable(); // Paha Kiri, Paha Kanan, Lengan
            $table->foreignId('pemberi_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->text('keluhan_pasca_imunisasi')->nullable(); // Demam, Bengkak, dll (KIPI)
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imunisasis');
    }
};
