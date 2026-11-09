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
        Schema::table('masuk_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('masuk_barangs', 'kode_po')) {
                $table->string('kode_po', 100)->nullable()->after('order_pembelian_id');
            }
            if (!Schema::hasColumn('masuk_barangs', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->default(0)->after('jenis_pembayaran');
            }
            if (!Schema::hasColumn('masuk_barangs', 'diskon_total_persen')) {
                $table->decimal('diskon_total_persen', 5, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('masuk_barangs', 'diskon_total_nominal')) {
                $table->decimal('diskon_total_nominal', 15, 2)->default(0)->after('diskon_total_persen');
            }
            if (!Schema::hasColumn('masuk_barangs', 'ppn_persen')) {
                $table->decimal('ppn_persen', 5, 2)->default(0)->after('diskon_total_nominal');
            }
            if (!Schema::hasColumn('masuk_barangs', 'ppn_nominal')) {
                $table->decimal('ppn_nominal', 15, 2)->default(0)->after('ppn_persen');
            }
            if (!Schema::hasColumn('masuk_barangs', 'grand_total')) {
                $table->decimal('grand_total', 15, 2)->default(0)->after('ppn_nominal');
            }
            if (!Schema::hasColumn('masuk_barangs', 'total_bayar')) {
                $table->decimal('total_bayar', 15, 2)->default(0)->after('grand_total');
            }
            if (!Schema::hasColumn('masuk_barangs', 'sisa_bayar')) {
                $table->decimal('sisa_bayar', 15, 2)->default(0)->after('total_bayar');
            }
        });

        Schema::table('masuk_barang_details', function (Blueprint $table) {
            if (!Schema::hasColumn('masuk_barang_details', 'master_gudang_id')) {
                $table->foreignId('master_gudang_id')->nullable()->after('masuk_barang_id')->constrained('master_gudangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('masuk_barang_details', 'lokasi_rak')) {
                $table->string('lokasi_rak', 100)->nullable()->after('master_gudang_id');
            }
            if (!Schema::hasColumn('masuk_barang_details', 'kemasan')) {
                $table->string('kemasan', 100)->nullable()->after('master_barang_id');
            }
            if (!Schema::hasColumn('masuk_barang_details', 'diskon_persen')) {
                $table->decimal('diskon_persen', 5, 2)->default(0)->after('harga_satuan');
            }
            if (!Schema::hasColumn('masuk_barang_details', 'diskon_nominal')) {
                $table->decimal('diskon_nominal', 15, 2)->default(0)->after('diskon_persen');
            }
            if (!Schema::hasColumn('masuk_barang_details', 'nomor_bpom')) {
                $table->string('nomor_bpom', 100)->nullable()->after('tanggal_kadaluarsa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masuk_barang_details', function (Blueprint $table) {
            $cols = ['master_gudang_id', 'lokasi_rak', 'kemasan', 'diskon_persen', 'diskon_nominal', 'nomor_bpom'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('masuk_barang_details', $col)) {
                    if ($col === 'master_gudang_id') {
                        $table->dropForeign(['master_gudang_id']);
                    }
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('masuk_barangs', function (Blueprint $table) {
            $cols = [
                'kode_po', 'subtotal', 'diskon_total_persen', 'diskon_total_nominal',
                'ppn_persen', 'ppn_nominal', 'grand_total', 'total_bayar', 'sisa_bayar'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('masuk_barangs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
