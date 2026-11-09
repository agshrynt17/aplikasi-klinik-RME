<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_pasien_baru_mjkns', function (Blueprint $table) {
            if (!Schema::hasColumn('log_pasien_baru_mjkns', 'nomorkk')) {
                $table->string('nomorkk', 30)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('log_pasien_baru_mjkns', 'jeniskelamin')) {
                $table->string('jeniskelamin', 5)->nullable()->default('L')->after('nama_pasien');
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_pasien_baru_mjkns', function (Blueprint $table) {
            $table->dropColumn(['nomorkk', 'jeniskelamin']);
        });
    }
};
