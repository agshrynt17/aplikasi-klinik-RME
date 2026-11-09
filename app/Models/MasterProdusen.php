<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProdusen extends Model
{
    use HasFactory;

    protected $table = 'master_produsens';

    protected $fillable = [
        'kode_produsen',
        'nama_produsen',
        'telp',
        'fax',
        'email',
        'alamat',
        'kontak_person',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
