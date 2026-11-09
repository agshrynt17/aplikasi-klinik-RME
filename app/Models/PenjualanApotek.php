<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanApotek extends Model
{
    protected $table = 'penjualan_apoteks';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total_harga'       => 'decimal:2',
        'diskon'            => 'decimal:2',
        'pembulatan'        => 'decimal:2',
        'grand_total'       => 'decimal:2',
        'dibayar'           => 'decimal:2',
        'kembalian'         => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanApotekDetail::class, 'penjualan_apotek_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Pegawai::class, 'dokter_id');
    }

    public function gudang()
    {
        return $this->belongsTo(MasterGudang::class, 'master_gudang_id');
    }
}