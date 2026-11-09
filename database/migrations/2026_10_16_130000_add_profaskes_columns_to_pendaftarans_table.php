<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'nomor_registrasi')) {
                $table->string('nomor_registrasi', 30)->nullable()->after('no_rawat');
            }
            if (!Schema::hasColumn('pendaftarans', 'skrining_visual')) {
                $table->string('skrining_visual', 100)->default('Pasien stabil')->after('status_periksa');
            }
            if (!Schema::hasColumn('pendaftarans', 'jenis_rawat')) {
                $table->string('jenis_rawat', 50)->default('Rawat Jalan')->after('skrining_visual');
            }
            if (!Schema::hasColumn('pendaftarans', 'is_kirim_pcare')) {
                $table->boolean('is_kirim_pcare')->default(false)->after('jenis_rawat');
            }
            if (!Schema::hasColumn('pendaftarans', 'is_kirim_satusehat')) {
                $table->boolean('is_kirim_satusehat')->default(false)->after('is_kirim_pcare');
            }
            if (!Schema::hasColumn('pendaftarans', 'antrian_tipe')) {
                $table->string('antrian_tipe', 30)->default('Dengan Antrian')->after('is_kirim_satusehat');
            }
            if (!Schema::hasColumn('pendaftarans', 'no_antrian')) {
                $table->string('no_antrian', 20)->nullable()->after('antrian_tipe');
            }
            if (!Schema::hasColumn('pendaftarans', 'tpa_id')) {
                $table->foreignId('tpa_id')->nullable()->after('penjamin_id');
            }
            if (!Schema::hasColumn('pendaftarans', 'no_bpjs_asuransi')) {
                $table->string('no_bpjs_asuransi', 50)->nullable()->after('tpa_id');
            }
            if (!Schema::hasColumn('pendaftarans', 'jenis_kunjungan')) {
                $table->string('jenis_kunjungan', 50)->default('Kunjungan Sakit')->after('no_bpjs_asuransi');
            }
            if (!Schema::hasColumn('pendaftarans', 'ruang_pemeriksaan')) {
                $table->string('ruang_pemeriksaan', 50)->nullable()->default('Ruang Rawat 1')->after('poliklinik_id');
            }
            if (!Schema::hasColumn('pendaftarans', 'perawat_id')) {
                $table->foreignId('perawat_id')->nullable()->after('dokter_id');
            }
            if (!Schema::hasColumn('pendaftarans', 'nama_perawat')) {
                $table->string('nama_perawat', 100)->nullable()->after('perawat_id');
            }
            if (!Schema::hasColumn('pendaftarans', 'is_prb')) {
                $table->boolean('is_prb')->default(false)->after('nama_perawat');
            }
            if (!Schema::hasColumn('pendaftarans', 'is_online')) {
                $table->boolean('is_online')->default(false)->after('is_prb');
            }
            if (!Schema::hasColumn('pendaftarans', 'is_exclude_kasir')) {
                $table->boolean('is_exclude_kasir')->default(false)->after('is_online');
            }
            if (!Schema::hasColumn('pendaftarans', 'status_antrol_bpjs')) {
                $table->string('status_antrol_bpjs', 30)->nullable()->default('Hadir')->after('status_periksa');
            }
            if (!Schema::hasColumn('pendaftarans', 'status_pcare')) {
                $table->string('status_pcare', 50)->nullable()->default('Baru Kirim')->after('status_antrol_bpjs');
            }
            if (!Schema::hasColumn('pendaftarans', 'status_satusehat')) {
                $table->string('status_satusehat', 50)->nullable()->default('Belum Kirim')->after('status_pcare');
            }
            if (!Schema::hasColumn('pendaftarans', 'sumber_antrian')) {
                $table->string('sumber_antrian', 30)->nullable()->default('Loket')->after('status_satusehat');
            }
            if (!Schema::hasColumn('pendaftarans', 'waktu_cetak')) {
                $table->time('waktu_cetak')->nullable()->after('sumber_antrian');
            }
            if (!Schema::hasColumn('pendaftarans', 'waktu_panggil')) {
                $table->time('waktu_panggil')->nullable()->after('waktu_cetak');
            }
            if (!Schema::hasColumn('pendaftarans', 'status_antrian')) {
                $table->string('status_antrian', 30)->nullable()->default('Menunggu')->after('waktu_panggil');
            }
        });
    }

    public function down(): void
    {
        // down not required
    }
};
