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
        Schema::table('master_antrians', function (Blueprint $table) {
            $table->string('sound_layanan', 255)->nullable()->after('poliklinik_id');
            $table->string('jenis', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('master_antrians', function (Blueprint $table) {
            $table->dropColumn('sound_layanan');
        });
    }
};
