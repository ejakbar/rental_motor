<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Pengguna::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:penggunas,email',
            'no_telp' => 'required|string|max:20',
            'role' => 'required|in:penyewa,pemilik',
            'password' => 'required|min:6|confirmed',
            // Jika pemilik, bisa isi rekening sekalian
            'nama_bank' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:50',
            'atas_nama_rekening' => 'nullable|string|max:100',
        ]);

        $user = Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'nama_bank' => $validated['nama_bank'] ?? null,
            'no_rekening' => $validated['no_rekening'] ?? null,
            'atas_nama_rekening' => $validated['atas_nama_rekening'] ?? null,
            'status_kyc' => $validated['role'] === 'pemilik' ? 'verified' : 'belum_verifikasi',
        ]);

        Auth::login($user);
        return $this->redirectBasedOnRole($user)->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Demo Quick-Login Switcher untuk kemudahan pengujian
     */
    public function demoLogin($type)
    {
        $user = match($type) {
            'admin' => Pengguna::where('role', 'admin')->first(),
            'pemilik' => Pengguna::where('email', 'budi.pemilik@rental.com')->first() ?? Pengguna::where('role', 'pemilik')->first(),
            'penyewa' => Pengguna::where('email', 'andi.penyewa@rental.com')->first() ?? Pengguna::where('role', 'penyewa')->where('status_kyc', 'verified')->first(),
            'penyewa_baru' => Pengguna::where('email', 'siti.baru@rental.com')->first() ?? Pengguna::where('role', 'penyewa')->where('status_kyc', 'belum_verifikasi')->first(),
            default => null,
        };

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            return $this->redirectBasedOnRole($user)->with('success', "Beralih akun ke {$user->nama} ({$user->role})");
        }

        return redirect('/')->with('error', 'Akun demo tidak ditemukan.');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'pemilik') {
            return redirect('/pemilik/dashboard');
        } else {
            return redirect('/katalog');
        }
    }
}

