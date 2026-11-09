<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTindakan extends Model
{
    protected $table = 'transaksi_tindakans';

    protected $fillable = [
        'pendaftaran_id', 'tindakan_id', 'pelaksana_id', 'tarif', 'qty', 'subtotal'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class);
    }

    public function pelaksana()
    {
        return $this->belongsTo(Pegawai::class, 'pelaksana_id');
    }
}
