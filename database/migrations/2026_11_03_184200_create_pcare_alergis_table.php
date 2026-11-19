<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_alergis', function (Blueprint $table) {
            $table->id();
            $table->string('kd_jenis', 10);
            $table->string('nm_jenis', 50);
            $table->string('kd_alergi', 10);
            $table->string('nm_alergi', 255);
            $table->string('kd_alergi_reff', 255)->nullable();
            $table->timestamps();
        });

        // Seed data 18 rows
        $data = [
            // Makanan
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '00', 'nm_alergi' => 'Tidak Ada',         'kd_alergi_reff' => 'Tidak Ada'],
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '01', 'nm_alergi' => 'Seafood',           'kd_alergi_reff' => 'Seafood'],
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '02', 'nm_alergi' => 'Gandum',            'kd_alergi_reff' => 'Gandum'],
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '03', 'nm_alergi' => 'Susu Sapi',         'kd_alergi_reff' => 'Susu sapi'],
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '04', 'nm_alergi' => 'Kacang-Kacangan',  'kd_alergi_reff' => 'Kacang-kacangan'],
            ['kd_jenis' => '01', 'nm_jenis' => '01-Makanan', 'kd_alergi' => '05', 'nm_alergi' => 'Makanan Lain',      'kd_alergi_reff' => 'Makanan Lainnya'],
            // Udara
            ['kd_jenis' => '02', 'nm_jenis' => '02-Udara',   'kd_alergi' => '00', 'nm_alergi' => 'Tidak Ada',         'kd_alergi_reff' => 'Tidak ada'],
            ['kd_jenis' => '02', 'nm_jenis' => '02-Udara',   'kd_alergi' => '01', 'nm_alergi' => 'Udara Panas',       'kd_alergi_reff' => 'Udara Panas'],
            ['kd_jenis' => '02', 'nm_jenis' => '02-Udara',   'kd_alergi' => '02', 'nm_alergi' => 'Udara Dingin',      'kd_alergi_reff' => 'Udara Dingin'],
            ['kd_jenis' => '02', 'nm_jenis' => '02-Udara',   'kd_alergi' => '03', 'nm_alergi' => 'Udara Kotor',       'kd_alergi_reff' => 'Udara Kotor'],
            // Obat
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '00', 'nm_alergi' => 'Tidak Ada',         'kd_alergi_reff' => 'Tidak Ada'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '01', 'nm_alergi' => 'Antibiotik',        'kd_alergi_reff' => 'Antibiotik'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '02', 'nm_alergi' => 'Antiinflamasi',     'kd_alergi_reff' => 'Antiinflamasi'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '03', 'nm_alergi' => 'Non Steroid',       'kd_alergi_reff' => 'Non Steroid'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '04', 'nm_alergi' => 'Aspirin',           'kd_alergi_reff' => 'Aspirin'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '05', 'nm_alergi' => 'Kortikosteroid',    'kd_alergi_reff' => 'Kortikosteroid'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '06', 'nm_alergi' => 'Insulin',           'kd_alergi_reff' => 'Insulin'],
            ['kd_jenis' => '03', 'nm_jenis' => '03-Obat',    'kd_alergi' => '07', 'nm_alergi' => 'Obat Lainnya',      'kd_alergi_reff' => 'Obat Lainnya'],
        ];

        foreach ($data as $row) {
            DB::table('pcare_alergis')->insert(array_merge($row, [
                'created_at' => '2024-08-06 14:49:15',
                'updated_at' => '2024-08-06 14:49:15',
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_alergis');
    }
};

