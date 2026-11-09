<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            if (!Schema::hasColumn('pasiens', 'nomor_identitas_lain')) {
                $table->string('nomor_identitas_lain', 50)->nullable()->after('no_bpjs');
            }
            if (!Schema::hasColumn('pasiens', 'nama_ibu_kandung')) {
                $table->string('nama_ibu_kandung', 100)->nullable()->after('nama_pasien');
            }
            if (!Schema::hasColumn('pasiens', 'agama')) {
                $table->string('agama', 30)->nullable()->after('status_pernikahan');
            }
            if (!Schema::hasColumn('pasiens', 'suku')) {
                $table->string('suku', 50)->nullable()->after('agama');
            }
            if (!Schema::hasColumn('pasiens', 'no_telp')) {
                $table->string('no_telp', 30)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('pasiens', 'email')) {
                $table->string('email', 100)->nullable()->after('no_telp');
            }
            if (!Schema::hasColumn('pasiens', 'pendidikan')) {
                $table->string('pendidikan', 50)->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('pasiens', 'rt')) {
                $table->string('rt', 10)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pasiens', 'rw')) {
                $table->string('rw', 10)->nullable()->after('rt');
            }
            if (!Schema::hasColumn('pasiens', 'provinsi')) {
                $table->string('provinsi', 100)->nullable()->after('rw');
            }
            if (!Schema::hasColumn('pasiens', 'kabupaten')) {
                $table->string('kabupaten', 100)->nullable()->after('provinsi');
            }
            if (!Schema::hasColumn('pasiens', 'kecamatan')) {
                $table->string('kecamatan', 100)->nullable()->after('kabupaten');
            }
            if (!Schema::hasColumn('pasiens', 'desa')) {
                $table->string('desa', 100)->nullable()->after('kecamatan');
            }
            if (!Schema::hasColumn('pasiens', 'kode_pos')) {
                $table->string('kode_pos', 15)->nullable()->after('desa');
            }
            if (!Schema::hasColumn('pasiens', 'kode_faskes_bpjs')) {
                $table->string('kode_faskes_bpjs', 50)->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('pasiens', 'nama_faskes_bpjs')) {
                $table->string('nama_faskes_bpjs', 100)->nullable()->after('kode_faskes_bpjs');
            }
            if (!Schema::hasColumn('pasiens', 'kontak_darurat')) {
                $table->string('kontak_darurat', 50)->nullable()->after('nama_faskes_bpjs');
            }
            if (!Schema::hasColumn('pasiens', 'is_domisili_sama')) {
                $table->boolean('is_domisili_sama')->default(true)->after('kontak_darurat');
            }
            if (!Schema::hasColumn('pasiens', 'alamat_domisili')) {
                $table->text('alamat_domisili')->nullable()->after('is_domisili_sama');
            }
            if (!Schema::hasColumn('pasiens', 'rt_domisili')) {
                $table->string('rt_domisili', 10)->nullable()->after('alamat_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'rw_domisili')) {
                $table->string('rw_domisili', 10)->nullable()->after('rt_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'provinsi_domisili')) {
                $table->string('provinsi_domisili', 100)->nullable()->after('rw_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'kabupaten_domisili')) {
                $table->string('kabupaten_domisili', 100)->nullable()->after('provinsi_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'kecamatan_domisili')) {
                $table->string('kecamatan_domisili', 100)->nullable()->after('kabupaten_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'desa_domisili')) {
                $table->string('desa_domisili', 100)->nullable()->after('kecamatan_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'kode_pos_domisili')) {
                $table->string('kode_pos_domisili', 15)->nullable()->after('desa_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'bahasa')) {
                $table->string('bahasa', 50)->nullable()->after('kode_pos_domisili');
            }
            if (!Schema::hasColumn('pasiens', 'is_meninggal')) {
                $table->boolean('is_meninggal')->default(false)->after('bahasa');
            }
            if (!Schema::hasColumn('pasiens', 'tanggal_kematian')) {
                $table->date('tanggal_kematian')->nullable()->after('is_meninggal');
            }
            if (!Schema::hasColumn('pasiens', 'alergi_obat')) {
                $table->text('alergi_obat')->nullable()->after('tanggal_kematian');
            }
            if (!Schema::hasColumn('pasiens', 'alergi_makanan')) {
                $table->text('alergi_makanan')->nullable()->after('alergi_obat');
            }
            if (!Schema::hasColumn('pasiens', 'catatan_khusus')) {
                $table->text('catatan_khusus')->nullable()->after('alergi_makanan');
            }
            if (!Schema::hasColumn('pasiens', 'resus')) {
                $table->string('resus', 10)->nullable()->after('golongan_darah');
            }
            if (!Schema::hasColumn('pasiens', 'kode_rm_ibu')) {
                $table->string('kode_rm_ibu', 30)->nullable()->after('no_rm');
            }
        });
    }

    public function down(): void
    {
        // No down needed for extension
    }
};
