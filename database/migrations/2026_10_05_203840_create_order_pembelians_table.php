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
        Schema::create('order_pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po', 50)->unique();
            $table->foreignId('master_vendor_id')->nullable()->constrained('master_vendors')->nullOnDelete();
            $table->date('tanggal_po');
            $table->date('rencana_datang')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('status', 50)->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pembelians');
    }
};
