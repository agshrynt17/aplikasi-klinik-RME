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
        Schema::table('hutangs', function (Blueprint $table) {
            if (!Schema::hasColumn('hutangs', 'no_tagihan')) {
                $table->string('no_tagihan', 100)->nullable()->after('id');
            }
            if (!Schema::hasColumn('hutangs', 'cara_bayar')) {
                $table->string('cara_bayar', 50)->nullable()->after('tanggal_jatuh_tempo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hutangs', function (Blueprint $table) {
            if (Schema::hasColumn('hutangs', 'no_tagihan')) {
                $table->dropColumn('no_tagihan');
            }
            if (Schema::hasColumn('hutangs', 'cara_bayar')) {
                $table->dropColumn('cara_bayar');
            }
        });
    }
};
