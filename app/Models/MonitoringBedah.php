<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringBedah extends Model
{
    protected $table = 'monitoring_bedahs';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id',
        'waktu_mulai', 'waktu_selesai',
        'cairan_masuk', 'cairan_keluar',
        'observasi_json', 'keterangan'
    ];
    
    protected $casts = [
        'observasi_json' => 'array',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }
}
