<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiRadiologi extends Model
{
    protected $table = 'transaksi_radiologis';

    protected $fillable = [
        'pendaftaran_id', 'master_radiologi_id', 'radiografer_id',
        'subtotal', 'status', 'keterangan'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function masterRadiologi()
    {
        return $this->belongsTo(MasterRadiologi::class, 'master_radiologi_id');
    }

    public function radiografer()
    {
        return $this->belongsTo(Pegawai::class, 'radiografer_id');
    }

    public function hasilRadiologi()
    {
        return $this->hasOne(HasilRadiologi::class, 'transaksi_radiologi_id');
    }
}
