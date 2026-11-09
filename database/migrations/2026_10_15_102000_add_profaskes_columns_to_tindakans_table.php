<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tindakans', function (Blueprint $table) {
            if (!Schema::hasColumn('tindakans', 'poliklinik_id')) {
                $table->foreignId('poliklinik_id')->nullable()->after('id')->constrained('polikliniks')->onDelete('set null');
            }
            if (!Schema::hasColumn('tindakans', 'is_konsultasi')) {
                $table->boolean('is_konsultasi')->default(false)->after('nama_tindakan');
            }
            if (!Schema::hasColumn('tindakans', 'is_informed_consent')) {
                $table->boolean('is_informed_consent')->default(false)->after('is_konsultasi');
            }
            if (!Schema::hasColumn('tindakans', 'is_ranap')) {
                $table->boolean('is_ranap')->default(true)->after('is_informed_consent');
            }
            if (!Schema::hasColumn('tindakans', 'bhp')) {
                $table->decimal('bhp', 14, 2)->default(0)->after('is_ranap');
            }
            if (!Schema::hasColumn('tindakans', 'jasa_petugas')) {
                $table->decimal('jasa_petugas', 14, 2)->default(0)->after('bhp');
            }
            if (!Schema::hasColumn('tindakans', 'penyusutan_gedung')) {
                $table->decimal('penyusutan_gedung', 14, 2)->default(0)->after('jasa_petugas');
            }
            if (!Schema::hasColumn('tindakans', 'penyusutan_alat')) {
                $table->decimal('penyusutan_alat', 14, 2)->default(0)->after('penyusutan_gedung');
            }
            if (!Schema::hasColumn('tindakans', 'unit_cost')) {
                $table->decimal('unit_cost', 14, 2)->default(0)->after('penyusutan_alat');
            }
        });
    }

    public function down()
    {
        Schema::table('tindakans', function (Blueprint $table) {
            if (Schema::hasColumn('tindakans', 'poliklinik_id')) {
                $table->dropForeign(['poliklinik_id']);
                $table->dropColumn('poliklinik_id');
            }
            $cols = ['is_konsultasi', 'is_informed_consent', 'is_ranap', 'bhp', 'jasa_petugas', 'penyusutan_gedung', 'penyusutan_alat', 'unit_cost'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tindakans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
