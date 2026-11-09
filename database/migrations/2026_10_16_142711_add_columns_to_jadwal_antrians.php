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
        Schema::table('jadwal_antrians', function (Blueprint $table) {
            $table->string('kode_prefix', 20)->nullable()->after('dokter_id');
            $table->string('ruangan', 100)->nullable()->after('kode_prefix');
            $table->string('sound', 100)->nullable()->after('kuota');
            $table->integer('terisi')->default(0)->after('sound');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_antrians', function (Blueprint $table) {
            $table->dropColumn(['kode_prefix', 'ruangan', 'sound', 'terisi']);
        });
    }
};
