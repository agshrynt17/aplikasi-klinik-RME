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
        if (!Schema::hasTable('stok_gudangs')) {
            Schema::create('stok_gudangs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_gudang_id')->constrained('master_gudangs')->cascadeOnDelete();
                $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
                $table->integer('stok')->default(0);
                $table->integer('stok_awal')->default(0);
                $table->timestamps();

                $table->unique(['master_gudang_id', 'master_barang_id']);
            });
        }

        Schema::table('history_stoks', function (Blueprint $table) {
            if (!Schema::hasColumn('history_stoks', 'master_gudang_id')) {
                $table->foreignId('master_gudang_id')->nullable()->after('master_barang_id')->constrained('master_gudangs')->nullOnDelete();
            }
            if (!Schema::hasColumn('history_stoks', 'tipe_penyesuaian')) {
                $table->string('tipe_penyesuaian', 50)->nullable()->after('jenis_penyesuaian');
            }
            if (!Schema::hasColumn('history_stoks', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('tipe_penyesuaian');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_stoks', function (Blueprint $table) {
            if (Schema::hasColumn('history_stoks', 'master_gudang_id')) {
                $table->dropForeign(['master_gudang_id']);
                $table->dropColumn(['master_gudang_id', 'tipe_penyesuaian', 'tanggal']);
            }
        });

        Schema::dropIfExists('stok_gudangs');
    }
};
