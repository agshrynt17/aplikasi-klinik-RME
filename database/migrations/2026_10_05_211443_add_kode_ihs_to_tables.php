<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('profil_faskes', function (Blueprint $table) {
            $table->string('kode_ihs_satusehat')->nullable()->after('nama_pimpinan');
        });
        Schema::table('unit_layanans', function (Blueprint $table) {
            $table->string('kode_ihs_satusehat')->nullable()->after('is_active');
        });
        Schema::table('ruangans', function (Blueprint $table) {
            $table->string('kode_ihs_satusehat')->nullable()->after('is_active');
        });
    }
    public function down(): void {
        Schema::table('profil_faskes', function (Blueprint $table) {
            $table->dropColumn('kode_ihs_satusehat');
        });
        Schema::table('unit_layanans', function (Blueprint $table) {
            $table->dropColumn('kode_ihs_satusehat');
        });
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn('kode_ihs_satusehat');
        });
    }
};
