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
        Schema::table('master_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('master_barangs', 'jenis')) {
                $table->string('jenis', 50)->default('Obat')->after('nama_barang');
            }
            if (!Schema::hasColumn('master_barangs', 'kemasan')) {
                $table->string('kemasan', 50)->nullable()->after('jenis');
            }
            if (!Schema::hasColumn('master_barangs', 'kelas_terapi')) {
                $table->string('kelas_terapi', 150)->nullable()->after('kemasan');
            }
            if (!Schema::hasColumn('master_barangs', 'golongan_obat')) {
                $table->string('golongan_obat', 100)->nullable()->after('kelas_terapi');
            }
            if (!Schema::hasColumn('master_barangs', 'bentuk_obat')) {
                $table->string('bentuk_obat', 100)->nullable()->after('golongan_obat');
            }
            if (!Schema::hasColumn('master_barangs', 'rute_obat')) {
                $table->string('rute_obat', 100)->nullable()->after('bentuk_obat');
            }
            if (!Schema::hasColumn('master_barangs', 'hna')) {
                $table->decimal('hna', 14, 2)->default(0)->after('rute_obat');
            }
            if (!Schema::hasColumn('master_barangs', 'minimum_stok')) {
                $table->integer('minimum_stok')->default(1)->after('hna');
            }
            if (!Schema::hasColumn('master_barangs', 'is_formularium')) {
                $table->boolean('is_formularium')->default(false)->after('minimum_stok');
            }
            if (!Schema::hasColumn('master_barangs', 'produsen')) {
                $table->string('produsen', 150)->nullable()->after('is_formularium');
            }
            if (!Schema::hasColumn('master_barangs', 'jenis_produk_kfa')) {
                $table->string('jenis_produk_kfa', 100)->nullable()->after('produsen');
            }
            if (!Schema::hasColumn('master_barangs', 'kode_kfa')) {
                $table->string('kode_kfa', 100)->nullable()->after('jenis_produk_kfa');
            }
            if (!Schema::hasColumn('master_barangs', 'nama_kfa')) {
                $table->string('nama_kfa', 255)->nullable()->after('kode_kfa');
            }
            if (!Schema::hasColumn('master_barangs', 'no_batch')) {
                $table->string('no_batch', 100)->nullable()->after('nama_kfa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_barangs', function (Blueprint $table) {
            $cols = [
                'jenis', 'kemasan', 'kelas_terapi', 'golongan_obat', 
                'bentuk_obat', 'rute_obat', 'hna', 'minimum_stok', 
                'is_formularium', 'produsen', 'jenis_produk_kfa', 
                'kode_kfa', 'nama_kfa', 'no_batch'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('master_barangs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
