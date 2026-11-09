<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilLab extends Model
{
    protected $table = 'hasil_labs';

    protected $fillable = [
        'transaksi_lab_id', 'nilai_hasil', 'satuan',
        'nilai_normal_text', 'flag', 'catatan',
        'diverifikasi_oleh', 'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function transaksiLab()
    {
        return $this->belongsTo(TransaksiLab::class, 'transaksi_lab_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(Pegawai::class, 'diverifikasi_oleh');
    }
}
