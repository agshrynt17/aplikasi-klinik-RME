<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_tindakans', function (Blueprint $table) {
            $table->id();
            $table->string('kd_tkp', 10)->default('10');
            $table->string('nm_tkp', 50)->default('Rawat Jalan');
            $table->string('kd_tindakan', 20)->index();
            $table->string('nm_tindakan');
            $table->bigInteger('max_tarif')->default(0);
            $table->bigInteger('with_value')->default(0);
            $table->string('kd_tindakan_reff')->nullable()->default('--Belum diatur--');
            $table->timestamps();
        });

        // Seed data standar PCare BPJS sesuai screenshot
        $items = [
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01005', 'nm' => 'Pelayanan KB - Pemasangan IUD / Implant',                    'max' => 100000, 'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01006', 'nm' => 'Pelayanan KB - Suntik',                                        'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01007', 'nm' => 'Pelayanan komplikasi KB pasca persalinan',                      'max' => 125000, 'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01023', 'nm' => 'Pelayanan ANC 1 (Satu)',                                        'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01024', 'nm' => 'Pelayanan ANC 2 (Dua)',                                         'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01025', 'nm' => 'Pelayanan ANC 3 (Tiga)',                                        'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01026', 'nm' => 'Pelayanan ANC 4 (Empat)',                                       'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01027', 'nm' => 'Pelayanan PNC 1 (Satu)',                                        'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01028', 'nm' => 'Pelayanan PNC 2 (Dua)',                                         'max' => 20000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01029', 'nm' => 'Pelayanan PNC 3 (Tiga)',                                        'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01030', 'nm' => 'Pelayanan PNC 4 (Empat)',                                       'max' => 25000,  'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01031', 'nm' => 'Pelayanan KB - Cabut IUD / Implant',                            'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '01032', 'nm' => 'Pelayanan KB - Cabut & Pemasangan IUD / Implant',               'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '02034', 'nm' => 'Pelayanan pra-rujukan pada komplikasi kebidanan dan neonatal',   'max' => 125000, 'wv' => 0],
            ['tkp' => '20', 'nm_tkp' => 'Rawat Inap',   'kd' => '02012', 'nm' => 'Paket Persalinan per Vaginam normal (oleh Bidan)',               'max' => 600000, 'wv' => 0],
            ['tkp' => '20', 'nm_tkp' => 'Rawat Inap',   'kd' => '02013', 'nm' => 'Paket Persalinan per Vaginam normal (oleh Dokter)',               'max' => 600000, 'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '03122', 'nm' => 'MOP / Vasektomi',                                               'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '03123', 'nm' => 'MOW / Tubektomi',                                               'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '04001', 'nm' => 'Pemeriksaan Gula Darah Sewaktu (GDS)',                           'max' => 0,      'wv' => 0],
            ['tkp' => '10', 'nm_tkp' => 'Rawat Jalan',  'kd' => '04002', 'nm' => 'Pemeriksaan Gula Darah Puasa (GDP)',                             'max' => 0,      'wv' => 0],
        ];

        foreach ($items as $item) {
            DB::table('pcare_tindakans')->insert([
                'kd_tkp'          => $item['tkp'],
                'nm_tkp'          => $item['nm_tkp'],
                'kd_tindakan'     => $item['kd'],
                'nm_tindakan'     => $item['nm'],
                'max_tarif'       => $item['max'],
                'with_value'      => $item['wv'],
                'kd_tindakan_reff'=> '--Belum diatur--',
                'created_at'      => '2022-12-23 01:10:38',
                'updated_at'      => '2022-12-23 01:10:38',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_tindakans');
    }
};

