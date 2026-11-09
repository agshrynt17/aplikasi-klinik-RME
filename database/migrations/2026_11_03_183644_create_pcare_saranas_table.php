<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_saranas', function (Blueprint $table) {
            $table->id();
            $table->string('kd_sarana', 20)->index();
            $table->string('nm_sarana');
            $table->string('kd_sarana_reff')->nullable();
            $table->timestamps();
        });

        $items = [
            ['kd' => '205', 'nm' => 'ABR/BERA (Brain Evoke Respon Audiometer)', 'reff' => '(belum diset)'],
            ['kd' => '176', 'nm' => 'Abrasive device and accessories/Alat poles', 'reff' => '(belum diset)'],
            ['kd' => '175', 'nm' => 'Acrylic trimmer bur', 'reff' => '(belum diset)'],
            ['kd' => '631', 'nm' => 'Acupunture therapy', 'reff' => '(belum diset)'],
            ['kd' => '198', 'nm' => 'Adenotonsilektomi sluder & diseksi set', 'reff' => '(belum diset)'],
            ['kd' => '574', 'nm' => 'Agregometer', 'reff' => '(belum diset)'],
            ['kd' => '258', 'nm' => 'Alat biopsi lengkap', 'reff' => '(belum diset)'],
            ['kd' => '788', 'nm' => 'Alat Continous renal replacement therapy (CRRT)', 'reff' => '(belum diset)'],
            ['kd' => '139', 'nm' => 'Alat ekstraksi (Tang Anterior, Tang Premolar, Tang Bayonet, Tang Posterior) set anak', 'reff' => null],
            ['kd' => '140', 'nm' => 'Alat ekstraksi (Tang Anterior, Tang Premolar, Tang Bayonet, Tang Posterior) set dewasa', 'reff' => null],
            ['kd' => '173', 'nm' => 'Alat endodontic dasar', 'reff' => '(belum diset)'],
            ['kd' => '674', 'nm' => 'Alat Latihan Sensori', 'reff' => '(belum diset)'],
            ['kd' => '526', 'nm' => 'Alat partus set', 'reff' => '(belum diset)'],
            ['kd' => '249', 'nm' => 'Alat pembuka gips elektrik (Cast removal instrument)', 'reff' => '(belum diset)'],
            ['kd' => '250', 'nm' => 'Alat pembuka gips manual (Manual cast application and removal instrument)', 'reff' => '(belum diset)'],
            ['kd' => '531', 'nm' => 'Alat pemecah selaput ketuban atau 1/2 kocher', 'reff' => '(belum diset)'],
            ['kd' => '177', 'nm' => 'Alat peraga penyuluhan kesehatan gigi', 'reff' => '(belum diset)'],
            ['kd' => '251', 'nm' => 'Alat potong gips (Cast cutter)', 'reff' => '(belum diset)'],
            ['kd' => '252', 'nm' => 'Alat retraksi bedah ortopedi', 'reff' => '(belum diset)'],
            ['kd' => '253', 'nm' => 'Alat rontgen portabel', 'reff' => '(belum diset)'],
        ];

        foreach ($items as $item) {
            DB::table('pcare_saranas')->insert([
                'kd_sarana'      => $item['kd'],
                'nm_sarana'      => $item['nm'],
                'kd_sarana_reff' => $item['reff'],
                'created_at'     => '2022-12-23 01:10:03',
                'updated_at'     => '2022-12-23 01:10:03',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_saranas');
    }
};
