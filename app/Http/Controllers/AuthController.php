<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login pengguna
     */
    public function login(Request $request)
    {
        // Support standard input and LoginForm format
        $loginInput = $request->input('login')
            ?? $request->input('email')
            ?? $request->input('username')
            ?? data_get($request->all(), 'LoginForm.email');

        $passwordInput = $request->input('password')
            ?? data_get($request->all(), 'LoginForm.password');

        if (empty($loginInput) || empty($passwordInput)) {
            return back()->withInput()->withErrors([
                'login' => 'Email/Username dan Password wajib diisi.'
            ]);
        }

        // Cari user berdasarkan email atau username
        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'login' => 'Akun dengan email atau username tersebut tidak ditemukan.'
            ]);
        }

        if (!$user->is_active) {
            return back()->withInput()->withErrors([
                'login' => 'Akun ini sedang dinonaktifkan. Silakan hubungi Administrator.'
            ]);
        }

        if (!Hash::check($passwordInput, $user->password)) {
            return back()->withInput()->withErrors([
                'login' => 'Password yang Anda masukkan salah.'
            ]);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $user->update(['last_login_at' => now()]);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

