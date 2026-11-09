<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        // Seed default employees if table is empty or has few records
        if (Pegawai::count() < 10) {
            $defaultPegawais = [
                [
                    'kode_pegawai'     => 'PGW-000',
                    'nik'              => '0',
                    'nama_pegawai'     => 'Admin Klinik',
                    'tanggal_lahir'    => '2000-01-01',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => '-',
                    'jabatan'          => '-',
                    'unit'             => '-',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-001',
                    'nik'              => '1307074511930001',
                    'nama_pegawai'     => 'RESI ANGRAINI, A.md Keb',
                    'tanggal_lahir'    => '1993-11-05',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'TENAGA KESEHATAN',
                    'jabatan'          => 'Koordinator',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-002',
                    'nik'              => '1871021101890003',
                    'nama_pegawai'     => 'Apt.Fariddon Ricky Wahyudi, S.Farm',
                    'tanggal_lahir'    => '1989-01-11',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => 'TENAGA KESEHATAN',
                    'jabatan'          => 'Penanggung Jawab Pelayanan',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => true,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-003',
                    'nik'              => '1971041103870003',
                    'nama_pegawai'     => 'dr. FARISI',
                    'tanggal_lahir'    => '1987-03-11',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-004',
                    'nik'              => '3207365903960004',
                    'nama_pegawai'     => 'dr. Shela Asyiah Granitya',
                    'tanggal_lahir'    => '1996-03-19',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-005',
                    'nik'              => '3210000000000000',
                    'nama_pegawai'     => 'ARIEL',
                    'tanggal_lahir'    => '2000-01-01',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => '-',
                    'jabatan'          => 'Koordinator',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-006',
                    'nik'              => '3210533060392000',
                    'nama_pegawai'     => 'HENDI RUKMANA',
                    'tanggal_lahir'    => '1992-12-28',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => '-',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-007',
                    'nik'              => '3213054209880004',
                    'nama_pegawai'     => 'SITI MAEMUNAH, A.md Keb',
                    'tanggal_lahir'    => '1988-09-02',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'TENAGA KESEHATAN',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-008',
                    'nik'              => '3213240112940001',
                    'nama_pegawai'     => 'dr. Harry Putra Pratama',
                    'tanggal_lahir'    => '1986-01-08',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Penanggung Jawab Pelayanan',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-009',
                    'nik'              => '3215130508880001',
                    'nama_pegawai'     => 'dr. Fahmi Rizal',
                    'tanggal_lahir'    => '1988-08-05',
                    'jenis_kelamin'    => 'L',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Penanggung Jawab Pelayanan',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-010',
                    'nik'              => '3215135105940004',
                    'nama_pegawai'     => 'Dr Rani Puspita',
                    'tanggal_lahir'    => '1994-05-11',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Umum',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-011',
                    'nik'              => '3215135409960001',
                    'nama_pegawai'     => 'NOVIA GANTINI, A.md Keb',
                    'tanggal_lahir'    => '1996-09-14',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'TENAGA KESEHATAN',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-012',
                    'nik'              => '3215135409970001',
                    'nama_pegawai'     => 'PUTRI IRIANTI',
                    'tanggal_lahir'    => '1997-09-14',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'TENAGA KESEHATAN',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
                [
                    'kode_pegawai'     => 'PGW-013',
                    'nik'              => '3215135711850006',
                    'nama_pegawai'     => 'drg. Tia Novianti',
                    'tanggal_lahir'    => '1985-11-17',
                    'jenis_kelamin'    => 'P',
                    'fungsional_medis' => 'DOKTER',
                    'jabatan'          => 'Pelaksana',
                    'unit'             => 'Pelayanan',
                    'is_apoteker'      => false,
                    'is_active'        => true,
                ],
            ];

            foreach ($defaultPegawais as $dp) {
                Pegawai::updateOrCreate(['nik' => $dp['nik']], $dp);
            }
        }

        $query = Pegawai::query();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_pegawai', 'like', '%' . $keyword . '%')
                  ->orWhere('nik', 'like', '%' . $keyword . '%')
                  ->orWhere('jabatan', 'like', '%' . $keyword . '%')
                  ->orWhere('unit', 'like', '%' . $keyword . '%')
                  ->orWhere('fungsional_medis', 'like', '%' . $keyword . '%');
            });
        }

        $data   = $query->orderBy('id', 'asc')->get();
        $profil = ProfilFaskes::first();

        return view('sdm.master_pegawai', compact('data', 'profil'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        return view('sdm.master_pegawai_create', compact('profil'));
    }

    public function show($id)
    {
        $item = Pegawai::findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'          => 'required|unique:pegawais,nik|max:30',
            'nama_pegawai' => 'required|max:255',
        ]);

        $data = $request->all();
        if (empty($data['kode_pegawai'])) {
            $data['kode_pegawai'] = 'PGW-' . str_pad(Pegawai::max('id') + 1, 3, '0', STR_PAD_LEFT);
        }
        $data['is_active'] = $request->has('is_active') ? true : ($request->status_pegawai == 'Aktif' || !isset($request->status_pegawai));
        $data['is_apoteker'] = $request->is_apoteker == '1' || $request->is_apoteker == 'Ya';

        $item = Pegawai::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai berhasil ditambahkan.',
                'data'    => $item,
            ]);
        }

        return redirect('/master-pegawai')->with('success', 'Data Pegawai berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $item = Pegawai::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($item);
        }
        $profil = ProfilFaskes::first();
        return view('sdm.master_pegawai_create', compact('item', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik'          => 'required|unique:pegawais,nik,' . $id . '|max:30',
            'nama_pegawai' => 'required|max:255',
        ]);

        $item = Pegawai::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : ($request->status_pegawai == 'Aktif' || !isset($request->status_pegawai));
        $data['is_apoteker'] = $request->is_apoteker == '1' || $request->is_apoteker == 'Ya';

        $item->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai berhasil diperbarui.',
                'data'    => $item->fresh(),
            ]);
        }

        return redirect('/master-pegawai')->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pegawai::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Data Pegawai berhasil dihapus.']);
    }
}
