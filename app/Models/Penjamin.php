<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjamin extends Model
{
    protected $table = 'penjamins';

    protected $fillable = [
        'kode_penjamin',
        'nama_penjamin',
        'jenis_penjamin',
        'telp',
        'is_default',
        'join_date',
        'alamat',
        'fax',
        'email',
        'nomor_spk',
        'expired_date',
        'nama_pic',
        'telp_pic',
        'email_pic',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_default'   => 'boolean',
        'join_date'    => 'date',
        'expired_date' => 'date',
    ];

    public function biayaRaciks()
    {
        return $this->hasMany(BiayaRacik::class, 'penjamin_id');
    }

    public function hargaObats()
    {
        return $this->hasMany(HargaObatPenjamin::class, 'penjamin_id');
    }
}

