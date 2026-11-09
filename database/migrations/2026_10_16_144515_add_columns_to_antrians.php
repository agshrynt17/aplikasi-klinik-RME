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
        Schema::table('antrians', function (Blueprint $table) {
            $table->string('status_daftar', 30)->default('Menunggu')->after('status');
            $table->string('status_poli', 30)->default('Menunggu')->after('status_daftar');
            $table->foreignId('dokter_id')->nullable()->after('pasien_id')->constrained('pegawais')->onDelete('set null');
            $table->foreignId('jadwal_antrian_id')->nullable()->after('dokter_id')->constrained('jadwal_antrians')->onDelete('set null');
            $table->dateTime('waktu_cetak')->nullable()->after('jadwal_antrian_id');
            $table->string('waktu_tunggu', 50)->nullable()->after('waktu_cetak');
            $table->string('waktu_pelayanan', 50)->nullable()->after('waktu_tunggu');
            $table->string('sumber_data', 50)->default('Cetak Onsite')->after('waktu_pelayanan');
            $table->string('no_kartu_bpjs', 50)->nullable()->after('sumber_data');
            $table->string('nik', 30)->nullable()->after('no_kartu_bpjs');
            $table->string('status_kirim_tiket', 30)->default('Terkirim')->after('nik');
            $table->string('status_kirim_hadir', 30)->nullable()->after('status_kirim_tiket');
            $table->string('nama_pasien_non_rm', 150)->nullable()->after('status_kirim_hadir');
            $table->string('jam_praktek', 50)->nullable()->after('nama_pasien_non_rm');
        });
    }

    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropForeign(['dokter_id']);
            $table->dropForeign(['jadwal_antrian_id']);
            $table->dropColumn([
                'status_daftar', 'status_poli', 'dokter_id', 'jadwal_antrian_id',
                'waktu_cetak', 'waktu_tunggu', 'waktu_pelayanan', 'sumber_data',
                'no_kartu_bpjs', 'nik', 'status_kirim_tiket', 'status_kirim_hadir',
                'nama_pasien_non_rm', 'jam_praktek'
            ]);
        });
    }
};
