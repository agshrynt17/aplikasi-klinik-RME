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
        Schema::table('master_diagnosas', function (Blueprint $table) {
            $table->string('kode_katdiagnosa')->nullable()->after('nama_diagnosa');
            $table->string('kode_kelompokdiagnosa')->nullable()->after('kode_katdiagnosa');
            $table->boolean('is_non_spesialis')->default(false)->after('kode_kelompokdiagnosa');
            $table->boolean('is_diagnosa_lab')->default(false)->after('is_non_spesialis');
            $table->boolean('is_diagnosa_radiologi')->default(false)->after('is_diagnosa_lab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_diagnosas', function (Blueprint $table) {
            $table->dropColumn([
                'kode_katdiagnosa',
                'kode_kelompokdiagnosa',
                'is_non_spesialis',
                'is_diagnosa_lab',
                'is_diagnosa_radiologi'
            ]);
        });
    }
};
