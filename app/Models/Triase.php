<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Triase extends Model
{
    protected $table = 'triases';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'pegawai_id', 'waktu_triase',
        'cara_datang', 'asal_rujukan',
        'jalan_napas', 'pernapasan', 'sirkulasi', 'kesadaran',
        'keluhan_utama', 'kategori_triase',
        'tensi', 'nadi', 'suhu', 'rr', 'spo2', 'gcs', 'keterangan'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
