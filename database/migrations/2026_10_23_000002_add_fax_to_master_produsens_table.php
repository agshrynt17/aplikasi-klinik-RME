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
        Schema::table('master_produsens', function (Blueprint $table) {
            if (!Schema::hasColumn('master_produsens', 'fax')) {
                $table->string('fax', 50)->nullable()->after('telp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_produsens', function (Blueprint $table) {
            if (Schema::hasColumn('master_produsens', 'fax')) {
                $table->dropColumn('fax');
            }
        });
    }
};
