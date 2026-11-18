<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterAntrian;
use App\Models\Poliklinik;
use Illuminate\Http\Request;

class MasterAntrianController extends Controller
{
    public function index()
    {
        // Self-heal initial data if empty to match KONHAKES default
        if (MasterAntrian::count() === 0) {
            $poliUmum = Poliklinik::where('nama_poli', 'Klinik Umum 1')->first() 
                     ?? Poliklinik::where('nama_poli', 'LIKE', '%Umum%')->first();
            $poliGigi = Poliklinik::where('nama_poli', 'Klinik Gigi')->first() 
                     ?? Poliklinik::where('nama_poli', 'LIKE', '%Gigi%')->first();
            $poliKia  = Poliklinik::where('nama_poli', 'Klinik KIA')->first() 
                     ?? Poliklinik::where('nama_poli', 'LIKE', '%KIA%')->first()
                     ?? Poliklinik::where('nama_poli', 'LIKE', '%Kebidanan%')->first();

            $defaults = [
                [
                    'kode_antrian'  => '0',
                    'nama_antrian'  => 'PENDAFTARAN',
                    'jenis'         => 'Umum',
                    'poliklinik_id' => null,
                    'sound_layanan' => 'default',
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => '1',
                    'nama_antrian'  => 'PEMERIKSAAN PERAWAT',
                    'jenis'         => 'Umum',
                    'poliklinik_id' => null,
                    'sound_layanan' => 'default',
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => '2',
                    'nama_antrian'  => 'PENYERAHAN OBAT',
                    'jenis'         => 'Umum',
                    'poliklinik_id' => null,
                    'sound_layanan' => 'default',
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => 'A',
                    'nama_antrian'  => 'POLI DOKTER',
                    'jenis'         => 'Poliklinik',
                    'poliklinik_id' => $poliUmum?->id,
                    'sound_layanan' => null,
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => 'B',
                    'nama_antrian'  => 'POLI DOKTER GIGI',
                    'jenis'         => 'Poliklinik',
                    'poliklinik_id' => $poliGigi?->id,
                    'sound_layanan' => null,
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => 'C',
                    'nama_antrian'  => 'POLI BIDAN',
                    'jenis'         => 'Poliklinik',
                    'poliklinik_id' => $poliKia?->id,
                    'sound_layanan' => null,
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => 'D',
                    'nama_antrian'  => 'POLI DOKTER HINGGIL',
                    'jenis'         => 'Poliklinik',
                    'poliklinik_id' => $poliUmum?->id,
                    'sound_layanan' => null,
                    'is_active'     => true,
                ],
                [
                    'kode_antrian'  => 'E',
                    'nama_antrian'  => 'POLI PRIBADI',
                    'jenis'         => 'Poliklinik',
                    'poliklinik_id' => $poliUmum?->id,
                    'sound_layanan' => null,
                    'is_active'     => true,
                ],
            ];

            foreach ($defaults as $item) {
                MasterAntrian::create($item);
            }
        }

        // Numerical codes (0, 1, 2...) first, then alphabetical (A, B, C...)
        $data = MasterAntrian::with('poliklinik')
            ->orderByRaw("CASE WHEN kode_antrian REGEXP '^[0-9]+$' THEN 1 ELSE 2 END, kode_antrian ASC")
            ->get();

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();

        return view('antrian.master_antrian', compact('data', 'polikliniks'));
    }

    public function create()
    {
        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();
        return view('antrian.master_antrian_form', [
            'item' => new MasterAntrian(),
            'polikliniks' => $polikliniks,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_antrian' => 'required|max:10',
            'nama_antrian' => 'required|max:150',
            'poliklinik_id' => 'nullable|exists:polikliniks,id',
        ]);

        $item = MasterAntrian::create([
            'kode_antrian'  => strtoupper(trim($request->kode_antrian)),
            'nama_antrian'  => strtoupper(trim($request->nama_antrian)),
            'jenis'         => $request->poliklinik_id ? 'Poliklinik' : 'Umum',
            'poliklinik_id' => $request->poliklinik_id ?: null,
            'sound_layanan' => $request->poliklinik_id ? null : ($request->sound_layanan ?? 'default'),
            'is_active'     => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        $item->load('poliklinik');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Layanan antrian berhasil ditambahkan.',
                'data'    => $item,
            ]);
        }

        return redirect('/master-antrian')->with('success', 'Layanan antrian berhasil ditambahkan.');
    }

    public function edit($id, Request $request)
    {
        $item = MasterAntrian::with('poliklinik')->findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }

        $polikliniks = Poliklinik::where('is_active', true)->orderBy('nama_poli')->get();

        return view('antrian.master_antrian_form', [
            'item' => $item,
            'polikliniks' => $polikliniks,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_antrian' => 'required|max:10',
            'nama_antrian' => 'required|max:150',
            'poliklinik_id' => 'nullable|exists:polikliniks,id',
        ]);

        $item = MasterAntrian::findOrFail($id);
        $item->update([
            'kode_antrian'  => strtoupper(trim($request->kode_antrian)),
            'nama_antrian'  => strtoupper(trim($request->nama_antrian)),
            'jenis'         => $request->poliklinik_id ? 'Poliklinik' : 'Umum',
            'poliklinik_id' => $request->poliklinik_id ?: null,
            'sound_layanan' => $request->poliklinik_id ? null : ($request->sound_layanan ?? $item->sound_layanan ?? 'default'),
            'is_active'     => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        $item->load('poliklinik');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Layanan antrian berhasil diperbarui.',
                'data'    => $item,
            ]);
        }

        return redirect('/master-antrian')->with('success', 'Layanan antrian berhasil diperbarui.');
    }

    public function updateSound(Request $request, $id)
    {
        $request->validate([
            'sound_layanan' => 'required|string|max:255',
        ]);

        $item = MasterAntrian::findOrFail($id);
        $item->update([
            'sound_layanan' => $request->sound_layanan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sound layanan berhasil diubah.',
            'data'    => $item,
        ]);
    }

    public function destroy($id, Request $request)
    {
        $item = MasterAntrian::findOrFail($id);
        $item->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Layanan antrian berhasil dihapus.']);
        }

        return redirect('/master-antrian')->with('success', 'Layanan antrian berhasil dihapus.');
    }
}
