<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_tkps', function (Blueprint $table) {
            $table->id();
            $table->string('kd_tkp', 20)->unique();
            $table->string('nm_tkp');
            $table->timestamps();
        });

        // Seed 3 data standar PCare sesuai KONHAKES 1:1
        DB::table('pcare_tkps')->insert([
            [
                'kd_tkp'     => '10',
                'nm_tkp'     => 'Rawat Jalan',
                'created_at' => '2022-11-30 14:47:22',
                'updated_at' => '2022-11-30 14:47:22',
            ],
            [
                'kd_tkp'     => '20',
                'nm_tkp'     => 'Rawat Inap',
                'created_at' => '2022-11-30 14:47:31',
                'updated_at' => '2022-11-30 14:47:31',
            ],
            [
                'kd_tkp'     => '50',
                'nm_tkp'     => 'Promotif Preventif',
                'created_at' => '2022-11-30 14:47:40',
                'updated_at' => '2022-11-30 14:47:40',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_tkps');
    }
};
