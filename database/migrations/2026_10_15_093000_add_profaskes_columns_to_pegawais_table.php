<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawais', 'fungsional_medis')) {
                $table->string('fungsional_medis')->nullable()->after('nama_pegawai');
            }
            if (!Schema::hasColumn('pegawais', 'agama')) {
                $table->string('agama')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('pegawais', 'status_nikah')) {
                $table->string('status_nikah')->nullable()->after('tanggal_masuk');
            }
            if (!Schema::hasColumn('pegawais', 'no_kis')) {
                $table->string('no_kis')->nullable()->after('status_nikah');
            }
            if (!Schema::hasColumn('pegawais', 'no_sip')) {
                $table->string('no_sip')->nullable()->after('no_kis');
            }
            if (!Schema::hasColumn('pegawais', 'tgl_expired_sip')) {
                $table->date('tgl_expired_sip')->nullable()->after('no_sip');
            }
            if (!Schema::hasColumn('pegawais', 'is_apoteker')) {
                $table->boolean('is_apoteker')->default(false)->after('tgl_expired_sip');
            }
            if (!Schema::hasColumn('pegawais', 'no_spk')) {
                $table->string('no_spk')->nullable()->after('is_apoteker');
            }
            if (!Schema::hasColumn('pegawais', 'expired_spk')) {
                $table->date('expired_spk')->nullable()->after('no_spk');
            }
            if (!Schema::hasColumn('pegawais', 'status_pekerjaan')) {
                $table->string('status_pekerjaan')->nullable()->after('expired_spk');
            }
            if (!Schema::hasColumn('pegawais', 'golongan')) {
                $table->string('golongan')->nullable()->after('status_pekerjaan');
            }
            if (!Schema::hasColumn('pegawais', 'unit')) {
                $table->string('unit')->nullable()->after('golongan');
            }
            if (!Schema::hasColumn('pegawais', 'propinsi')) {
                $table->string('propinsi')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pegawais', 'kabupaten')) {
                $table->string('kabupaten')->nullable()->after('propinsi');
            }
            if (!Schema::hasColumn('pegawais', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('kabupaten');
            }
            if (!Schema::hasColumn('pegawais', 'desa')) {
                $table->string('desa')->nullable()->after('kecamatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'fungsional_medis', 'agama', 'status_nikah', 'no_kis',
                'no_sip', 'tgl_expired_sip', 'is_apoteker', 'no_spk',
                'expired_spk', 'status_pekerjaan', 'golongan', 'unit',
                'propinsi', 'kabupaten', 'kecamatan', 'desa'
            ]);
        });
    }
};
