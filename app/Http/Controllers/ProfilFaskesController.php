<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfilFaskes;

class ProfilFaskesController extends Controller
{
    public function index()
    {
        $profil = ProfilFaskes::first();

        // Jika belum ada data di database, buat data default Klinik Telagasari 24 Jam
        if (!$profil) {
            $profil = ProfilFaskes::create([
                'nama_faskes' => 'KLINIK TELAGASARI 24 JAM',
                'kode_faskes' => '3215001',
                'jenis_faskes' => 'Faskes Rawat Jalan',
                'tingkat_pelayanan' => 'Tingkat Pertama (FKTP)',
                'no_izin_operasional' => null,
                'alamat' => 'Jln. Raya Telagasari No. 24, Kec. Telagasari, Kabupaten Karawang, Jawa Barat',
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Karawang',
                'kecamatan' => 'Telagasari',
                'kode_pos' => '41371',
                'telepon' => '(0264) 8303763',
                'email' => 'kliniktelagasari24jam@gmail.com',
                'no_izin_apotek' => '123/019/X/KLINIK-OSS/XXX',
                'penanggung_jawab_apotek' => 'apt. Fariddon Ricky Wahyudi, S.Farm',
                'kerjasama_bpjs' => 'Ya'
            ]);
        }

        return view('profil_faskes', compact('profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_faskes' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $profil = ProfilFaskes::first();
        if (!$profil) {
            $profil = new ProfilFaskes();
        }

        $data = $request->except(['_token', 'logo']);

        // Handle upload logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);
            $data['logo'] = 'uploads/logo/' . $filename;
        }

        $profil->fill($data);
        $profil->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil faskes berhasil diperbarui!',
                'data' => $profil
            ]);
        }

        return redirect()->back()->with('success', 'Profil Faskes berhasil diperbarui!');
    }
}
