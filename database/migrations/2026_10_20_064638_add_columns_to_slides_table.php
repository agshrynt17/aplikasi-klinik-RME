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
        Schema::table('slides', function (Blueprint $table) {
            $table->string('judul')->nullable()->change();
            $table->text('url_media')->nullable()->change();
            $table->integer('durasi')->nullable()->default(5)->after('tipe');
            $table->string('tampilkan_di')->default('Semua Layanan')->after('durasi');
            $table->text('tampilkan_layanan_ids')->nullable()->after('tampilkan_di');
            $table->text('text_slide')->nullable()->after('tampilkan_layanan_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropColumn(['durasi', 'tampilkan_di', 'tampilkan_layanan_ids', 'text_slide']);
        });
    }
};
