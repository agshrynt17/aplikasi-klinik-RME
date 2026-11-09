<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_pelayanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            
            $table->string('nama_dokumen'); // Misal: Hasil Lab Parahita, Surat Rujukan RS, Persetujuan Tindakan
            $table->string('jenis_dokumen'); // Hasil Lab, Radiologi, Surat Rujukan, Berkas External, Lain-lain
            $table->string('file_path'); // Path file upload di storage
            $table->text('keterangan')->nullable();
            
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pelayanans');
    }
};
