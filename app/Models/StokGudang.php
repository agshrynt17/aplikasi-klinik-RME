<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    protected $table = 'stok_gudangs';

    protected $fillable = [
        'master_gudang_id',
        'master_barang_id',
        'stok',
        'stok_awal',
    ];

    protected $casts = [
        'stok'      => 'integer',
        'stok_awal' => 'integer',
    ];

    public function masterGudang()
    {
        return $this->belongsTo(MasterGudang::class, 'master_gudang_id');
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }
}
