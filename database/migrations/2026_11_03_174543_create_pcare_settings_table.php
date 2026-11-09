<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pcare_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_setting')->unique();
            $table->text('nilai_setting')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('can_edit')->default(false);
            $table->boolean('is_secret')->default(false); // untuk masking password
            $table->timestamps();
        });

        // Seed data 16 setting PCare sesuai sistem PROFASKES
        $settings = [
            ['nama_setting' => 'base_url',            'nilai_setting' => 'https://apijkn.bpjs-kesehatan.go.id',          'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'base_url_rbk',        'nilai_setting' => 'https://apijkn.bpjs-kesehatan.go.id',          'deskripsi' => 'Khusus untuk rujukan RBK',                                                                 'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'cabang',               'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'cid',                  'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'faskes_rujukan_rayon', 'nilai_setting' => '0',                                             'deskripsi' => 'Pencarian faskes rujukan berdasarkan rayonisasi',                                          'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'kode_aplikasi',        'nilai_setting' => '095',                                           'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'kode_faskes',          'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'print_rujukan',        'nilai_setting' => 'aktif',                                         'deskripsi' => 'Isi "aktif" untuk mengaktifkan print rujukan, "non-aktif" untuk mematikan print rujukan',  'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'regional',             'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'rest_pass',            'nilai_setting' => '',                                              'deskripsi' => 'Password Aplikasi Pcare',                                                                  'can_edit' => true,  'is_secret' => true],
            ['nama_setting' => 'rest_user',            'nilai_setting' => '',                                              'deskripsi' => 'Username Aplikasi Pcare',                                                                  'can_edit' => true,  'is_secret' => true],
            ['nama_setting' => 'secret_key',           'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'service_name',         'nilai_setting' => 'pcare-rest',                                    'deskripsi' => null,                                                                                       'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'service_name_rbk',     'nilai_setting' => 'pcare-simrs-nrshapiv1',                         'deskripsi' => 'Khusus untuk rujukan RBK',                                                                 'can_edit' => false, 'is_secret' => false],
            ['nama_setting' => 'url_kunjungan',        'nilai_setting' => 'kunjunganv1',                                   'deskripsi' => 'Isi "kunjunganv1" jika status binding kunjungan v1',                                       'can_edit' => true,  'is_secret' => false],
            ['nama_setting' => 'user_key',             'nilai_setting' => '',                                              'deskripsi' => null,                                                                                       'can_edit' => true,  'is_secret' => false],
        ];

        foreach ($settings as $s) {
            DB::table('pcare_settings')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pcare_settings');
    }
};
