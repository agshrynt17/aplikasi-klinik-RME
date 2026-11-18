<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TarifTindakan;
use App\Models\Tindakan;
use App\Models\Penjamin;
use App\Models\Poliklinik;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class TarifTindakanController extends Controller
{
    private function seedDefaultsIfNeeded()
    {
        if (TarifTindakan::count() < 14) {
            \Artisan::call('db:seed', ['--class' => 'TarifTindakanKonhakesSeeder']);
        }
    }

    public function index(Request $request)
    {
        $this->seedDefaultsIfNeeded();
        $profil = ProfilFaskes::first();
        $polikliniks = Poliklinik::orderBy('nama_poli', 'asc')->get();
        $penjamins = Penjamin::where('id', '!=', 4)->orderBy('kode_penjamin', 'asc')->get();

        $query = TarifTindakan::with(['tindakan.poliklinik', 'penjamin']);

        if ($request->filled('poliklinik_id')) {
            $query->whereHas('tindakan', function ($q) use ($request) {
                $q->where('poliklinik_id', $request->poliklinik_id);
            });
        }

        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }

        if ($request->filled('nama_tindakan')) {
            $q = $request->nama_tindakan;
            $query->whereHas('tindakan', function ($sq) use ($q) {
                $sq->where('nama_tindakan', 'like', "%{$q}%")
                   ->orWhere('kode_tindakan', 'like', "%{$q}%");
            });
        }

        $data = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();

        return view('tindakan.master_tarif_tindakan', compact('data', 'profil', 'polikliniks', 'penjamins'));
    }

    public function create(Request $request)
    {
        $profil = ProfilFaskes::first();
        $penjamins = Penjamin::where('id', '!=', 4)->orderBy('kode_penjamin', 'asc')->get();
        $tindakans = Tindakan::with('poliklinik')->orderBy('id', 'asc')->get();

        // Get default tariffs from Pribadi (penjamin_id = 1)
        $defaultTarifs = TarifTindakan::where('penjamin_id', 1)->pluck('margin', 'tindakan_id')->toArray();

        return view('tindakan.master_tarif_tindakan_create', compact('profil', 'penjamins', 'tindakans', 'defaultTarifs'));
    }

    public function store(Request $request)
    {
        // Batch tariff store from create page
        if ($request->has('items') && is_array($request->items)) {
            $penjaminId = $request->penjamin_id;
            if (!$penjaminId) {
                return redirect()->back()->with('error', 'Silakan pilih penjamin terlebih dahulu.');
            }

            foreach ($request->items as $tindakanId => $row) {
                $unitCost = floatval($row['unit_cost'] ?? 0);
                $jasaMedis = floatval($row['jasa_medis'] ?? 0);
                $jasaParamedis = floatval($row['jasa_paramedis'] ?? 0);
                $margin = floatval($row['margin'] ?? 0);
                $totalTarif = $unitCost + $jasaMedis + $jasaParamedis + $margin;

                TarifTindakan::updateOrCreate(
                    [
                        'tindakan_id' => $tindakanId,
                        'penjamin_id' => $penjaminId,
                    ],
                    [
                        'unit_cost'      => $unitCost,
                        'jasa_medis'     => $jasaMedis,
                        'jasa_paramedis' => $jasaParamedis,
                        'margin'         => $margin,
                        'total_tarif'    => $totalTarif,
                        'harga'          => $totalTarif,
                        'is_active'      => 1,
                    ]
                );
            }

            return redirect('/master-tarif-tindakan')->with('success', 'Tarif Tindakan berhasil disimpan.');
        }

        // Single tariff store
        $request->validate([
            'tindakan_id' => 'required|exists:tindakans,id',
            'penjamin_id' => 'required|exists:penjamins,id',
        ]);

        $unitCost      = floatval($request->unit_cost ?? 0);
        $jasaMedis     = floatval($request->jasa_medis ?? 0);
        $jasaParamedis = floatval($request->jasa_paramedis ?? 0);
        $margin        = floatval($request->margin ?? 0);
        $totalTarif    = $unitCost + $jasaMedis + $jasaParamedis + $margin;

        $item = TarifTindakan::updateOrCreate(
            [
                'tindakan_id' => $request->tindakan_id,
                'penjamin_id' => $request->penjamin_id,
            ],
            [
                'unit_cost'      => $unitCost,
                'jasa_medis'     => $jasaMedis,
                'jasa_paramedis' => $jasaParamedis,
                'margin'         => $margin,
                'total_tarif'    => $totalTarif,
                'harga'          => $totalTarif,
                'is_active'      => $request->has('is_active') ? (bool) $request->is_active : true,
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Tarif Tindakan berhasil disimpan.',
                'data'    => $item,
            ]);
        }

        return redirect('/master-tarif-tindakan')->with('success', 'Data Tarif Tindakan berhasil disimpan.');
    }

    public function edit($id, Request $request)
    {
        $item = TarifTindakan::with(['tindakan', 'penjamin'])->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = TarifTindakan::findOrFail($id);

        $unitCost      = floatval($request->unit_cost ?? $item->unit_cost);
        $jasaMedis     = floatval($request->jasa_medis ?? $item->jasa_medis);
        $jasaParamedis = floatval($request->jasa_paramedis ?? $item->jasa_paramedis);
        $margin        = floatval($request->margin ?? $item->margin);
        $totalTarif    = $unitCost + $jasaMedis + $jasaParamedis + $margin;

        $item->update([
            'unit_cost'      => $unitCost,
            'jasa_medis'     => $jasaMedis,
            'jasa_paramedis' => $jasaParamedis,
            'margin'         => $margin,
            'total_tarif'    => $totalTarif,
            'harga'          => $totalTarif,
            'is_active'      => $request->has('is_active') ? (bool) $request->is_active : $item->is_active,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Tarif Tindakan berhasil diperbarui.',
                'data'    => $item,
            ]);
        }

        return redirect('/master-tarif-tindakan')->with('success', 'Data Tarif Tindakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = TarifTindakan::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Tarif Tindakan berhasil dihapus.',
        ]);
    }
}
