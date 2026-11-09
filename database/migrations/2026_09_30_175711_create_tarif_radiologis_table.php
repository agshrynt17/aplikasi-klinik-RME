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
        Schema::create('tarif_radiologis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_radiologi_id')->constrained('master_radiologis')->onDelete('cascade');
            $table->foreignId('penjamin_id')->constrained('penjamins')->onDelete('cascade');
            $table->decimal('harga', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_radiologis');
    }
};
