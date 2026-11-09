<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterGudang extends Model
{
    protected $table = 'master_gudangs';
    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'lokasi',
        'keterangan',
        'is_active'
    ];
}
