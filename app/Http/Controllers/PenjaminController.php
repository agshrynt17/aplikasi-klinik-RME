<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penjamin;
use App\Models\ProfilFaskes;
use Illuminate\Http\Request;

class PenjaminController extends Controller
{
    private function seedDefaultsIfNeeded()
    {
        if (Penjamin::count() < 9) {
            $defaults = [
                ['id' => 1, 'kode_penjamin' => 'P0000', 'nama_penjamin' => 'Pribadi', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => 'xx', 'is_default' => 0, 'is_active' => 1],
                ['id' => 2, 'kode_penjamin' => 'P0001', 'nama_penjamin' => 'BPJS Kesehatan', 'jenis_penjamin' => 'Asuransi', 'telp' => 'xxx', 'is_default' => 1, 'is_active' => 1],
                ['id' => 3, 'kode_penjamin' => 'P0002', 'nama_penjamin' => 'MANDIRI INHEALTH', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => '00000', 'nama_penjamin' => 'Penjualan Apotek', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => 'xx', 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => 'P0003', 'nama_penjamin' => 'ISOMEDIK RELIENCE', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => 'P0004', 'nama_penjamin' => 'BANSOS LINGKUNGAN KLINIK', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => 'P0005', 'nama_penjamin' => 'SURAT SEHAT', 'jenis_penjamin' => 'Pribadi/Umum', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => 'P0006', 'nama_penjamin' => 'ALAM MAKMUR', 'jenis_penjamin' => 'Perusahaan', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
                ['kode_penjamin' => 'P0007', 'nama_penjamin' => 'ADMEDIKA', 'jenis_penjamin' => 'Asuransi', 'telp' => null, 'is_default' => 0, 'is_active' => 1],
            ];

            foreach ($defaults as $row) {
                if (isset($row['id'])) {
                    $id = $row['id'];
                    unset($row['id']);
                    Penjamin::where('id', $id)->update($row);
                } else {
                    Penjamin::updateOrCreate(['kode_penjamin' => $row['kode_penjamin']], $row);
                }
            }
        }
    }

    public function getNextKode()
    {
        $penjamins = Penjamin::where('kode_penjamin', 'LIKE', 'P%')->pluck('kode_penjamin');
        $maxNum = 0;
        foreach ($penjamins as $code) {
            if (preg_match('/^P(\d+)$/i', $code, $matches)) {
                $num = intval($matches[1]);
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $nextNum = $maxNum + 1;
        return 'P' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $this->seedDefaultsIfNeeded();
        $profil = ProfilFaskes::first();

        $query = Penjamin::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('kode_penjamin', 'like', "%{$q}%")
                  ->orWhere('nama_penjamin', 'like', "%{$q}%")
                  ->orWhere('jenis_penjamin', 'like', "%{$q}%")
                  ->orWhere('telp', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenis_penjamin')) {
            $query->where('jenis_penjamin', $request->jenis_penjamin);
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        $data = $query->orderBy('kode_penjamin', 'asc')->get();

        return view('master_penjamin', compact('data', 'profil'));
    }

    public function create()
    {
        $profil = ProfilFaskes::first();
        $nextCode = $this->getNextKode();
        return view('master_penjamin_create', compact('nextCode', 'profil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penjamin'  => 'required|max:255',
            'jenis_penjamin' => 'required|max:100',
        ]);

        $kode = $request->filled('kode_penjamin') ? strtoupper($request->kode_penjamin) : $this->getNextKode();

        // Check if kode is duplicate
        if (Penjamin::where('kode_penjamin', $kode)->exists()) {
            $kode = $this->getNextKode();
        }

        $isActive = $request->has('is_active') ? (bool) $request->is_active : true;

        $item = Penjamin::create([
            'kode_penjamin'  => $kode,
            'nama_penjamin'  => $request->nama_penjamin,
            'jenis_penjamin' => $request->jenis_penjamin,
            'telp'           => $request->telp,
            'is_default'     => false,
            'join_date'      => $request->join_date,
            'alamat'         => $request->alamat,
            'fax'            => $request->fax,
            'email'          => $request->email,
            'nomor_spk'      => $request->nomor_spk,
            'expired_date'   => $request->expired_date,
            'nama_pic'       => $request->nama_pic,
            'telp_pic'       => $request->telp_pic,
            'email_pic'      => $request->email_pic,
            'keterangan'     => $request->keterangan,
            'is_active'      => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Penjamin berhasil disimpan.',
                'redirect' => '/master-penjamin',
                'data' => $item,
            ]);
        }

        return redirect('/master-penjamin')->with('success', 'Data Penjamin berhasil disimpan.');
    }

    public function show($id)
    {
        $item = Penjamin::findOrFail($id);
        return response()->json($item);
    }

    public function edit($id, Request $request)
    {
        $penjamin = Penjamin::findOrFail($id);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($penjamin);
        }
        $profil = ProfilFaskes::first();
        $isEdit = true;
        return view('master_penjamin_create', compact('penjamin', 'isEdit', 'profil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penjamin'  => 'required|max:255',
            'jenis_penjamin' => 'required|max:100',
        ]);

        $item = Penjamin::findOrFail($id);
        $isActive = $request->has('is_active') ? (bool) $request->is_active : $item->is_active;

        $item->update([
            'kode_penjamin'  => $request->kode_penjamin ?? $item->kode_penjamin,
            'nama_penjamin'  => $request->nama_penjamin,
            'jenis_penjamin' => $request->jenis_penjamin,
            'telp'           => $request->telp,
            'join_date'      => $request->join_date,
            'alamat'         => $request->alamat,
            'fax'            => $request->fax,
            'email'          => $request->email,
            'nomor_spk'      => $request->nomor_spk,
            'expired_date'   => $request->expired_date,
            'nama_pic'       => $request->nama_pic,
            'telp_pic'       => $request->telp_pic,
            'email_pic'      => $request->email_pic,
            'keterangan'     => $request->keterangan,
            'is_active'      => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data Penjamin berhasil diperbarui.',
                'redirect' => '/master-penjamin',
                'data' => $item->fresh(),
            ]);
        }

        return redirect('/master-penjamin')->with('success', 'Data Penjamin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Penjamin::findOrFail($id);
        $item->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Penjamin berhasil dihapus.',
        ]);
    }

    public function setDefault(Request $request)
    {
        $id = $request->input('id');
        Penjamin::query()->update(['is_default' => false]);
        $item = Penjamin::findOrFail($id);
        $item->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Penjamin ' . $item->nama_penjamin . ' berhasil dijadikan default.',
            'data'    => $item,
        ]);
    }
}
