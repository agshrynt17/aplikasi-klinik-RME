<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_pembelians', function (Blueprint $table) {
            if (!Schema::hasColumn('order_pembelians', 'tgl_kirim')) {
                $table->date('tgl_kirim')->nullable()->after('rencana_datang');
            }
            if (!Schema::hasColumn('order_pembelians', 'pengiriman_kurir')) {
                $table->string('pengiriman_kurir')->nullable()->after('tgl_kirim');
            }
            if (!Schema::hasColumn('order_pembelians', 'alamat_kirim')) {
                $table->text('alamat_kirim')->nullable()->after('pengiriman_kurir');
            }
        });

        Schema::table('order_pembelian_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_pembelian_details', 'kemasan')) {
                $table->string('kemasan')->nullable()->after('master_barang_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_pembelians', function (Blueprint $table) {
            $table->dropColumn(['tgl_kirim', 'pengiriman_kurir', 'alamat_kirim']);
        });

        Schema::table('order_pembelian_details', function (Blueprint $table) {
            $table->dropColumn(['kemasan']);
        });
    }
};
