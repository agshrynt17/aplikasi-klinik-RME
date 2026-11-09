<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesmenAwalRajal extends Model
{
    protected $table = 'asesmen_awal_rajals';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'tanggal_asesmen',
        'keluhan_utama', 'riwayat_penyakit_sekarang', 'riwayat_penyakit_dahulu', 'riwayat_alergi',
        'tensi', 'nadi', 'suhu', 'rr', 'berat_badan', 'tinggi_badan', 'pemeriksaan_fisik',
        'diagnosis', 'tindakan_rencana', 'dokter_id'
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
