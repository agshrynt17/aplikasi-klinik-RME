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
        Schema::create('satusehat_lokasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ruangan_id')->nullable();
            $table->string('nama_tipe')->default('Room');
            $table->string('nama_ruangan_satusehat');
            $table->string('location_id_satusehat')->nullable();
            $table->string('referensi_ruangan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('telp')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->string('status')->default('Aktif');
            $table->text('alamat')->nullable();
            $table->string('rt')->default('0');
            $table->string('rw')->default('0');
            $table->string('provinsi')->default('JAWA BARAT');
            $table->string('kabupaten')->default('KABUPATEN KARAWANG');
            $table->string('kecamatan')->default('CIKAMPEK');
            $table->string('desa')->default('CIKAMPEK KOTA');
            $table->string('kode_pos')->default('41373');
            $table->string('longitude')->default('0');
            $table->string('latitude')->default('0');
            $table->string('altitude')->default('0');
            $table->dateTime('diubah_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satusehat_lokasis');
    }
};
