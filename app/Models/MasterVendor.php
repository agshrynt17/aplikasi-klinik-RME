<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterVendor extends Model
{
    use HasFactory;

    protected $table = 'master_vendors';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'join_date',
        'alamat',
        'telp',
        'no_telepon',
        'fax',
        'email',
        'nomor_spk',
        'expired_spk',
        'nama_pic',
        'telp_pic',
        'email_pic',
        'kontak_person',
        'is_active',
    ];

    protected $casts = [
        'join_date'   => 'date',
        'expired_spk' => 'date',
        'is_active'   => 'boolean',
    ];
}
