<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    protected $table = 'lantais';
    protected $fillable = ['gedung_id', 'kode_lantai', 'nama_lantai', 'keterangan', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function ruangans()
    {
        return $this->hasMany(Ruangan::class);
    }
}
