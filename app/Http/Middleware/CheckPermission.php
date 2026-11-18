<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Administrator has unrestricted access
        if ($user->isAdministrator()) {
            return $next($request);
        }

        // If specific permissions are required, verify user has at least one
        if (!empty($permissions) && !$user->hasPermission($permissions)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Anda tidak memiliki hak akses untuk modul ini.'
                ], 403);
            }

            return redirect('/dashboard')->with('error', 'Akses ditolak: Akun Anda (' . ($user->role ? $user->role->name : 'Staff') . ') tidak memiliki izin untuk mengakses modul tersebut.');
        }

        return $next($request);
    }
}

