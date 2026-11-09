<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokAdjustment extends Model
{
    protected $table = 'stok_adjustments';

    protected $fillable = [
        'no_penyesuaian',
        'tanggal_penyesuaian',
        'master_gudang_id',
        'kode_gudang',
        'tipe',
        'keterangan',
        'user_id',
        'user_name',
    ];

    protected $casts = [
        'tanggal_penyesuaian' => 'date',
    ];

    public function masterGudang()
    {
        return $this->belongsTo(MasterGudang::class, 'master_gudang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(StokAdjustmentDetail::class, 'stok_adjustment_id');
    }
}
