<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawais', 'nama_satusehat')) {
                $table->string('nama_satusehat')->nullable()->after('kode_ihs_satusehat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('pegawais', 'nama_satusehat')) {
                $table->dropColumn('nama_satusehat');
            }
        });
    }
};
