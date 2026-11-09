<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            // kode_dpjp_bpjs sudah ada — tambah nama & timestamp saja
            if (!Schema::hasColumn('pegawais', 'nama_pcare')) {
                $table->string('nama_pcare')->nullable()->after('kode_dpjp_bpjs')->comment('Nama dokter dari PCare BPJS');
            }
            if (!Schema::hasColumn('pegawais', 'pcare_updated_at')) {
                $table->timestamp('pcare_updated_at')->nullable()->after('nama_pcare')->comment('Terakhir sync dari PCare');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn(['nama_pcare', 'pcare_updated_at']);
        });
    }
};
