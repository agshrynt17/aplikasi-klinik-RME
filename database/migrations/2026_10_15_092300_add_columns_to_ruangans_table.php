<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangans', 'poli_klinik')) {
                $table->string('poli_klinik')->nullable()->after('nama_ruangan');
            }
            if (!Schema::hasColumn('ruangans', 'ruang_ranap')) {
                $table->string('ruang_ranap')->nullable()->after('poli_klinik');
            }
            if (!Schema::hasColumn('ruangans', 'kapasitas_tempat_tidur')) {
                $table->string('kapasitas_tempat_tidur')->nullable()->after('ruang_ranap');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn(['poli_klinik', 'ruang_ranap', 'kapasitas_tempat_tidur']);
        });
    }
};
