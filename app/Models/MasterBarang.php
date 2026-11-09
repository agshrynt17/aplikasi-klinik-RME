<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    use HasFactory;

    protected $table = 'master_barangs';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jenis',
        'kemasan',
        'kelas_terapi',
        'golongan_obat',
        'bentuk_obat',
        'rute_obat',
        'hna',
        'minimum_stok',
        'is_formularium',
        'produsen',
        'jenis_produk_kfa',
        'kode_kfa',
        'nama_kfa',
        'no_batch',
        'kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'is_formularium' => 'boolean',
        'hna'            => 'decimal:2',
        'harga_beli'     => 'decimal:2',
        'harga_jual'     => 'decimal:2',
        'minimum_stok'   => 'integer',
        'stok'           => 'integer',
    ];

    public function batches()
    {
        return $this->hasMany(BatchBarang::class, 'master_barang_id');
    }

    public function stokGudangs()
    {
        return $this->hasMany(StokGudang::class, 'master_barang_id');
    }
}

