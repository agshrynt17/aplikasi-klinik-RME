<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkunPembayaran;
use Illuminate\Http\Request;

class AkunPembayaranController extends Controller
{
    /**
     * Ensure initial seed data matching PROFASKES exists
     */
    private function ensureInitialData()
    {
        if (AkunPembayaran::count() === 0) {
            $seeds = [
                [
                    'kode_akun'   => 'BCA-01',
                    'nama_akun'   => 'BCA',
                    'atas_nama'   => 'BENDAHARA KLINIK TELAGASARI',
                    'no_rekening' => '3781826535',
                    'jenis_akun'  => 'Bank',
                    'is_active'   => true,
                ],
                [
                    'kode_akun'   => 'MAYBANK-01',
                    'nama_akun'   => 'MAYBANK',
                    'atas_nama'   => 'PT. TELAGASARI MEDIKA PERKASA',
                    'no_rekening' => '2784000357',
                    'jenis_akun'  => 'Bank',
                    'is_active'   => true,
                ],
                [
                    'kode_akun'   => 'MANDIRI-01',
                    'nama_akun'   => 'MANDIRI',
                    'atas_nama'   => 'ULFI NURHIDAYATI',
                    'no_rekening' => '1730029793933',
                    'jenis_akun'  => 'Bank',
                    'is_active'   => true,
                ],
            ];

            foreach ($seeds as $s) {
                AkunPembayaran::create($s);
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureInitialData();

        $query = AkunPembayaran::query();

        // Search: Nama Channel / Bank
        if ($request->filled('nama_akun')) {
            $query->where('nama_akun', 'like', '%' . $request->nama_akun . '%');
        }

        // Search: Nama Pemilik
        if ($request->filled('atas_nama')) {
            $query->where('atas_nama', 'like', '%' . $request->atas_nama . '%');
        }

        // Search: No Akun
        if ($request->filled('no_rekening')) {
            $query->where('no_rekening', 'like', '%' . $request->no_rekening . '%');
        }

        // Search: Status
        if ($request->filled('is_active')) {
            if ($request->is_active === '1' || $request->is_active === 'Aktif') {
                $query->where('is_active', true);
            } elseif ($request->is_active === '0' || $request->is_active === 'Tidak Aktif') {
                $query->where('is_active', false);
            }
        }

        $data = $query->orderBy('id', 'asc')->get();

        return view('payment_account.index', compact('data'));
    }

    public function create()
    {
        return view('payment_account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_akun'   => 'required|max:255',
            'atas_nama'   => 'nullable|max:255',
            'no_rekening' => 'nullable|max:50',
            'is_active'   => 'nullable',
        ]);

        $kodeAkun = $request->kode_akun;
        if (empty($kodeAkun)) {
            $cleanName = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $request->nama_akun), 0, 8));
            $kodeAkun = $cleanName . '-' . rand(100, 999);
        }

        $isActive = true;
        if ($request->has('is_active')) {
            $isActive = in_array($request->is_active, ['1', 'Aktif', true], true);
        }

        $item = AkunPembayaran::create([
            'kode_akun'   => $kodeAkun,
            'nama_akun'   => $request->nama_akun,
            'jenis_akun'  => $request->jenis_akun ?? 'Bank',
            'no_rekening' => $request->no_rekening,
            'atas_nama'   => $request->atas_nama,
            'keterangan'  => $request->keterangan,
            'is_active'   => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun Pembayaran berhasil ditambahkan.',
                'data'    => $item,
            ]);
        }

        return redirect('/payment-account/index')->with('success', 'Akun Pembayaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = AkunPembayaran::findOrFail($id);
        return response()->json($item);
    }

    public function edit($id)
    {
        $item = AkunPembayaran::findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($item);
        }

        return view('payment_account.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_akun'   => 'required|max:255',
            'atas_nama'   => 'nullable|max:255',
            'no_rekening' => 'nullable|max:50',
            'is_active'   => 'nullable',
        ]);

        $item = AkunPembayaran::findOrFail($id);

        $isActive = true;
        if ($request->has('is_active')) {
            $isActive = in_array($request->is_active, ['1', 'Aktif', true], true);
        }

        $item->update([
            'nama_akun'   => $request->nama_akun,
            'jenis_akun'  => $request->jenis_akun ?? $item->jenis_akun,
            'no_rekening' => $request->no_rekening,
            'atas_nama'   => $request->atas_nama,
            'keterangan'  => $request->keterangan ?? $item->keterangan,
            'is_active'   => $isActive,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun Pembayaran berhasil diperbarui.',
                'data'    => $item->fresh(),
            ]);
        }

        return redirect('/payment-account/index')->with('success', 'Akun Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = AkunPembayaran::findOrFail($id);
        $item->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun Pembayaran berhasil dihapus.',
            ]);
        }

        return redirect('/payment-account/index')->with('success', 'Akun Pembayaran berhasil dihapus.');
    }
}
