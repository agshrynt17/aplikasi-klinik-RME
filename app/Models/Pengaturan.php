<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = [
        'nama_klinik', 'alamat', 'telepon', 'email', 'website', 'kode_faskes', 'logo',
    ];
}
