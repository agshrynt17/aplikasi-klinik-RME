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
        Schema::table('profil_faskes', function (Blueprint $table) {
            $table->string('jenis_faskes')->nullable()->default('Faskes Rawat Jalan');
            $table->string('tingkat_pelayanan')->nullable()->default('Tingkat Pertama (FKTP)');
            $table->string('no_izin_operasional')->nullable();
            $table->string('no_izin_apotek')->nullable()->default('123/019/X/KLINIK-OSS/XXX');
            $table->string('penanggung_jawab_apotek')->nullable()->default('apt. Fariddon Ricky Wahyudi, S.Farm');
            $table->string('kerjasama_bpjs')->nullable()->default('Ya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_faskes', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_faskes',
                'tingkat_pelayanan',
                'no_izin_operasional',
                'no_izin_apotek',
                'penanggung_jawab_apotek',
                'kerjasama_bpjs'
            ]);
        });
    }
};
