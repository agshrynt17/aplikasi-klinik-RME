<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('icare_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('kode_nik')->nullable();
            $table->string('nama_karyawan');
            $table->string('user_icare')->nullable();
            $table->string('password_icare')->nullable();
            $table->dateTime('diubah_pada')->nullable();
            $table->timestamps();
        });

        // Seed initial 12 records matching screenshot
        $seedData = [
            [
                'kode_nik' => '1971041103870003',
                'nama_karyawan' => 'dr. FARISI',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3207365903960004',
                'nama_karyawan' => 'dr. Sheila Asyiah Granitya',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3213240112940001',
                'nama_karyawan' => 'dr. Harry Putra Pratama',
                'user_icare' => 'iCare-drHarry',
                'password_icare' => '******',
                'diubah_pada' => '2024-09-19 08:55:07',
            ],
            [
                'kode_nik' => '3215130508880001',
                'nama_karyawan' => 'dr. Fahmi Rizal',
                'user_icare' => 'fahmirizaldr',
                'password_icare' => '******',
                'diubah_pada' => '2026-09-24 07:59:19',
            ],
            [
                'kode_nik' => '3215135105940004',
                'nama_karyawan' => 'Dr Rani Puspita',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3215135711850006',
                'nama_karyawan' => 'drg. Tia Novianti',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3215250508840005',
                'nama_karyawan' => 'dr. Syaiful Bachtiar',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3215296003560001',
                'nama_karyawan' => 'drg. SAMSIYARNI',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3277033110970001',
                'nama_karyawan' => 'dr. VIERTA AJI NUR YASIN',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3278064912960012',
                'nama_karyawan' => 'dr. Della Alifah Kusuma',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3573057004890002',
                'nama_karyawan' => 'drg APRIANI DEVY MEILITA',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
            [
                'kode_nik' => '3671090601950001',
                'nama_karyawan' => 'dr Galang Bela Nusa',
                'user_icare' => null,
                'password_icare' => null,
                'diubah_pada' => null,
            ],
        ];

        $now = now();
        foreach ($seedData as $row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            DB::table('icare_users')->insert($row);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('icare_users');
    }
};

