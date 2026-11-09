<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->string('kode_poli_pcare', 20)->nullable()->after('kode_poli')->comment('Kode poli dari PCare BPJS');
            $table->string('nama_poli_pcare')->nullable()->after('kode_poli_pcare')->comment('Nama poli dari PCare BPJS');
            $table->boolean('is_poli_sakit')->default(false)->after('nama_poli_pcare')->comment('Tandai sebagai Poli Sakit');
            $table->timestamp('pcare_updated_at')->nullable()->after('is_poli_sakit')->comment('Terakhir sync dari PCare');
        });
    }

    public function down(): void
    {
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->dropColumn(['kode_poli_pcare', 'nama_poli_pcare', 'is_poli_sakit', 'pcare_updated_at']);
        });
    }
};
