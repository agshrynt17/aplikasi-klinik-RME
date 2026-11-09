<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesmenIgd extends Model
{
    protected $table = 'asesmen_igds';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'tanggal_asesmen',
        'skala_triage', 'jalan_napas', 'pernapasan', 'sirkulasi', 'kesadaran',
        'keluhan_utama', 'riwayat_penyakit',
        'tensi', 'nadi', 'suhu', 'rr',
        'pemeriksaan_fisik', 'diagnosis', 'tindakan_rencana', 'dokter_id'
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
