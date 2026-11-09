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
        if (!Schema::hasTable('stok_cancels')) {
            Schema::create('stok_cancels', function (Blueprint $table) {
                $table->id();
                $table->string('cancel_no', 50)->default('1');
                $table->date('cancel_tanggal')->nullable();
                $table->string('kode_gudang', 50)->default('001');
                $table->foreignId('master_gudang_id')->nullable()->constrained('master_gudangs')->nullOnDelete();
                $table->string('cancel_type', 50)->default('01');
                $table->text('keterangan')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('stok_cancel_details')) {
            Schema::create('stok_cancel_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('stok_cancel_id')->constrained('stok_cancels')->cascadeOnDelete();
                $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
                $table->integer('jumlah')->default(0);
                $table->string('satuan', 50)->nullable();
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_cancel_details');
        Schema::dropIfExists('stok_cancels');
    }
};
