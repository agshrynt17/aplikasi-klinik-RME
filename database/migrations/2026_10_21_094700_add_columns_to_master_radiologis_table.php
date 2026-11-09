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
        Schema::table('master_radiologis', function (Blueprint $table) {
            if (!Schema::hasColumn('master_radiologis', 'alat_tersedia')) {
                $table->boolean('alat_tersedia')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('master_radiologis', 'golongan')) {
                $table->string('golongan', 100)->nullable()->after('nama_pemeriksaan');
            }
            if (!Schema::hasColumn('master_radiologis', 'permintaan_hasil')) {
                $table->string('permintaan_hasil', 100)->nullable()->after('golongan');
            }
            if (!Schema::hasColumn('master_radiologis', 'unitcost')) {
                $table->decimal('unitcost', 15, 2)->default(0)->after('permintaan_hasil');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_radiologis', function (Blueprint $table) {
            $table->dropColumn(['alat_tersedia', 'golongan', 'permintaan_hasil', 'unitcost']);
        });
    }
};
