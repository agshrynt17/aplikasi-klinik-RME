<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesmenUlang extends Model
{
    protected $table = 'asesmen_ulangs';
    
    protected $fillable = [
        'pendaftaran_id', 'pasien_id', 'tanggal_asesmen',
        'subjektif', 'objektif', 'asesmen', 'plan', 'instruksi_medis', 'pegawai_id'
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
