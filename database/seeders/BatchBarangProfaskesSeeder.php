<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BatchBarang;
use App\Models\MasterBarang;
use App\Models\MasterGudang;
use Carbon\Carbon;

class BatchBarangProfaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gudangFarmasi = MasterGudang::firstOrCreate(
            ['nama_gudang' => 'Gudang Farmasi'],
            ['kode_gudang' => 'GUD-001', 'lokasi' => 'Lantai 1 - Instalasi Farmasi', 'is_active' => true]
        );

        $barangs = MasterBarang::orderBy('id')->get();

        if ($barangs->isEmpty()) {
            return;
        }

        $batchesData = [
            [
                'kode_barang' => 'ANFEK-000002',
                'nomor_batch' => 'BCH-2024-A01',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(18)->format('Y-m-d'),
                'stok' => 50,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-002828',
                'nomor_batch' => 'BCH-2024-B02',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(24)->format('Y-m-d'),
                'stok' => 25,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-001700',
                'nomor_batch' => 'BCH-2024-C03',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(12)->format('Y-m-d'),
                'stok' => 100,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-001701',
                'nomor_batch' => 'BCH-2024-D04',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(14)->format('Y-m-d'),
                'stok' => 80,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-002700',
                'nomor_batch' => 'BCH-2024-E05',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(20)->format('Y-m-d'),
                'stok' => 150,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-002393',
                'nomor_batch' => 'BCH-2024-F06',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(10)->format('Y-m-d'),
                'stok' => 40,
                'is_active' => true,
            ],
            [
                'kode_barang' => 'ANFEK-002871',
                'nomor_batch' => 'BCH-2024-G07',
                'tanggal_kadaluarsa' => Carbon::now()->addMonths(16)->format('Y-m-d'),
                'stok' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($batchesData as $b) {
            $barang = $barangs->firstWhere('kode_barang', $b['kode_barang']);
            if ($barang) {
                BatchBarang::updateOrCreate(
                    [
                        'master_barang_id' => $barang->id,
                        'nomor_batch'      => $b['nomor_batch'],
                    ],
                    [
                        'master_gudang_id'   => $gudangFarmasi->id,
                        'tanggal_kadaluarsa' => $b['tanggal_kadaluarsa'],
                        'stok'               => $b['stok'],
                        'is_active'          => $b['is_active'],
                        'created_at'         => Carbon::now()->subDays(rand(1, 30)),
                        'updated_at'         => Carbon::now()->subHours(rand(1, 48)),
                    ]
                );
            }
        }
    }
}
