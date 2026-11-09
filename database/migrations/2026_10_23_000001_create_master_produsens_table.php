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
        if (!Schema::hasTable('master_produsens')) {
            Schema::create('master_produsens', function (Blueprint $table) {
                $table->id();
                $table->string('kode_produsen', 50)->unique();
                $table->string('nama_produsen');
                $table->string('telp', 50)->nullable();
                $table->string('email', 100)->nullable();
                $table->text('alamat')->nullable();
                $table->string('kontak_person', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_produsens');
    }
};
