<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PraBedah extends Model
{
    protected $table = 'pra_bedahs';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id', 'waktu_pengkajian',
        'diagnosa_pra_bedah', 'rencana_tindakan', 'riwayat_penyakit',
        'persiapan_darah', 'puasa_mulai',
        'tensi', 'nadi', 'suhu', 'rr', 'pemeriksaan_penunjang',
        'resiko_operasi', 'keterangan'
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
