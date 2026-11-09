<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edukasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'edukator_id',
        'waktu_edukasi',
        'materi_edukasi',
        'metode_edukasi',
        'penerima_edukasi',
        'evaluasi_pemahaman',
        'keterangan',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function edukator()
    {
        return $this->belongsTo(Pegawai::class, 'edukator_id');
    }
}
