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
        Schema::table('mutasi_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('mutasi_barangs', 'gudang_asal_id')) {
                $table->foreignId('gudang_asal_id')->nullable()->after('tanggal_mutasi')->constrained('master_gudangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('mutasi_barangs', 'gudang_tujuan_id')) {
                $table->foreignId('gudang_tujuan_id')->nullable()->after('gudang_asal_id')->constrained('master_gudangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('mutasi_barangs', 'petugas')) {
                $table->string('petugas', 150)->nullable()->after('penerima');
            }
            if (!Schema::hasColumn('mutasi_barangs', 'sudah_diterima')) {
                $table->string('sudah_diterima', 50)->default('Belum Terima')->after('status');
            }
        });

        Schema::table('mutasi_barang_details', function (Blueprint $table) {
            if (!Schema::hasColumn('mutasi_barang_details', 'kemasan')) {
                $table->string('kemasan', 50)->nullable()->after('master_barang_id');
            }
            if (!Schema::hasColumn('mutasi_barang_details', 'nomor_batch')) {
                $table->string('nomor_batch', 100)->nullable()->after('kemasan');
            }
            if (!Schema::hasColumn('mutasi_barang_details', 'tanggal_kadaluarsa')) {
                $table->date('tanggal_kadaluarsa')->nullable()->after('nomor_batch');
            }
            if (!Schema::hasColumn('mutasi_barang_details', 'batch_barang_id')) {
                $table->foreignId('batch_barang_id')->nullable()->after('tanggal_kadaluarsa')->constrained('batch_barangs')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_barang_details', function (Blueprint $table) {
            $cols = ['kemasan', 'nomor_batch', 'tanggal_kadaluarsa', 'batch_barang_id'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('mutasi_barang_details', $c)) {
                    if ($c === 'batch_barang_id') {
                        $table->dropForeign(['batch_barang_id']);
                    }
                    $table->dropColumn($c);
                }
            }
        });

        Schema::table('mutasi_barangs', function (Blueprint $table) {
            $cols = ['gudang_asal_id', 'gudang_tujuan_id', 'petugas', 'sudah_diterima'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('mutasi_barangs', $c)) {
                    if ($c === 'gudang_asal_id' || $c === 'gudang_tujuan_id') {
                        $table->dropForeign([$c]);
                    }
                    $table->dropColumn($c);
                }
            }
        });
    }
};
