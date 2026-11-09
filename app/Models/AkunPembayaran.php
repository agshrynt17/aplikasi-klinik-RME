<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunPembayaran extends Model
{
    protected $table = 'akun_pembayarans';

    protected $fillable = [
        'kode_akun', 'nama_akun', 'jenis_akun', 'no_rekening', 'atas_nama', 'keterangan', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
