<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosaKeperawatan extends Model
{
    protected $table = 'diagnosa_keperawatans';
    protected $fillable = ['kode_diagnosa', 'nama_diagnosa', 'keterangan', 'is_active'];
}
