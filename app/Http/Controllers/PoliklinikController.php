<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Poliklinik;
use App\Models\UnitLayanan;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index(Request $request)
    {
        // Auto-seed sample polikliniks only when table is completely empty
        if (Poliklinik::count() === 0) {
            $defaultPolis = [
                ['kode_poli' => 'KL001', 'nama_poli' => 'Klinik Umum 1', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL002', 'nama_poli' => 'Klinik Gigi', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL003', 'nama_poli' => 'Apotek', 'unit' => 'Penunjang', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL004', 'nama_poli' => 'Klinik KIA', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL005', 'nama_poli' => 'Laboratorium', 'unit' => 'Penunjang', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => true, 'is_igd' => false],
                ['kode_poli' => 'KL006', 'nama_poli' => 'Kunjungan Online Sehat', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL007', 'nama_poli' => 'Home Visit', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL008', 'nama_poli' => 'Konseling', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL009', 'nama_poli' => 'Kunjungan Online Sakit', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL010', 'nama_poli' => 'Klinik Umum 2', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'KL011', 'nama_poli' => 'KLINIK UMUM 3', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK010', 'nama_poli' => 'Poli KB', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK011', 'nama_poli' => 'Imunisasi DPT', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK012', 'nama_poli' => 'Imunisasi Campak', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK013', 'nama_poli' => 'Senam Kelompok Prolanis', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK014', 'nama_poli' => 'UGD', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => true],
                ['kode_poli' => 'PK015', 'nama_poli' => 'Imunisasi Polio', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK016', 'nama_poli' => 'Imunisasi Hepatitis B', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK017', 'nama_poli' => 'Poli Persalinan', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK018', 'nama_poli' => 'Imunisasi BCG', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK019', 'nama_poli' => 'Edukasi Kelompok Prolanis', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sehat', 'is_lab' => false, 'is_igd' => false],
                ['kode_poli' => 'PK020', 'nama_poli' => 'Poli Refraksi', 'unit' => 'Pelayanan', 'wajib_ppa' => 'Ya', 'jenis_poli' => 'Poli Sakit', 'is_lab' => false, 'is_igd' => false]
            ];
            foreach ($defaultPolis as $p) {
                Poliklinik::updateOrCreate(['kode_poli' => $p['kode_poli']], $p);
            }
        }

        $query = Poliklinik::query();

        // Filter Unit
        if ($request->filled('kode_unit')) {
            $query->where('unit', $request->kode_unit);
        }

        // Filter Kode Poliklinik
        if ($request->filled('kode_poli')) {
            $query->where('kode_poli', 'LIKE', '%' . $request->kode_poli . '%');
        }

        // Filter Nama Poliklinik
        if ($request->filled('nama_poli')) {
            $query->where('nama_poli', 'LIKE', '%' . $request->nama_poli . '%');
        }

        $data = $query->orderBy('id', 'asc')->get();

        // Unit options
        $units = ['Pelayanan', 'Penunjang'];

        return view('master_poliklinik', compact('data', 'units'));
    }

    public function create()
    {
        $units = ['Pelayanan', 'Penunjang'];

        // Auto-generate next suggested kode_poli e.g. KL021
        $maxNum = 0;
        $allCodes = Poliklinik::where('kode_poli', 'LIKE', 'KL%')->pluck('kode_poli');
        foreach ($allCodes as $code) {
            if (preg_match('/^KL(\d+)$/i', $code, $m)) {
                $val = intval($m[1]);
                if ($val > $maxNum) {
                    $maxNum = $val;
                }
            }
        }
        $nextCode = 'KL' . str_pad($maxNum + 1, 3, '0', STR_PAD_LEFT);

        return view('poliklinik_create', compact('units', 'nextCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_poli'  => 'required|unique:polikliniks,kode_poli|max:20',
            'nama_poli'  => 'required|max:255',
            'unit'       => 'nullable|string',
            'wajib_ppa'  => 'nullable|string',
            'jenis_poli' => 'nullable|string',
        ]);

        $item = Poliklinik::create([
            'kode_poli'       => strtoupper($request->kode_poli),
            'nama_poli'       => $request->nama_poli,
            'unit'            => $request->unit ?? 'Pelayanan',
            'wajib_ppa'       => $request->wajib_ppa ?? 'Ya',
            'jenis_poli'      => $request->jenis_poli ?? 'Poli Sakit',
            'is_igd'          => $request->has('is_igd'),
            'is_rehab_medik'  => $request->has('is_rehab_medik'),
            'is_mcu'          => $request->has('is_mcu'),
            'is_lab'          => $request->has('is_lab'),
            'is_radiologi'    => $request->has('is_radiologi'),
            'is_active'       => true,
            'keterangan'      => $request->keterangan,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data Poliklinik berhasil ditambahkan.', 'data' => $item]);
        }

        return redirect('/master-poliklinik')->with('success', 'Data Poliklinik berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Poliklinik::findOrFail($id);
        return response()->json($item);
    }

    public function edit($id)
    {
        $item = Poliklinik::findOrFail($id);
        $units = ['Pelayanan', 'Penunjang'];

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }

        return view('poliklinik_create', compact('item', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_poli'  => 'required|unique:polikliniks,kode_poli,' . $id . '|max:20',
            'nama_poli'  => 'required|max:255',
            'unit'       => 'nullable|string',
            'wajib_ppa'  => 'nullable|string',
            'jenis_poli' => 'nullable|string',
        ]);

        $item = Poliklinik::findOrFail($id);
        $item->update([
            'kode_poli'       => strtoupper($request->kode_poli),
            'nama_poli'       => $request->nama_poli,
            'unit'            => $request->unit ?? 'Pelayanan',
            'wajib_ppa'       => $request->wajib_ppa ?? 'Ya',
            'jenis_poli'      => $request->jenis_poli ?? 'Poli Sakit',
            'is_igd'          => $request->has('is_igd'),
            'is_rehab_medik'  => $request->has('is_rehab_medik'),
            'is_mcu'          => $request->has('is_mcu'),
            'is_lab'          => $request->has('is_lab'),
            'is_radiologi'    => $request->has('is_radiologi'),
            'is_active'       => $request->has('is_active') ? true : $item->is_active,
            'keterangan'      => $request->keterangan,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data Poliklinik berhasil diperbarui.', 'data' => $item->fresh()]);
        }

        return redirect('/master-poliklinik')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $item = Poliklinik::findOrFail($id);
            $nama = $item->nama_poli;
            $item->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => "Data Poliklinik '$nama' berhasil dihapus."]);
            }

            return redirect('/master-poliklinik')->with('success', "Data Poliklinik '$nama' berhasil dihapus.");
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
            }

            return redirect('/master-poliklinik')->with('error', 'Gagal menghapus data Poliklinik: ' . $e->getMessage());
        }
    }
}
