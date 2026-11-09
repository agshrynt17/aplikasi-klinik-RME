<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilRadiologi extends Model
{
    protected $table = 'hasil_radiologis';

    protected $fillable = [
        'transaksi_radiologi_id', 'hasil_ekspertise', 'file_gambar',
        'dokter_pemeriksa_id', 'waktu_verifikasi'
    ];

    protected $casts = [
        'waktu_verifikasi' => 'datetime',
    ];

    public function transaksiRadiologi()
    {
        return $this->belongsTo(TransaksiRadiologi::class, 'transaksi_radiologi_id');
    }

    public function dokterPemeriksa()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_pemeriksa_id');
    }
}
