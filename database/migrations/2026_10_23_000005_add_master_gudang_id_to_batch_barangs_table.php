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
        Schema::table('batch_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('batch_barangs', 'master_gudang_id')) {
                $table->foreignId('master_gudang_id')->nullable()->after('master_barang_id')->constrained('master_gudangs')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('batch_barangs', 'master_gudang_id')) {
                $table->dropForeign(['master_gudang_id']);
                $table->dropColumn('master_gudang_id');
            }
        });
    }
};
