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
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->string('unit', 50)->nullable()->default('Pelayanan');
            $table->string('wajib_ppa', 10)->nullable()->default('Ya');
            $table->string('jenis_poli', 50)->nullable()->default('Poli Sakit');
            $table->boolean('is_igd')->default(false);
            $table->boolean('is_rehab_medik')->default(false);
            $table->boolean('is_mcu')->default(false);
            $table->boolean('is_lab')->default(false);
            $table->boolean('is_radiologi')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polikliniks', function (Blueprint $table) {
            $table->dropColumn([
                'unit',
                'wajib_ppa',
                'jenis_poli',
                'is_igd',
                'is_rehab_medik',
                'is_mcu',
                'is_lab',
                'is_radiologi'
            ]);
        });
    }
};
