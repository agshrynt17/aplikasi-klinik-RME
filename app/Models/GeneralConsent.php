<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralConsent extends Model
{
    protected $table = 'general_consents';

    protected $fillable = [
        'pendaftaran_id', 'nama_pj', 'hubungan_pj', 'no_hp_pj', 
        'alamat_pj', 'ttd_pj', 'ttd_petugas'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
