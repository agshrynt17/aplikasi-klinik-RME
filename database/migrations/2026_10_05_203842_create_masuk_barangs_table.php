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
        Schema::create('masuk_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_faktur', 100);
            $table->string('nomor_penerimaan', 100)->unique();
            $table->foreignId('order_pembelian_id')->nullable()->constrained('order_pembelians');
            $table->foreignId('master_vendor_id')->nullable()->constrained('master_vendors');
            $table->date('tanggal_terima');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('jenis_pembayaran', 50)->default('Tunai');
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->string('status_pembayaran', 50)->default('Lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masuk_barangs');
    }
};
