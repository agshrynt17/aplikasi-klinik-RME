<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IcareController extends Controller
{
    public function index()
    {
        return redirect()->route('icare.akun');
    }

    public function akun()
    {
        return view('icare.akun', ['title' => 'Akun Bridging i-Care']);
    }

    public function cekRiwayatKunjungan()
    {
        return view('icare.cek_riwayat_kunjungan', ['title' => 'Cek Riwayat Kunjungan i-Care']);
    }

    public function updatePassword()
    {
        return view('icare.update_password', ['title' => 'Update Password i-Care']);
    }

    public function storeUpdatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        // TODO: implementasi update password via API i-Care
        return back()->with('success', 'Password i-Care berhasil diperbarui.');
    }
}
