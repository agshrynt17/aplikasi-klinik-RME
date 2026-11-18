<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TarifRadiologi;
use App\Models\MasterRadiologi;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifRadiologiController extends Controller
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
        // Urutkan persis sesuai KONHAKES screenshot (00000, P0000, P0001, dst)
        $data = $query->orderBy('kode_penjamin', 'asc')->get();

        // Cek penjamin mana yang sudah diset tarif radiologinya
        $tarifCountMap = TarifRadiologi::select('penjamin_id', DB::raw('count(*) as count'))
            ->groupBy('penjamin_id')
            ->pluck('count', 'penjamin_id')
            ->toArray();

        // Penjamins list untuk modal copy/import tarif
        $allPenjamins = Penjamin::where('is_active', true)->whereNotIn('kode_penjamin', ['BPJS', 'UMUM'])->orderBy('kode_penjamin')->get();

        return view('radiologi.tarif_radiologi', compact('data', 'totalCount', 'tarifCountMap', 'allPenjamins'));
    }

    // API: Ambil data tarif radiologi untuk penjamin tertentu (digunakan oleh modal Setting Tarif)
    public function getTarifByPenjamin(Request $request, $penjamin_id)
    {
        $penjamin = Penjamin::findOrFail($penjamin_id);

        // Ambil pemeriksaan radiologi: utamakan yang aktif terlebih dahulu
        $radiologis = MasterRadiologi::orderBy('is_active', 'desc')
            ->orderBy('id', 'asc')
            ->take(100)
            ->get();

        $tarifMap = TarifRadiologi::where('penjamin_id', $penjamin_id)
            ->pluck('harga', 'master_radiologi_id')
            ->toArray();

        $result = $radiologis->map(function($rad) use ($tarifMap) {
            $harga = $tarifMap[$rad->id] ?? ($rad->unitcost > 0 ? $rad->unitcost : 130000);
            return [
                'master_radiologi_id' => $rad->id,
                'kode_rad'            => $rad->kode_rad,
                'nama_pemeriksaan'    => $rad->nama_pemeriksaan,
                'kategori'            => $rad->kategori ?: '(belum diset)',
                'golongan'            => $rad->golongan ?: 'Radiologi Konvensional',
                'unitcost'            => $rad->unitcost,
                'is_active'           => $rad->is_active,
                'harga'               => $harga,
                'sudah_diset'         => isset($tarifMap[$rad->id]),
            ];
        });

        return response()->json([
            'success'  => true,
            'penjamin' => $penjamin,
            'data'     => $result,
        ]);
    }

    // API: Simpan batch tarif untuk penjamin
    public function saveBatchTarif(Request $request)
    {
        $penjamin_id = $request->penjamin_id;
        $items = $request->items; // array of ['master_radiologi_id' => x, 'harga' => y]

        if (!$penjamin_id) {
            return response()->json(['success' => false, 'message' => 'Penjamin tidak valid.'], 422);
        }

        if (is_array($items)) {
            foreach ($items as $item) {
                if (isset($item['master_radiologi_id']) && isset($item['harga'])) {
                    TarifRadiologi::updateOrCreate(
                        [
                            'master_radiologi_id' => $item['master_radiologi_id'],
                            'penjamin_id'         => $penjamin_id,
                        ],
                        [
                            'harga'     => (float)str_replace(',', '', $item['harga']),
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tarif radiologi untuk penjamin berhasil disimpan.',
        ]);
    }

    // API: Salin / Import tarif radiologi dari penjamin lain
    public function importTarif(Request $request)
    {
        $targetPenjaminId = $request->target_penjamin_id;
        $sourcePenjaminId = $request->source_penjamin_id;

        if (!$targetPenjaminId || !$sourcePenjaminId) {
            return response()->json(['success' => false, 'message' => 'Data penjamin sumber dan target harus dipilih.'], 422);
        }

        $sourceTarifs = TarifRadiologi::where('penjamin_id', $sourcePenjaminId)->get();

        if ($sourceTarifs->isEmpty()) {
            // Gunakan unitcost dari master radiologi
            $masters = MasterRadiologi::where('is_active', true)->get();
            foreach ($masters as $m) {
                TarifRadiologi::updateOrCreate(
                    [
                        'master_radiologi_id' => $m->id,
                        'penjamin_id'         => $targetPenjaminId,
                    ],
                    [
                        'harga'     => $m->unitcost > 0 ? $m->unitcost : 130000,
                        'is_active' => true,
                    ]
                );
            }
        } else {
            foreach ($sourceTarifs as $st) {
                TarifRadiologi::updateOrCreate(
                    [
                        'master_radiologi_id' => $st->master_radiologi_id,
                        'penjamin_id'         => $targetPenjaminId,
                    ],
                    [
                        'harga'     => $st->harga,
                        'is_active' => true,
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tarif radiologi berhasil diimport/disalin.',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_radiologi_id' => 'required|exists:master_radiologis,id',
            'penjamin_id'         => 'required|exists:penjamins,id',
            'harga'               => 'required|numeric|min:0',
        ]);

        $item = TarifRadiologi::updateOrCreate(
            [
                'master_radiologi_id' => $request->master_radiologi_id,
                'penjamin_id'         => $request->penjamin_id,
            ],
            [
                'harga'     => $request->harga,
                'is_active' => $request->has('is_active') ? true : false,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Tarif Radiologi berhasil disimpan.',
            'data'    => $item->load(['masterRadiologi', 'penjamin']),
        ]);
    }

    public function edit($id)
    {
        return response()->json(TarifRadiologi::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
        ]);

        $item = TarifRadiologi::findOrFail($id);
        $item->update([
            'harga'     => $request->harga,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarif Radiologi berhasil diperbarui.',
            'data'    => $item->load(['masterRadiologi', 'penjamin']),
        ]);
    }

    public function destroy($id)
    {
        TarifRadiologi::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Tarif Radiologi berhasil dihapus.']);
    }
}
