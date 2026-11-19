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
        Schema::table('pasiens', function (Blueprint $table) {
            if (!Schema::hasColumn('pasiens', 'nama_satusehat')) {
                $table->string('nama_satusehat')->nullable()->after('kode_ihs_satusehat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            if (Schema::hasColumn('pasiens', 'nama_satusehat')) {
                $table->dropColumn('nama_satusehat');
            }
        });
    }
};
