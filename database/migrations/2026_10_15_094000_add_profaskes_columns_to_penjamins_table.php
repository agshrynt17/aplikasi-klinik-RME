<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjamins', function (Blueprint $table) {
            if (!Schema::hasColumn('penjamins', 'jenis_penjamin')) {
                $table->string('jenis_penjamin')->nullable()->default('Pribadi/Umum')->after('nama_penjamin');
            }
            if (!Schema::hasColumn('penjamins', 'telp')) {
                $table->string('telp')->nullable()->after('jenis_penjamin');
            }
            if (!Schema::hasColumn('penjamins', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('telp');
            }
            if (!Schema::hasColumn('penjamins', 'join_date')) {
                $table->date('join_date')->nullable()->after('is_default');
            }
            if (!Schema::hasColumn('penjamins', 'alamat')) {
                $table->text('alamat')->nullable()->after('join_date');
            }
            if (!Schema::hasColumn('penjamins', 'fax')) {
                $table->string('fax')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('penjamins', 'email')) {
                $table->string('email')->nullable()->after('fax');
            }
            if (!Schema::hasColumn('penjamins', 'nomor_spk')) {
                $table->string('nomor_spk')->nullable()->after('email');
            }
            if (!Schema::hasColumn('penjamins', 'expired_date')) {
                $table->date('expired_date')->nullable()->after('nomor_spk');
            }
            if (!Schema::hasColumn('penjamins', 'nama_pic')) {
                $table->string('nama_pic')->nullable()->after('expired_date');
            }
            if (!Schema::hasColumn('penjamins', 'telp_pic')) {
                $table->string('telp_pic')->nullable()->after('nama_pic');
            }
            if (!Schema::hasColumn('penjamins', 'email_pic')) {
                $table->string('email_pic')->nullable()->after('telp_pic');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjamins', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_penjamin', 'telp', 'is_default', 'join_date',
                'alamat', 'fax', 'email', 'nomor_spk', 'expired_date',
                'nama_pic', 'telp_pic', 'email_pic'
            ]);
        });
    }
};
