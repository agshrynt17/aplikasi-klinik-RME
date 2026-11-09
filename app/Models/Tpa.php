<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tpa extends Model
{
    protected $table = 'tpas';

    protected $fillable = [
        'kode_tpa',
        'nama_tpa',
        'alamat',
        'telp',
        'fax',
        'email',
        'join_date',
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
        'join_date'    => 'date',
        'expired_date' => 'date',
    ];
}
