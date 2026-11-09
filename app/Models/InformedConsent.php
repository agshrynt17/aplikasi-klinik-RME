<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformedConsent extends Model
{
    protected $table = 'informed_consents';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'dokter_id', 'jenis_tindakan',
        'tanggal_persetujuan', 'status_persetujuan', 'pihak_menyatakan',
        'nama_wali', 'hubungan_wali', 'keterangan', 'saksi_keluarga', 'saksi_rs'
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
