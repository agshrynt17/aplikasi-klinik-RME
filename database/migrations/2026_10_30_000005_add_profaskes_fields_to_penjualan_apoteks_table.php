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
        Schema::table('penjualan_apoteks', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan_apoteks', 'kode_transaksi')) {
                $table->string('kode_transaksi', 50)->nullable()->after('nomor_faktur');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'pasien_id')) {
                $table->foreignId('pasien_id')->nullable()->after('kode_transaksi')->constrained('pasiens')->nullOnDelete();
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'pelanggan_nama')) {
                $table->string('pelanggan_nama', 100)->nullable()->after('pasien_id');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'pelanggan_telp')) {
                $table->string('pelanggan_telp', 30)->nullable()->after('pelanggan_nama');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'dokter_id')) {
                $table->foreignId('dokter_id')->nullable()->after('pelanggan_telp')->constrained('pegawais')->nullOnDelete();
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'dokter_rujukan')) {
                $table->string('dokter_rujukan', 100)->nullable()->after('dokter_id');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'instansi_perujuk')) {
                $table->string('instansi_perujuk', 100)->nullable()->after('dokter_rujukan');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'no_resep_rujukan')) {
                $table->string('no_resep_rujukan', 50)->nullable()->after('instansi_perujuk');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'master_gudang_id')) {
                $table->foreignId('master_gudang_id')->nullable()->after('no_resep_rujukan')->constrained('master_gudangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'cara_bayar')) {
                $table->string('cara_bayar', 50)->default('Tunai')->after('master_gudang_id');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'status_bayar')) {
                $table->string('status_bayar', 50)->default('Lunas')->after('cara_bayar');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0)->after('total_harga');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'pembulatan')) {
                $table->decimal('pembulatan', 15, 2)->default(0)->after('diskon');
            }
            if (!Schema::hasColumn('penjualan_apoteks', 'grand_total')) {
                $table->decimal('grand_total', 15, 2)->default(0)->after('pembulatan');
            }
        });

        Schema::table('penjualan_apotek_details', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan_apotek_details', 'kemasan')) {
                $table->string('kemasan', 50)->nullable()->after('master_barang_id');
            }
            if (!Schema::hasColumn('penjualan_apotek_details', 'nomor_batch')) {
                $table->string('nomor_batch', 50)->nullable()->after('kemasan');
            }
            if (!Schema::hasColumn('penjualan_apotek_details', 'tanggal_kadaluarsa')) {
                $table->date('tanggal_kadaluarsa')->nullable()->after('nomor_batch');
            }
            if (!Schema::hasColumn('penjualan_apotek_details', 'dosis')) {
                $table->string('dosis', 100)->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('penjualan_apotek_details', 'cara_minum')) {
                $table->string('cara_minum', 100)->nullable()->after('dosis');
            }
            if (!Schema::hasColumn('penjualan_apotek_details', 'diskon')) {
                $table->decimal('diskon', 15, 2)->default(0)->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_apotek_details', function (Blueprint $table) {
            $table->dropColumn([
                'kemasan',
                'nomor_batch',
                'tanggal_kadaluarsa',
                'dosis',
                'cara_minum',
                'diskon',
            ]);
        });

        Schema::table('penjualan_apoteks', function (Blueprint $table) {
            $table->dropColumn([
                'kode_transaksi',
                'pasien_id',
                'pelanggan_nama',
                'pelanggan_telp',
                'dokter_id',
                'dokter_rujukan',
                'instansi_perujuk',
                'no_resep_rujukan',
                'master_gudang_id',
                'cara_bayar',
                'status_bayar',
                'diskon',
                'pembulatan',
                'grand_total',
            ]);
        });
    }
};
