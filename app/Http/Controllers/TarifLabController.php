<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TarifLab;
use App\Models\MasterLab;
use App\Models\Penjamin;
use Illuminate\Http\Request;

class TarifLabController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjamin::where('is_active', true)->whereNotIn('kode_penjamin', ['BPJS', 'UMUM']);

        if ($request->filled('kode_penjamin')) {
            $term = $request->kode_penjamin;
            $query->where(function($q) use ($term) {
                $q->where('kode_penjamin', 'like', "%{$term}%")
                  ->orWhere('nama_penjamin', 'like', "%{$term}%");
            });
        }

        $totalCount = $query->count();
        // Sesuai screenshot KONHAKES menampilkan 9 penjamin
        $data = $query->orderBy('kode_penjamin', 'asc')->get();

        $labs = MasterLab::where('is_active', true)->orderBy('nama_pemeriksaan')->get();

        return view('laboratorium.tarif_lab', compact('data', 'totalCount', 'labs'));
    }

    // API: Ambil daftar tarif per penjamin (satuan atau paket)
    public function getTarifByPenjamin(Request $request, $penjamin_id)
    {
        $tipe = $request->get('tipe', 'Tunggal'); // 'Tunggal' = Satuan, 'Paket' = Paket

        $labs = MasterLab::where('is_active', true)
                    ->where('kelompok_lab', $tipe)
                    ->orderBy('nama_pemeriksaan')
                    ->get();

        $tarifMap = TarifLab::where('penjamin_id', $penjamin_id)
                        ->pluck('harga', 'master_lab_id')
                        ->toArray();

        $penjamin = Penjamin::findOrFail($penjamin_id);

        $result = $labs->map(function($lab) use ($tarifMap) {
            return [
                'master_lab_id' => $lab->id,
                'kode_lab'      => $lab->kode_lab,
                'nama_lab'      => $lab->nama_pemeriksaan,
                'golongan_lab'  => $lab->golongan_lab ?: $lab->kategori,
                'kelompok_lab'  => $lab->kelompok_lab,
                'unitcost'      => $lab->unitcost,
                'harga'         => $tarifMap[$lab->id] ?? ($lab->unitcost > 0 ? $lab->unitcost : 25000),
            ];
        });

        return response()->json([
            'success'  => true,
            'penjamin' => $penjamin,
            'tipe'     => $tipe,
            'data'     => $result,
        ]);
    }

    // Simpan batch tarif untuk suatu penjamin
    public function saveBatchTarif(Request $request)
    {
        $penjamin_id = $request->penjamin_id;
        $items = $request->items; // array of ['master_lab_id' => x, 'harga' => y]

        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['master_lab_id']) && isset($item['harga'])) {
                    TarifLab::updateOrCreate(
                        [
                            'master_lab_id' => $item['master_lab_id'],
                            'penjamin_id'   => $penjamin_id,
                        ],
                        [
                            'harga'     => $item['harga'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tarif laboratorium per penjamin berhasil disimpan.',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_lab_id' => 'required|exists:master_labs,id',
            'penjamin_id'   => 'nullable|exists:penjamins,id',
            'harga'         => 'required|numeric|min:0',
        ]);

        $item = TarifLab::updateOrCreate(
            [
                'master_lab_id' => $request->master_lab_id,
                'penjamin_id'   => $request->penjamin_id ?: null,
            ],
            [
                'harga'     => $request->harga,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data Tarif Lab berhasil disimpan.',
            'data'    => $item,
        ]);
    }
}
