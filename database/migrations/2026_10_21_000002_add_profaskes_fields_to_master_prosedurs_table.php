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
        Schema::table('master_prosedurs', function (Blueprint $table) {
            $table->string('kelompok_prosedur')->nullable()->after('nama_prosedur');
            $table->string('kategori_prosedur')->nullable()->after('kelompok_prosedur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_prosedurs', function (Blueprint $table) {
            $table->dropColumn(['kelompok_prosedur', 'kategori_prosedur']);
        });
    }
};
