<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaObatPenjamin extends Model
{
    protected $table = 'harga_obat_penjamins';

    protected $fillable = [
        'penjamin_id',
        'master_barang_id',
        'harga_jual',
        'margin_persen',
    ];

    protected $casts = [
        'harga_jual'    => 'decimal:2',
        'margin_persen' => 'decimal:2',
    ];

    public function penjamin()
    {
        return $this->belongsTo(Penjamin::class, 'penjamin_id');
    }

    public function masterBarang()
    {
        return $this->belongsTo(MasterBarang::class, 'master_barang_id');
    }
}
