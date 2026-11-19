<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamarRanap extends Model
{
    use HasFactory;

    protected $table = 'kamar_ranaps';

    protected $fillable = [
        'ruangan_id',
        'nama_kamar',
        'nomor_bed',
        'kelas',
        'tarif_per_hari',
        'status',
        'pendaftaran_id',
        'waktu_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tarif_per_hari' => 'decimal:2',
        'waktu_masuk'    => 'datetime',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function transfers()
    {
        return $this->hasMany(RawatInapTransfer::class, 'kamar_ranap_id');
    }
}

