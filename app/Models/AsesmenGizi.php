<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesmenGizi extends Model
{
    protected $table = 'asesmen_gizis';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'tanggal_asesmen',
        'antropometri', 'biokimia', 'klinis_fisik', 'riwayat_makan', 'riwayat_personal',
        'diagnosis_gizi', 'intervensi_gizi', 'monitoring_evaluasi', 'pegawai_id'
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
