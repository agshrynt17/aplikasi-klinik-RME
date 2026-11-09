<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pasien extends Model
{
    protected $table = 'pasiens';

    protected $guarded = ['id'];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_domisili_sama'=> 'boolean',
        'is_meninggal'    => 'boolean',
        'tanggal_lahir'   => 'date',
        'tanggal_kematian'=> 'date',
    ];

    public function getUsiaLengkapAttribute()
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        $now = Carbon::now();
        $diff = $this->tanggal_lahir->diff($now);
        return "{$diff->y} thn {$diff->m} bln {$diff->d} hr";
    }

    public function getUsiaTahunAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        return $this->tanggal_lahir->age;
    }

    public function skrinings()
    {
        return $this->hasMany(Skrining::class, 'pasien_id');
    }

    public function getKlusterAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 'Dewasa';
        }
        $age = $this->usia_tahun;
        if ($age < 6) return 'Balita & Anak Prasekolah';
        if ($age <= 18) return 'Anak Usia Sekolah dan Remaja';
        if ($age < 60) return 'Dewasa';
        return 'Lansia';
    }
}
