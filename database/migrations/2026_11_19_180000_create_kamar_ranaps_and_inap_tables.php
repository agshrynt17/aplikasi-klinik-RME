<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kamar_ranaps')) {
            Schema::create('kamar_ranaps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->nullOnDelete();
                $table->string('nama_kamar', 100);
                $table->string('nomor_bed', 50);
                $table->string('kelas', 50)->default('Kelas 2'); // VIP, Kelas 1, Kelas 2, Kelas 3, Isolasi
                $table->decimal('tarif_per_hari', 15, 2)->default(200000);
                $table->string('status', 30)->default('Tersedia'); // Tersedia, Terisi, Pembersihan, Perbaikan
                $table->foreignId('pendaftaran_id')->nullable()->constrained('pendaftarans')->nullOnDelete();
                $table->dateTime('waktu_masuk')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('rawat_inap_transfers')) {
            Schema::create('rawat_inap_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->cascadeOnDelete();
                $table->foreignId('kamar_ranap_id')->constrained('kamar_ranaps')->cascadeOnDelete();
                $table->dateTime('waktu_masuk');
                $table->dateTime('waktu_keluar')->nullable();
                $table->integer('lama_hari')->default(1);
                $table->decimal('tarif_per_hari', 15, 2)->default(0);
                $table->decimal('total_biaya_kamar', 15, 2)->default(0);
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rawat_inap_transfers');
        Schema::dropIfExists('kamar_ranaps');
    }
};

