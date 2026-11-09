<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tpas', function (Blueprint $table) {
            if (!Schema::hasColumn('tpas', 'alamat')) {
                $table->string('alamat')->nullable()->after('nama_tpa');
            }
            if (!Schema::hasColumn('tpas', 'telp')) {
                $table->string('telp', 50)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('tpas', 'fax')) {
                $table->string('fax', 50)->nullable()->after('telp');
            }
            if (!Schema::hasColumn('tpas', 'email')) {
                $table->string('email')->nullable()->after('fax');
            }
            if (!Schema::hasColumn('tpas', 'join_date')) {
                $table->date('join_date')->nullable()->after('email');
            }
            if (!Schema::hasColumn('tpas', 'nomor_spk')) {
                $table->string('nomor_spk')->nullable()->after('join_date');
            }
            if (!Schema::hasColumn('tpas', 'expired_date')) {
                $table->date('expired_date')->nullable()->after('nomor_spk');
            }
            if (!Schema::hasColumn('tpas', 'nama_pic')) {
                $table->string('nama_pic')->nullable()->after('expired_date');
            }
            if (!Schema::hasColumn('tpas', 'telp_pic')) {
                $table->string('telp_pic', 50)->nullable()->after('nama_pic');
            }
            if (!Schema::hasColumn('tpas', 'email_pic')) {
                $table->string('email_pic')->nullable()->after('telp_pic');
            }
        });
    }

    public function down()
    {
        Schema::table('tpas', function (Blueprint $table) {
            $cols = ['alamat', 'telp', 'fax', 'email', 'join_date', 'nomor_spk', 'expired_date', 'nama_pic', 'telp_pic', 'email_pic'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tpas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
