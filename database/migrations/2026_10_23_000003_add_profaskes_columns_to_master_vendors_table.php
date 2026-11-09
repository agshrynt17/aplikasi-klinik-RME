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
        Schema::table('master_vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('master_vendors', 'join_date')) {
                $table->date('join_date')->nullable()->after('nama_vendor');
            }
            if (!Schema::hasColumn('master_vendors', 'telp')) {
                $table->string('telp', 50)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('master_vendors', 'fax')) {
                $table->string('fax', 50)->nullable()->after('telp');
            }
            if (!Schema::hasColumn('master_vendors', 'nomor_spk')) {
                $table->string('nomor_spk', 100)->nullable()->after('email');
            }
            if (!Schema::hasColumn('master_vendors', 'expired_spk')) {
                $table->date('expired_spk')->nullable()->after('nomor_spk');
            }
            if (!Schema::hasColumn('master_vendors', 'nama_pic')) {
                $table->string('nama_pic', 100)->nullable()->after('expired_spk');
            }
            if (!Schema::hasColumn('master_vendors', 'telp_pic')) {
                $table->string('telp_pic', 50)->nullable()->after('nama_pic');
            }
            if (!Schema::hasColumn('master_vendors', 'email_pic')) {
                $table->string('email_pic', 100)->nullable()->after('telp_pic');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_vendors', function (Blueprint $table) {
            $cols = ['join_date', 'telp', 'fax', 'nomor_spk', 'expired_spk', 'nama_pic', 'telp_pic', 'email_pic'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('master_vendors', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
