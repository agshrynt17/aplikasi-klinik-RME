<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcu extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'nadi',
        'suhu',
        'respirasi',
        'buta_warna',
        'golongan_darah',
        'visus_mata_kanan',
        'visus_mata_kiri',
        'pemeriksaan_fisik',
        'kesimpulan',
        'dokter_id',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }
}
