<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_khususes', function (Blueprint $table) {
            $table->id();
            $table->string('kd_khusus', 20)->index();
            $table->string('nm_khusus');
            $table->string('kd_khusus_reff')->nullable()->default('(belum diset)');
            $table->timestamps();
        });

        $items = [
            ['kd' => 'IGD', 'nm' => 'ALIH RAWAT',            'reff' => '(belum diset)'],
            ['kd' => 'HDL', 'nm' => 'HEMODIALISA',           'reff' => '(belum diset)'],
            ['kd' => 'HEM', 'nm' => 'HEMOFILI',              'reff' => '(belum diset)'],
            ['kd' => 'HIV', 'nm' => 'HIV-ODHA',              'reff' => '(belum diset)'],
            ['kd' => 'JIW', 'nm' => 'JIWA',                  'reff' => '(belum diset)'],
            ['kd' => 'KLT', 'nm' => 'KUSTA',                 'reff' => '(belum diset)'],
            ['kd' => 'KEM', 'nm' => 'SARANA KEMOTERAPI',     'reff' => '(belum diset)'],
            ['kd' => 'RAT', 'nm' => 'SARANA RADIOTERAPI',    'reff' => '(belum diset)'],
            ['kd' => 'PAR', 'nm' => 'TB-MDR',                'reff' => '(belum diset)'],
            ['kd' => 'THA', 'nm' => 'THALASEMIA',            'reff' => '(belum diset)'],
        ];

        foreach ($items as $item) {
            DB::table('pcare_khususes')->insert([
                'kd_khusus'      => $item['kd'],
                'nm_khusus'      => $item['nm'],
                'kd_khusus_reff' => $item['reff'],
                'created_at'     => '2023-09-14 03:45:59',
                'updated_at'     => '2023-09-14 03:45:59',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_khususes');
    }
};

