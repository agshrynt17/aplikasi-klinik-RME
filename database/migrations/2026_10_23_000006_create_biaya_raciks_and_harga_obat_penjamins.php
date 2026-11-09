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
        if (!Schema::hasTable('biaya_raciks')) {
            Schema::create('biaya_raciks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penjamin_id')->constrained('penjamins')->cascadeOnDelete();
                $table->string('nama_tarif', 150);
                $table->decimal('biaya', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('harga_obat_penjamins')) {
            Schema::create('harga_obat_penjamins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penjamin_id')->constrained('penjamins')->cascadeOnDelete();
                $table->foreignId('master_barang_id')->constrained('master_barangs')->cascadeOnDelete();
                $table->decimal('harga_jual', 12, 2)->default(0);
                $table->decimal('margin_persen', 5, 2)->default(0);
                $table->timestamps();

                $table->unique(['penjamin_id', 'master_barang_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_obat_penjamins');
        Schema::dropIfExists('biaya_raciks');
    }
};
