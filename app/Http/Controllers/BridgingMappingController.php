<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Poliklinik;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class BridgingMappingController extends Controller
{
    // =====================================
    // 1. MAPPING POLI
    // =====================================
    public function mappingPoli()
    {
        $polis = Poliklinik::orderBy('nama_poli')->get();
        return view('bridging.mapping_poli', compact('polis'));
    }

    public function storeMappingPoli(Request $request)
    {
        $request->validate([
            'mappings' => 'required|array',
            'mappings.*.id' => 'required|exists:polikliniks,id',
            'mappings.*.kode_bpjs' => 'nullable|string',
            'mappings.*.kode_satusehat' => 'nullable|string',
        ]);

        foreach ($request->mappings as $map) {
            Poliklinik::where('id', $map['id'])->update([
                'kode_bpjs' => $map['kode_bpjs'],
                'kode_satusehat' => $map['kode_satusehat']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Mapping Poli berhasil disimpan.']);
    }

    // =====================================
    // 2. MAPPING DOKTER
    // =====================================
    public function mappingDokter()
    {
        // Ambil pegawai yang jabatannya mengandung kata Dokter
        $dokters = Pegawai::where('jabatan', 'LIKE', '%Dokter%')->orWhere('jabatan', 'Dokter Umum')->orWhere('jabatan', 'Dokter Spesialis')->orderBy('nama_pegawai')->get();
        
        // Jika kosong, tampilkan saja semua pegawai sebagai fallback untuk testing
        if ($dokters->isEmpty()) {
            $dokters = Pegawai::orderBy('nama_pegawai')->get();
        }
        return view('bridging.mapping_dokter', compact('dokters'));
    }

    public function storeMappingDokter(Request $request)
    {
        $request->validate([
            'mappings' => 'required|array',
            'mappings.*.id' => 'required|exists:pegawais,id',
            'mappings.*.kode_dpjp_bpjs' => 'nullable|string',
            'mappings.*.kode_ihs_satusehat' => 'nullable|string',
        ]);

        foreach ($request->mappings as $map) {
            Pegawai::where('id', $map['id'])->update([
                'kode_dpjp_bpjs' => $map['kode_dpjp_bpjs'],
                'kode_ihs_satusehat' => $map['kode_ihs_satusehat']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Mapping Dokter berhasil disimpan.']);
    }
}
