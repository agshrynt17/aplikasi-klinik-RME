<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_kasirs', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_kasirs', 'biaya_adm')) {
                $table->decimal('biaya_adm', 12, 2)->default(10000)->after('waktu_bayar');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'biaya_konsultasi')) {
                $table->decimal('biaya_konsultasi', 12, 2)->default(0)->after('total_tindakan');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'biaya_lab')) {
                $table->decimal('biaya_lab', 12, 2)->default(0)->after('total_obat');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'biaya_radiologi')) {
                $table->decimal('biaya_radiologi', 12, 2)->default(0)->after('biaya_lab');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'sub_total')) {
                $table->decimal('sub_total', 12, 2)->default(0)->after('biaya_radiologi');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'dijamin')) {
                $table->decimal('dijamin', 12, 2)->default(0)->after('total_tagihan');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'dibayar_pasien')) {
                $table->decimal('dibayar_pasien', 12, 2)->default(0)->after('dijamin');
            }
            if (!Schema::hasColumn('transaksi_kasirs', 'cara_bayar')) {
                $table->string('cara_bayar', 50)->default('Belum Bayar')->after('dibayar_pasien');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_kasirs', function (Blueprint $table) {
            $table->dropColumn([
                'biaya_adm', 'biaya_konsultasi', 'biaya_lab', 'biaya_radiologi',
                'sub_total', 'dijamin', 'dibayar_pasien', 'cara_bayar'
            ]);
        });
    }
};

