<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedungs';
    protected $fillable = ['kode_gedung', 'nama_gedung', 'keterangan', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lantais()
    {
        return $this->hasMany(Lantai::class);
    }
}
