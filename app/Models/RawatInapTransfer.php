<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawatInapTransfer extends Model
{
    use HasFactory;

    protected $table = 'rawat_inap_transfers';

    protected $fillable = [
        'pendaftaran_id',
        'kamar_ranap_id',
        'waktu_masuk',
        'waktu_keluar',
        'lama_hari',
        'tarif_per_hari',
        'total_biaya_kamar',
        'catatan',
    ];

    protected $casts = [
        'waktu_masuk'       => 'datetime',
        'waktu_keluar'      => 'datetime',
        'tarif_per_hari'    => 'decimal:2',
        'total_biaya_kamar' => 'decimal:2',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function kamarRanap()
    {
        return $this->belongsTo(KamarRanap::class, 'kamar_ranap_id');
    }
}

