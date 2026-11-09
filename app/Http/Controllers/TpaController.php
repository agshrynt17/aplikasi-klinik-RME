<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tpa;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class TpaController extends Controller
{
    private function seedDefaultsIfNeeded()
    {
        if (Tpa::count() < 3) {
            $defaults = [
                [
                    'kode_tpa'  => '011',
                    'nama_tpa'  => 'ADMEDIKA',
                    'alamat'    => 'Jakarta',
                    'telp'      => '021-22109090',
                    'fax'       => '021-9902226',
                    'is_active' => true,
                ],
                [
                    'kode_tpa'  => '012',
                    'nama_tpa'  => 'ISOMEDIK RELIANCE',
                    'alamat'    => null,
                    'telp'      => null,
                    'fax'       => null,
                    'is_active' => true,
                ],
                [
                    'kode_tpa'  => '02',
                    'nama_tpa'  => 'MANDIRI INHEALTH',
                    'alamat'    => 'karawang',
                    'telp'      => null,
                    'fax'       => null,
                    'is_active' => true,
                ],
            ];

            foreach ($defaults as $row) {
                Tpa::updateOrCreate(['kode_tpa' => $row['kode_tpa']], $row);
            }
        }
    }

    public function index(Request $request)
    {
        $this->seedDefaultsIfNeeded();
        $profil = ProfilFaskes::first();

        $query = Tpa::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('kode_tpa', 'like', "%{$q}%")
                  ->orWhere('nama_tpa', 'like', "%{$q}%")
                  ->orWhere('alamat', 'like', "%{$q}%")
                  ->orWhere('telp', 'like', "%{$q}%")
                  ->orWhere('fax', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        $data = $query->orderBy('id', 'asc')->get();

        return view('master_tpa', compact('data', 'profil'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        return view('master_tpa_create', compact('profil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_tpa' => 'required|max:50',
            'nama_tpa' => 'required|max:255',
        ]);

        $isActive = $request->has('is_active') ? (bool) $request->is_active : true;

        $item = Tpa::create([
            'kode_tpa'     => strtoupper($request->kode_tpa),
            'nama_tpa'     => $request->nama_tpa,
            'alamat'       => $request->alamat,
            'telp'         => $request->telp,
            'fax'          => $request->fax,
            'email'        => $request->email,
            'join_date'    => $request->join_date,
            'nomor_spk'    => $request->nomor_spk,
            'expired_date' => $request->expired_date,
            'nama_pic'     => $request->nama_pic,
            'telp_pic'     => $request->telp_pic,
            'email_pic'    => $request->email_pic,
            'keterangan'   => $request->keterangan,
            'is_active'    => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Data TPA berhasil disimpan.',
                'redirect' => '/master-tpa',
                'data'     => $item,
            ]);
        }

        return redirect('/master-tpa')->with('success', 'Data TPA berhasil disimpan.');
    }

    public function show($id)
    {
        $item = Tpa::findOrFail($id);
        return response()->json($item);
    }

    public function edit($id, Request $request)
    {
        $tpa = Tpa::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($tpa);
        }
        $profil = ProfilFaskes::first();
        $isEdit = true;
        return view('master_tpa_create', compact('tpa', 'isEdit', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_tpa' => 'required|max:50',
            'nama_tpa' => 'required|max:255',
        ]);

        $item = Tpa::findOrFail($id);
        $isActive = $request->has('is_active') ? (bool) $request->is_active : $item->is_active;

        $item->update([
            'kode_tpa'     => strtoupper($request->kode_tpa),
            'nama_tpa'     => $request->nama_tpa,
            'alamat'       => $request->alamat,
            'telp'         => $request->telp,
            'fax'          => $request->fax,
            'email'        => $request->email,
            'join_date'    => $request->join_date,
            'nomor_spk'    => $request->nomor_spk,
            'expired_date' => $request->expired_date,
            'nama_pic'     => $request->nama_pic,
            'telp_pic'     => $request->telp_pic,
            'email_pic'    => $request->email_pic,
            'keterangan'   => $request->keterangan,
            'is_active'    => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Data TPA berhasil diperbarui.',
                'redirect' => '/master-tpa',
                'data'     => $item->fresh(),
            ]);
        }

        return redirect('/master-tpa')->with('success', 'Data TPA berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Tpa::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data TPA berhasil dihapus.',
        ]);
    }
}
