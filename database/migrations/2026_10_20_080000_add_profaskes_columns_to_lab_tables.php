<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_labs', function (Blueprint $table) {
            if (!Schema::hasColumn('master_labs', 'golongan_lab')) {
                $table->string('golongan_lab', 100)->nullable()->after('kode_lab');
            }
            if (!Schema::hasColumn('master_labs', 'kelompok_lab')) {
                $table->string('kelompok_lab', 50)->default('Tunggal')->after('nama_pemeriksaan');
            }
            if (!Schema::hasColumn('master_labs', 'unitcost')) {
                $table->decimal('unitcost', 15, 2)->default(0)->after('kelompok_lab');
            }
        });

        Schema::table('lab_nilai_normals', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_nilai_normals', 'kategori')) {
                $table->string('kategori', 100)->nullable()->after('master_lab_id');
            }
            if (!Schema::hasColumn('lab_nilai_normals', 'detail_lab')) {
                $table->string('detail_lab', 150)->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('lab_nilai_normals', 'nama_setting')) {
                $table->string('nama_setting', 150)->nullable()->after('detail_lab');
            }
            if (!Schema::hasColumn('lab_nilai_normals', 'satuan')) {
                $table->string('satuan', 50)->nullable()->after('nama_setting');
            }
            if (!Schema::hasColumn('lab_nilai_normals', 'bentuk_nilai')) {
                $table->string('bentuk_nilai', 50)->default('range')->after('satuan');
            }
            if (!Schema::hasColumn('lab_nilai_normals', 'nilai_normal')) {
                $table->string('nilai_normal', 100)->nullable()->after('bentuk_nilai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('master_labs', function (Blueprint $table) {
            $table->dropColumn(['golongan_lab', 'kelompok_lab', 'unitcost']);
        });

        Schema::table('lab_nilai_normals', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'detail_lab', 'nama_setting', 'satuan', 'bentuk_nilai', 'nilai_normal']);
        });
    }
};
