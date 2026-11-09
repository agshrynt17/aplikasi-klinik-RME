<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogKirimAntrian extends Model
{
    protected $table = 'log_kirim_antrians';

    protected $fillable = [
        'kodebooking',
        'no_antrian',
        'sumber_antrian',
        'no_registrasi',
        'tanggal_pendaftaran',
        'nama_pasien',
        'no_rm',
        'no_kartu',
        'nik',
        'nama_poli',
        'nama_dokter',
        'jam_praktek',
        'status_kirim_antrian',
        'status_kehadiran',
        'keterangan',
        'task_id',
        'waktu_kirim',
        'request_payload',
        'response_api',
        'status_berhasil'
    ];

    protected $casts = [
        'status_berhasil'     => 'boolean',
        'waktu_kirim'         => 'datetime',
        'tanggal_pendaftaran' => 'date',
    ];
}
