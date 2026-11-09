<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_kirim_antrians', function (Blueprint $table) {
            if (!Schema::hasColumn('log_kirim_antrians', 'no_antrian')) {
                $table->string('no_antrian', 30)->nullable()->after('kodebooking');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'sumber_antrian')) {
                $table->string('sumber_antrian', 50)->nullable()->after('no_antrian');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'no_registrasi')) {
                $table->string('no_registrasi', 50)->nullable()->after('sumber_antrian');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'tanggal_pendaftaran')) {
                $table->date('tanggal_pendaftaran')->nullable()->after('no_registrasi');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'nama_pasien')) {
                $table->string('nama_pasien', 150)->nullable()->after('tanggal_pendaftaran');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'no_rm')) {
                $table->string('no_rm', 50)->nullable()->after('nama_pasien');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'no_kartu')) {
                $table->string('no_kartu', 30)->nullable()->after('no_rm');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'nik')) {
                $table->string('nik', 30)->nullable()->after('no_kartu');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'nama_poli')) {
                $table->string('nama_poli', 100)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'nama_dokter')) {
                $table->string('nama_dokter', 150)->nullable()->after('nama_poli');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'jam_praktek')) {
                $table->string('jam_praktek', 50)->nullable()->default('06:00-22:00')->after('nama_dokter');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'status_kirim_antrian')) {
                $table->string('status_kirim_antrian', 50)->default('Terkirim')->after('jam_praktek');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'status_kehadiran')) {
                $table->string('status_kehadiran', 50)->default('Terkirim')->after('status_kirim_antrian');
            }
            if (!Schema::hasColumn('log_kirim_antrians', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('status_kehadiran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_kirim_antrians', function (Blueprint $table) {
            $table->dropColumn([
                'no_antrian', 'sumber_antrian', 'no_registrasi', 'tanggal_pendaftaran',
                'nama_pasien', 'no_rm', 'no_kartu', 'nik', 'nama_poli', 'nama_dokter',
                'jam_praktek', 'status_kirim_antrian', 'status_kehadiran', 'keterangan'
            ]);
        });
    }
};
