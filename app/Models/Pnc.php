<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pnc extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'tanggal_persalinan',
        'hari_ke',
        'keluhan_utama',
        'tekanan_darah',
        'nadi',
        'respirasi',
        'suhu',
        'payudara_laktasi',
        'tfu',
        'kontraksi_uterus',
        'lochea',
        'perineum',
        'kandung_kemih',
        'masalah_diagnosa',
        'tatalaksana',
        'edukasi',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
}
