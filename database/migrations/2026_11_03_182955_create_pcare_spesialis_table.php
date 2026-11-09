<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_spesialis', function (Blueprint $table) {
            $table->id();
            $table->string('kd_spesialis', 20)->unique();
            $table->string('nm_spesialis');
            $table->integer('jumlah_sub')->default(0);
            $table->timestamps();
        });

        $list = [
            ['kd' => '155', 'nm' => 'Sport, shoulder and elbow', 'sub' => 0],
            ['kd' => '192', 'nm' => 'Pelayanan aritmia', 'sub' => 0],
            ['kd' => 'ANA', 'nm' => 'ANAK', 'sub' => 16],
            ['kd' => 'AND', 'nm' => 'ANDROLOGI', 'sub' => 1],
            ['kd' => 'ANT', 'nm' => 'ANASTESI', 'sub' => 8],
            ['kd' => 'BDA', 'nm' => 'BEDAH ANAK', 'sub' => 1],
            ['kd' => 'BDM', 'nm' => 'GIGI BEDAH MULUT', 'sub' => 1],
            ['kd' => 'BDP', 'nm' => 'BEDAH PLASTIK', 'sub' => 7],
            ['kd' => 'BED', 'nm' => 'BEDAH', 'sub' => 4],
            ['kd' => 'BSY', 'nm' => 'BEDAH SARAF', 'sub' => 7],
            ['kd' => 'BTK', 'nm' => 'BEDAH TORAKS KARDIOVASKULER', 'sub' => 5],
            ['kd' => 'FOR', 'nm' => 'FORENSIK', 'sub' => 0],
            ['kd' => 'GIG', 'nm' => 'GIGI', 'sub' => 7],
            ['kd' => 'GIZ', 'nm' => 'GIZI KLINIK', 'sub' => 1],
            ['kd' => 'INT', 'nm' => 'PENYAKIT DALAM', 'sub' => 14],
            ['kd' => 'JAN', 'nm' => 'JANTUNG DAN PEMBULUH DARAH', 'sub' => 6],
            ['kd' => 'JIW', 'nm' => 'KEDOKTERAN JIWA', 'sub' => 5],
            ['kd' => 'KDK', 'nm' => 'KEDOKTERAN KELAUTAN', 'sub' => 0],
            ['kd' => 'KDN', 'nm' => 'KEDOKTERAN NUKLIR', 'sub' => 1],
            ['kd' => 'KDO', 'nm' => 'KEDOKTERAN OLAHRAGA', 'sub' => 0],
            ['kd' => 'KDP', 'nm' => 'KEDOKTERAN PENERBANGAN', 'sub' => 0],
            ['kd' => 'KLN', 'nm' => 'KEDOKTERAN LINGKUNGAN', 'sub' => 0],
            ['kd' => 'KLT', 'nm' => 'KULIT DAN KELAMIN', 'sub' => 8],
            ['kd' => 'KOK', 'nm' => 'KEDOKTERAN OKUPASI', 'sub' => 1],
            ['kd' => 'MAT', 'nm' => 'MATA', 'sub' => 9],
            ['kd' => 'MIC', 'nm' => 'MIKROBIOLOGI KLINIK', 'sub' => 1],
            ['kd' => 'NEU', 'nm' => 'NEUROLOGI', 'sub' => 8],
            ['kd' => 'OBG', 'nm' => 'OBSTETRI DAN GINEKOLOGI', 'sub' => 7],
            ['kd' => 'ORT', 'nm' => 'ORTOPEDI DAN TRAUMATOLOGI', 'sub' => 7],
            ['kd' => 'PAR', 'nm' => 'PARU', 'sub' => 5],
            ['kd' => 'PAT', 'nm' => 'PATOLOGI ANATOMI', 'sub' => 3],
            ['kd' => 'PKL', 'nm' => 'PATOLOGI KLINIK', 'sub' => 3],
            ['kd' => 'RAD', 'nm' => 'RADIOLOGI', 'sub' => 4],
            ['kd' => 'RDM', 'nm' => 'REHABILITASI MEDIK', 'sub' => 3],
            ['kd' => 'THT', 'nm' => 'THT-KL', 'sub' => 6],
            ['kd' => 'URO', 'nm' => 'UROLOGI', 'sub' => 5],
            ['kd' => 'AKP', 'nm' => 'AKUPUNKTUR', 'sub' => 1],
            ['kd' => 'FAR', 'nm' => 'FARMAKOLOGI KLINIK', 'sub' => 1],
        ];

        foreach ($list as $item) {
            DB::table('pcare_spesialis')->insert([
                'kd_spesialis' => $item['kd'],
                'nm_spesialis' => $item['nm'],
                'jumlah_sub'   => $item['sub'],
                'created_at'   => '2022-12-23 01:09:52',
                'updated_at'   => '2022-12-23 01:09:52',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_spesialis');
    }
};
