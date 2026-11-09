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
        Schema::table('reseps', function (Blueprint $table) {
            if (!Schema::hasColumn('reseps', 'pendaftaran_id')) {
                $table->foreignId('pendaftaran_id')->nullable()->after('id')->constrained('pendaftarans')->nullOnDelete();
            }
            if (!Schema::hasColumn('reseps', 'antrian_nomor')) {
                $table->string('antrian_nomor', 50)->nullable()->after('nomor_resep');
            }
            if (!Schema::hasColumn('reseps', 'status_antrian')) {
                $table->string('status_antrian', 50)->default('Menunggu')->after('antrian_nomor');
            }
            if (!Schema::hasColumn('reseps', 'status_proses')) {
                $table->string('status_proses', 50)->default('Belum Diproses')->after('status');
            }
            if (!Schema::hasColumn('reseps', 'waktu_proses')) {
                $table->timestamp('waktu_proses')->nullable()->after('status_proses');
            }
            if (!Schema::hasColumn('reseps', 'status_penyerahan')) {
                $table->string('status_penyerahan', 50)->default('Belum Diserahkan')->after('waktu_proses');
            }
            if (!Schema::hasColumn('reseps', 'waktu_penyerahan')) {
                $table->timestamp('waktu_penyerahan')->nullable()->after('status_penyerahan');
            }
            if (!Schema::hasColumn('reseps', 'waktu_tunggu')) {
                $table->string('waktu_tunggu', 50)->nullable()->default('0 Jam 0 Mnt')->after('waktu_penyerahan');
            }
            if (!Schema::hasColumn('reseps', 'petugas_farmasi')) {
                $table->string('petugas_farmasi', 100)->nullable()->after('waktu_tunggu');
            }
            if (!Schema::hasColumn('reseps', 'diagnosa')) {
                $table->string('diagnosa', 255)->nullable()->after('petugas_farmasi');
            }
            if (!Schema::hasColumn('reseps', 'informasi_tambahan')) {
                $table->text('informasi_tambahan')->nullable()->after('diagnosa');
            }
            if (!Schema::hasColumn('reseps', 'is_bhp')) {
                $table->boolean('is_bhp')->default(false)->after('informasi_tambahan');
            }
            if (!Schema::hasColumn('reseps', 'status_pcare')) {
                $table->string('status_pcare', 100)->default('Obat Belum Kirim')->after('is_bhp');
            }
            if (!Schema::hasColumn('reseps', 'status_satusehat')) {
                $table->string('status_satusehat', 100)->default('Belum Daftar')->after('status_pcare');
            }
            if (!Schema::hasColumn('reseps', 'status_kaji_resep')) {
                $table->string('status_kaji_resep', 100)->default('Belum Kaji')->after('status_satusehat');
            }
            if (!Schema::hasColumn('reseps', 'conseling_catatan')) {
                $table->text('conseling_catatan')->nullable()->after('status_kaji_resep');
            }
            if (!Schema::hasColumn('reseps', 'pio_catatan')) {
                $table->text('pio_catatan')->nullable()->after('conseling_catatan');
            }
            if (!Schema::hasColumn('reseps', 'penyerahan_oleh')) {
                $table->string('penyerahan_oleh', 100)->nullable()->after('pio_catatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reseps', function (Blueprint $table) {
            $table->dropColumn([
                'pendaftaran_id',
                'antrian_nomor',
                'status_antrian',
                'status_proses',
                'waktu_proses',
                'status_penyerahan',
                'waktu_penyerahan',
                'waktu_tunggu',
                'petugas_farmasi',
                'diagnosa',
                'informasi_tambahan',
                'is_bhp',
                'status_pcare',
                'status_satusehat',
                'status_kaji_resep',
                'conseling_catatan',
                'pio_catatan',
                'penyerahan_oleh',
            ]);
        });
    }
};
