<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('kode_ihs_satusehat')->nullable()->after('no_bpjs');
        });
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('kode_ihs_satusehat')->nullable()->after('keterangan');
        });
    }
    public function down(): void {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn('kode_ihs_satusehat');
        });
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn('kode_ihs_satusehat');
        });
    }
};
