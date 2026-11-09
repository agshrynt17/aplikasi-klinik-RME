<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skrining extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'nama_skrining',
        'skrining_nyeri',
        'skor_nyeri',
        'skrining_jatuh',
        'resiko_jatuh',
        'skrining_gizi',
        'status_alergi',
        'detail_alergi',
        'kesimpulan_skrining',
        'status',
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
