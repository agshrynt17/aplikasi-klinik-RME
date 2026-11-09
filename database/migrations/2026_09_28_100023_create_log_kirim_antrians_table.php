<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_kirim_antrians', function (Blueprint $table) {
            $table->id();
            $table->string('kodebooking', 50);
            $table->integer('task_id'); // 1-7 (waktu tunggu BPJS)
            $table->timestamp('waktu_kirim')->useCurrent();
            $table->text('request_payload')->nullable();
            $table->text('response_api')->nullable();
            $table->boolean('status_berhasil')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_kirim_antrians');
    }
};
