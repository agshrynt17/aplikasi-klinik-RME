<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_antrian_mjkns', function (Blueprint $table) {
            if (!Schema::hasColumn('log_antrian_mjkns', 'nama_pasien')) {
                $table->string('nama_pasien', 150)->nullable()->after('nomorkartu');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'jenis_pasien')) {
                $table->string('jenis_pasien', 50)->default('Pasien Lama')->after('nama_pasien');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'status_antrian')) {
                $table->string('status_antrian', 50)->default('Menunggu')->after('jenis_pasien');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'no_registrasi')) {
                $table->string('no_registrasi', 50)->nullable()->after('status_antrian');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'nama_poli')) {
                $table->string('nama_poli', 100)->nullable()->after('kodepoli');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'nama_dokter')) {
                $table->string('nama_dokter', 150)->nullable()->after('kodedokter');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'jam_praktek')) {
                $table->string('jam_praktek', 50)->default('06:00-22:00')->after('tanggalperiksa');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'keluhan')) {
                $table->text('keluhan')->nullable()->after('angkaantrean');
            }
            if (!Schema::hasColumn('log_antrian_mjkns', 'is_batal')) {
                $table->boolean('is_batal')->default(false)->after('keluhan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_antrian_mjkns', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pasien', 'jenis_pasien', 'status_antrian', 'no_registrasi',
                'nama_poli', 'nama_dokter', 'jam_praktek', 'keluhan', 'is_batal'
            ]);
        });
    }
};
