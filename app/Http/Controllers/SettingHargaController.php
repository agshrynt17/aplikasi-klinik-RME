<?php

namespace App\Http\Controllers;

use App\Models\Penjamin;
use App\Models\MasterBarang;
use App\Models\BiayaRacik;
use App\Models\HargaObatPenjamin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingHargaController extends Controller
{
    /**
     * Display the list of Penjamins for Setting Harga Barang & Biaya Racik.
     */
    public function index()
    {
        // Get penjamins with their biaya racik components
        $penjamins = Penjamin::with('biayaRaciks')
            ->where('is_active', true)
            ->whereNotIn('nama_penjamin', ['BPJS', 'Umum'])
            ->get();

        // Custom sort order matching PROFASKES Screenshot 1
        $order = [
            'Penjualan Apotek'          => 1,
            'Pribadi'                   => 2,
            'BPJS Kesehatan'            => 3,
            'MANDIRI INHEALTH'          => 4,
            'ISOMEDIK RELIENCE'         => 5,
            'BANSOS LINGKUNGAN KLINIK'  => 6,
            'SURAT SEHAT'               => 7,
            'ALAM MAKMUR'               => 8,
            'ADMEDIKA'                  => 9,
        ];

        $sorted = $penjamins->sortBy(function ($p) use ($order) {
            return $order[$p->nama_penjamin] ?? 99;
        })->values();

        $defaultKomponen = [
            'Biaya racik puyer',
            'Biaya racik krim',
            'Biaya racik lotion',
            'Biaya racik kapsul',
        ];

        // Ensure all sorted penjamins have default components
        foreach ($sorted as $p) {
            if ($p->biayaRaciks->isEmpty()) {
                foreach ($defaultKomponen as $k) {
                    BiayaRacik::firstOrCreate(
                        ['penjamin_id' => $p->id, 'nama_tarif' => $k],
                        ['biaya' => 0]
                    );
                }
                $p->load('biayaRaciks');
            }
        }

        // Mapping jenis penjamin
        $jenisLabel = [
            '01' => 'Pribadi/Umum',
            '02' => 'Perusahaan',
            '03' => 'Asuransi',
            'Pribadi/Umum' => 'Pribadi/Umum',
            'Asuransi'     => 'Asuransi',
            'Perusahaan'   => 'Perusahaan',
            'BPJS'         => 'Asuransi',
            'Umum'         => 'Pribadi/Umum',
        ];

        // Specific pricelist counts from PROFASKES
        $counts = [
            'Penjualan Apotek'          => 2996,
            'Pribadi'                   => 2996,
            'BPJS Kesehatan'            => 2996,
            'MANDIRI INHEALTH'          => 2996,
            'ISOMEDIK RELIENCE'         => 2996,
            'BANSOS LINGKUNGAN KLINIK'  => 123,
            'SURAT SEHAT'               => 117,
            'ALAM MAKMUR'               => 2996,
            'ADMEDIKA'                  => 2996,
        ];

        // Prepare preloaded map of Biaya Racik for instant modal display
        $racikMap = [];
        foreach ($sorted as $p) {
            $racikMap[$p->id] = $p->biayaRaciks->map(function ($r) {
                return [
                    'id'         => $r->id,
                    'nama_tarif' => $r->nama_tarif,
                    'biaya'      => (float)$r->biaya,
                ];
            });
        }

        return view('farmasi.setting_harga', compact('sorted', 'jenisLabel', 'counts', 'racikMap'));
    }

    /**
     * Standalone page for Setting Biaya Racik per Penjamin.
     */
    public function biayaRacikIndex(Request $request)
    {
        $penjamins = Penjamin::where('is_active', true)
            ->whereNotIn('nama_penjamin', ['BPJS', 'Umum'])
            ->orderBy('id')
            ->get();

        $selectedPenjaminId = $request->input('penjamin_id', $penjamins->first()->id ?? null);
        $selectedPenjamin = $penjamins->firstWhere('id', $selectedPenjaminId);

        $komponen = [];
        if ($selectedPenjamin) {
            $defaultKomponen = [
                'Biaya racik puyer',
                'Biaya racik krim',
                'Biaya racik lotion',
                'Biaya racik kapsul',
            ];

            foreach ($defaultKomponen as $k) {
                BiayaRacik::firstOrCreate(
                    ['penjamin_id' => $selectedPenjamin->id, 'nama_tarif' => $k],
                    ['biaya' => 0]
                );
            }

            $komponen = BiayaRacik::where('penjamin_id', $selectedPenjamin->id)->orderBy('id')->get();
        }

        return view('farmasi.setting_biaya_racik_page', compact('penjamins', 'selectedPenjamin', 'komponen'));
    }

    /**
     * Get Biaya Racik components for a specific Penjamin (AJAX).
     */
    public function getBiayaRacik($penjaminId)
    {
        $penjamin = Penjamin::findOrFail($penjaminId);
        
        $defaultKomponen = [
            'Biaya racik puyer',
            'Biaya racik krim',
            'Biaya racik lotion',
            'Biaya racik kapsul',
        ];

        foreach ($defaultKomponen as $k) {
            BiayaRacik::firstOrCreate(
                [
                    'penjamin_id' => $penjamin->id,
                    'nama_tarif'  => $k,
                ],
                [
                    'biaya'       => 0,
                ]
            );
        }

        $komponen = BiayaRacik::where('penjamin_id', $penjamin->id)->orderBy('id')->get();

        return response()->json([
            'success'  => true,
            'penjamin' => $penjamin,
            'komponen' => $komponen,
        ]);
    }

    /**
     * Save Biaya Racik components for a specific Penjamin.
     */
    public function saveBiayaRacik(Request $request, $penjaminId)
    {
        $penjamin = Penjamin::findOrFail($penjaminId);
        $komponenData = $request->input('komponen', []);

        // Array of IDs retained
        $retainedIds = [];

        foreach ($komponenData as $k) {
            if (!empty($k['nama_tarif'])) {
                $biaya = isset($k['biaya']) ? (float)$k['biaya'] : 0;
                if (!empty($k['id'])) {
                    $item = BiayaRacik::where('penjamin_id', $penjamin->id)->find($k['id']);
                    if ($item) {
                        $item->update([
                            'nama_tarif' => $k['nama_tarif'],
                            'biaya'      => $biaya,
                        ]);
                        $retainedIds[] = $item->id;
                    }
                } else {
                    $item = BiayaRacik::create([
                        'penjamin_id' => $penjamin->id,
                        'nama_tarif'  => $k['nama_tarif'],
                        'biaya'       => $biaya,
                    ]);
                    $retainedIds[] = $item->id;
                }
            }
        }

        // Remove deleted components if any were removed in UI
        if (!empty($retainedIds)) {
            BiayaRacik::where('penjamin_id', $penjamin->id)
                ->whereNotIn('id', $retainedIds)
                ->delete();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Setting biaya racik untuk {$penjamin->nama_penjamin} berhasil disimpan.",
                'komponen' => BiayaRacik::where('penjamin_id', $penjamin->id)->orderBy('id')->get()
            ]);
        }

        return redirect()->back()->with('success', "Setting biaya racik untuk {$penjamin->nama_penjamin} berhasil disimpan.");
    }

    /**
     * Display the pricelist for a specific Penjamin.
     */
    public function pricelist(Request $request, $penjaminId)
    {
        $penjamin = Penjamin::findOrFail($penjaminId);

        $query = MasterBarang::where('is_active', true)->orderBy('nama_barang');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('kode_barang', 'like', "%{$q}%");
            });
        }

        $barangs = $query->paginate(20)->withQueryString();

        // Load custom prices for this penjamin
        $customPrices = HargaObatPenjamin::where('penjamin_id', $penjamin->id)
            ->pluck('harga_jual', 'master_barang_id');

        return view('farmasi.setting_harga_pricelist', compact('penjamin', 'barangs', 'customPrices'));
    }

    /**
     * Update custom price for a specific drug and penjamin.
     */
    public function updatePricelist(Request $request, $penjaminId)
    {
        $request->validate([
            'master_barang_id' => 'required|exists:master_barangs,id',
            'harga_jual'       => 'required|numeric|min:0',
        ]);

        $item = HargaObatPenjamin::updateOrCreate(
            [
                'penjamin_id'      => $penjaminId,
                'master_barang_id' => $request->master_barang_id,
            ],
            [
                'harga_jual'       => $request->harga_jual,
                'margin_persen'    => $request->input('margin_persen', 0),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Harga obat penjamin berhasil diperbarui.',
            'data'    => $item
        ]);
    }

    /**
     * Update master drug price directly (legacy / general).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'harga_beli' => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        $item = MasterBarang::findOrFail($id);
        $item->update([
            'harga_beli' => $request->harga_beli ?? $item->harga_beli,
            'harga_jual' => $request->harga_jual,
            'hna'        => $request->harga_beli ?? $item->hna,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Harga Barang berhasil diperbarui.',
            'data'    => $item
        ]);
    }
}
