<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiLab extends Model
{
    protected $table = 'transaksi_labs';

    protected $fillable = [
        'pendaftaran_id', 'master_lab_id', 'analis_id',
        'tarif', 'qty', 'subtotal', 'status', 'keterangan'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function masterLab()
    {
        return $this->belongsTo(MasterLab::class, 'master_lab_id');
    }

    public function analis()
    {
        return $this->belongsTo(Pegawai::class, 'analis_id');
    }

    public function hasilLab()
    {
        return $this->hasOne(HasilLab::class, 'transaksi_lab_id');
    }
}
