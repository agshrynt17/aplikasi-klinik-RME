<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->string('kode_bpjs', 50)->nullable()->after('keterangan');
            $table->string('kode_satusehat', 100)->nullable()->after('kode_bpjs');
        });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('kode_dpjp_bpjs', 50)->nullable()->after('is_active');
            $table->string('kode_ihs_satusehat', 100)->nullable()->after('kode_dpjp_bpjs');
        });
    }

    public function down(): void
    {
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->dropColumn(['kode_bpjs', 'kode_satusehat']);
        });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn(['kode_dpjp_bpjs', 'kode_ihs_satusehat']);
        });
    }
};
