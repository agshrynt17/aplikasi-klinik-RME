<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 100);
            $table->string('nama_setting', 255);
            $table->string('key', 100)->unique();
            $table->string('nilai', 255)->default('');
            $table->string('tipe_input', 50)->default('select'); // select, boolean, text
            $table->json('opsi')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
