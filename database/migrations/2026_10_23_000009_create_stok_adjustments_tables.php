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
        if (!Schema::hasTable('stok_adjustments')) {
            Schema::create('stok_adjustments', function (Blueprint $table) {
                $table->id();
                $table->string('no_penyesuaian')->unique();
                $table->date('tanggal_penyesuaian');
                $table->foreignId('master_gudang_id')->nullable()->constrained('master_gudangs')->nullOnDelete();
                $table->string('kode_gudang')->nullable();
                $table->string('tipe')->default('Penyesuaian Stok');
                $table->text('keterangan')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_name')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('stok_adjustment_details')) {
            Schema::create('stok_adjustment_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stok_adjustment_id')->constrained('stok_adjustments')->cascadeOnDelete();
                $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
                $table->integer('stok_sistem')->default(0);
                $table->integer('stok_fisik')->default(0);
                $table->integer('selisih')->default(0);
                $table->string('satuan')->nullable();
                $table->string('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_adjustment_details');
        Schema::dropIfExists('stok_adjustments');
    }
};
