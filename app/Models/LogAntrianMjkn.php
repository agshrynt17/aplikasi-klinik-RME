<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAntrianMjkn extends Model
{
    protected $table = 'log_antrian_mjkns';

    protected $fillable = [
        'nomorkartu',
        'nama_pasien',
        'jenis_pasien',
        'status_antrian',
        'no_registrasi',
        'nik',
        'nohp',
        'kodepoli',
        'nama_poli',
        'tanggalperiksa',
        'jam_praktek',
        'kodedokter',
        'nama_dokter',
        'nomorantrean',
        'angkaantrean',
        'keluhan',
        'is_batal',
        'estimasidilayani',
        'status_sinkronisasi',
        'pesan_error'
    ];

    protected $casts = [
        'status_sinkronisasi' => 'boolean',
        'is_batal'            => 'boolean',
        'tanggalperiksa'      => 'date',
    ];
}
