<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tarif_tindakans', function (Blueprint $table) {
            if (!Schema::hasColumn('tarif_tindakans', 'unit_cost')) {
                $table->decimal('unit_cost', 14, 2)->default(0)->after('penjamin_id');
            }
            if (!Schema::hasColumn('tarif_tindakans', 'jasa_medis')) {
                $table->decimal('jasa_medis', 14, 2)->default(0)->after('unit_cost');
            }
            if (!Schema::hasColumn('tarif_tindakans', 'jasa_paramedis')) {
                $table->decimal('jasa_paramedis', 14, 2)->default(0)->after('jasa_medis');
            }
            if (!Schema::hasColumn('tarif_tindakans', 'margin')) {
                $table->decimal('margin', 14, 2)->default(0)->after('jasa_paramedis');
            }
            if (!Schema::hasColumn('tarif_tindakans', 'total_tarif')) {
                $table->decimal('total_tarif', 14, 2)->default(0)->after('margin');
            }
        });
    }

    public function down()
    {
        Schema::table('tarif_tindakans', function (Blueprint $table) {
            $cols = ['unit_cost', 'jasa_medis', 'jasa_paramedis', 'margin', 'total_tarif'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tarif_tindakans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
