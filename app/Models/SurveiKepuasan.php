<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveiKepuasan extends Model
{
    protected $table = 'survei_kepuasans';

    protected $fillable = [
        'pendaftaran_id', 'pelayanan', 'fasilitas', 'dokter', 'komentar'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
