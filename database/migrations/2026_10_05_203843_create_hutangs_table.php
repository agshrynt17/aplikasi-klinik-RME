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
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masuk_barang_id')->constrained('masuk_barangs')->cascadeOnDelete();
            $table->foreignId('master_vendor_id')->nullable()->constrained('master_vendors');
            $table->decimal('jumlah_hutang', 15, 2);
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_hutang', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->string('status', 50)->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};
