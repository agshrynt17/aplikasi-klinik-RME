<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PraAnestesi extends Model
{
    protected $table = 'pra_anestesis';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id', 'waktu_pengkajian',
        'riwayat_alergi', 'riwayat_penyakit', 'riwayat_operasi', 'riwayat_anestesi',
        'tensi', 'nadi', 'suhu', 'rr', 'tb', 'bb',
        'jalan_napas', 'pemeriksaan_fisik', 'pemeriksaan_penunjang',
        'asa_score', 'rencana_anestesi', 'keterangan'
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
