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
        Schema::create('tarif_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_lab_id')->constrained('master_labs')->onDelete('cascade');
            $table->foreignId('penjamin_id')->nullable()->constrained('penjamins')->onDelete('cascade'); // Null = UMUM
            $table->integer('harga')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_labs');
    }
};
