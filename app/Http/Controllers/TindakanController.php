<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tindakan;
use App\Models\Poliklinik;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    private function seedDefaultsIfNeeded()
    {
        if (Tindakan::count() < 14) {
            \Artisan::call('db:seed', ['--class' => 'TindakanKonhakesSeeder']);
        }
    }

    public function getNextKode()
    {
        $items = Tindakan::where('kode_tindakan', 'LIKE', 'TK%')->pluck('kode_tindakan');
        $maxNum = 0;
        foreach ($items as $code) {
            if (preg_match('/^TK(\d+)$/i', $code, $m)) {
                $num = intval($m[1]);
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $nextNum = $maxNum > 0 ? $maxNum + 1 : 94;
        return 'TK' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $this->seedDefaultsIfNeeded();
        $profil = ProfilFaskes::first();
        $polikliniks = Poliklinik::orderBy('nama_poli', 'asc')->get();

        $query = Tindakan::with('poliklinik');

        if ($request->filled('poliklinik_id')) {
            $query->where('poliklinik_id', $request->poliklinik_id);
        }

        if ($request->filled('nama_tindakan')) {
            $q = $request->nama_tindakan;
            $query->where(function ($w) use ($q) {
                $w->where('nama_tindakan', 'like', "%{$q}%")
                  ->orWhere('kode_tindakan', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === '1' || $request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === '0' || $request->status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('is_ranap')) {
            $query->where('is_ranap', (bool)$request->is_ranap);
        }

        // Custom sort to match KONHAKES screenshot order (TK035 HECTING 2 first, etc.)
        $data = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();

        return view('tindakan.master_tindakan', compact('data', 'profil', 'polikliniks'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        $polikliniks = Poliklinik::orderBy('nama_poli', 'asc')->get();
        $nextKode = $this->getNextKode();

        return view('tindakan.master_tindakan_create', compact('profil', 'polikliniks', 'nextKode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tindakan' => 'required|max:255',
        ]);

        $kode = $request->filled('kode_tindakan') ? strtoupper($request->kode_tindakan) : $this->getNextKode();

        if (Tindakan::where('kode_tindakan', $kode)->exists()) {
            $kode = $this->getNextKode();
        }

        $bhp        = floatval($request->bhp ?? 0);
        $jasa       = floatval($request->jasa_petugas ?? 0);
        $gedung     = floatval($request->penyusutan_gedung ?? 0);
        $alat       = floatval($request->penyusutan_alat ?? 0);
        $unitCost   = $bhp + $jasa + $gedung + $alat;

        $isActive   = $request->has('is_active') ? (bool) $request->is_active : true;
        $isRanap    = $request->has('is_ranap') ? (bool) $request->is_ranap : true;

        $item = Tindakan::create([
            'poliklinik_id'       => $request->poliklinik_id ?: null,
            'kode_tindakan'       => $kode,
            'nama_tindakan'       => $request->nama_tindakan,
            'is_konsultasi'       => (bool) $request->is_konsultasi,
            'is_informed_consent' => (bool) $request->is_informed_consent,
            'is_ranap'            => $isRanap,
            'bhp'                 => $bhp,
            'jasa_petugas'        => $jasa,
            'penyusutan_gedung'   => $gedung,
            'penyusutan_alat'     => $alat,
            'unit_cost'           => $unitCost,
            'is_active'           => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Data Tindakan berhasil disimpan.',
                'redirect' => '/master-tindakan',
                'data'     => $item,
            ]);
        }

        return redirect('/master-tindakan')->with('success', 'Data Tindakan berhasil disimpan.');
    }

    public function edit($id, Request $request)
    {
        $tindakan = Tindakan::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($tindakan);
        }

        $profil = ProfilFaskes::first();
        $polikliniks = Poliklinik::orderBy('nama_poli', 'asc')->get();
        $isEdit = true;

        return view('tindakan.master_tindakan_create', compact('tindakan', 'isEdit', 'profil', 'polikliniks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tindakan' => 'required|max:255',
        ]);

        $item = Tindakan::findOrFail($id);

        $bhp      = floatval($request->bhp ?? 0);
        $jasa     = floatval($request->jasa_petugas ?? 0);
        $gedung   = floatval($request->penyusutan_gedung ?? 0);
        $alat     = floatval($request->penyusutan_alat ?? 0);
        $unitCost = $bhp + $jasa + $gedung + $alat;

        $isActive = $request->has('is_active') ? (bool) $request->is_active : $item->is_active;
        $isRanap  = $request->has('is_ranap') ? (bool) $request->is_ranap : $item->is_ranap;

        $item->update([
            'poliklinik_id'       => $request->poliklinik_id ?: null,
            'kode_tindakan'       => $request->kode_tindakan ?? $item->kode_tindakan,
            'nama_tindakan'       => $request->nama_tindakan,
            'is_konsultasi'       => (bool) $request->is_konsultasi,
            'is_informed_consent' => (bool) $request->is_informed_consent,
            'is_ranap'            => $isRanap,
            'bhp'                 => $bhp,
            'jasa_petugas'        => $jasa,
            'penyusutan_gedung'   => $gedung,
            'penyusutan_alat'     => $alat,
            'unit_cost'           => $unitCost,
            'is_active'           => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Data Tindakan berhasil diperbarui.',
                'redirect' => '/master-tindakan',
                'data'     => $item->fresh(),
            ]);
        }

        return redirect('/master-tindakan')->with('success', 'Data Tindakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Tindakan::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Tindakan berhasil dihapus.',
        ]);
    }
}
