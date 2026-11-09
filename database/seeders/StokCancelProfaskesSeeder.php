<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StokCancel;
use App\Models\StokCancelDetail;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use Carbon\Carbon;

class StokCancelProfaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudangFarmasi = MasterGudang::firstOrCreate(
            ['nama_gudang' => 'Gudang Farmasi'],
            ['kode_gudang' => '001', 'lokasi' => 'Lantai 1 - Instalasi Farmasi', 'is_active' => true]
        );

        $cancel = StokCancel::updateOrCreate(
            ['cancel_no' => '1'],
            [
                'cancel_tanggal'   => '2024-02-20',
                'kode_gudang'      => '001',
                'master_gudang_id' => $gudangFarmasi->id,
                'cancel_type'      => '01',
                'keterangan'       => 'Pembatalan transaksi penyesuaian stok opname',
                'created_at'       => Carbon::parse('2024-02-20 07:48:47'),
                'updated_at'       => Carbon::parse('2024-02-20 07:48:47'),
            ]
        );

        $barang1 = MasterBarang::where('kode_barang', 'BRO25-000001')->first() ?? MasterBarang::first();
        $barang2 = MasterBarang::where('kode_barang', 'ANHEM-000006')->first() ?? MasterBarang::skip(1)->first();

        if ($barang1) {
            StokCancelDetail::updateOrCreate(
                [
                    'stok_cancel_id'   => $cancel->id,
                    'master_barang_id' => $barang1->id,
                ],
                [
                    'jumlah'     => 10,
                    'satuan'     => $barang1->kemasan ?? 'Tablet',
                    'keterangan' => 'Salah input jumlah fisik saat stok opname',
                ]
            );
        }

        if ($barang2) {
            StokCancelDetail::updateOrCreate(
                [
                    'stok_cancel_id'   => $cancel->id,
                    'master_barang_id' => $barang2->id,
                ],
                [
                    'jumlah'     => 5,
                    'satuan'     => $barang2->kemasan ?? 'Tablet',
                    'keterangan' => 'Koreksi item ganda',
                ]
            );
        }
    }
}
