<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcareUser extends Model
{
    use HasFactory;

    protected $table = 'icare_users';

    protected $fillable = [
        'pegawai_id',
        'kode_nik',
        'nama_karyawan',
        'user_icare',
        'password_icare',
        'diubah_pada',
    ];

    protected $casts = [
        'diubah_pada' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}

