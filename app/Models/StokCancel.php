<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokCancel extends Model
{
    protected $table = 'stok_cancels';

    protected $fillable = [
        'cancel_no',
        'cancel_tanggal',
        'kode_gudang',
        'master_gudang_id',
        'cancel_type',
        'keterangan',
        'user_id',
        'created_at',
    ];

    protected $casts = [
        'cancel_tanggal' => 'date',
    ];

    public function masterGudang()
    {
        return $this->belongsTo(MasterGudang::class, 'master_gudang_id');
    }

    public function details()
    {
        return $this->hasMany(StokCancelDetail::class, 'stok_cancel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
