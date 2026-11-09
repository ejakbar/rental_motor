<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Jika role tidak cocok, redirect ke dashboard masing-masing
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses dibatasi sesuai peran akun Anda.');
            } elseif ($user->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard')->with('error', 'Akses dibatasi sesuai peran akun Anda.');
            } else {
                return redirect()->route('katalog')->with('error', 'Akses dibatasi sesuai peran akun Anda.');
            }
        }

        return $next($request);
    }
}

