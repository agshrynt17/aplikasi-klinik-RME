<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPelanggan extends Model
{
    protected $table = 'master_pelanggans';
    
    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'tanggal_lahir',
        'alamat',
        'kode_prop',
        'kode_kab',
        'kode_kec',
        'kode_desa',
        'telp',
        'no_telepon',
        'jenis_kelamin',
        'email',
    ];
}
