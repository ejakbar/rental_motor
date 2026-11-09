@extends('layouts.main')

@section('title', 'Masuk Akun')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-md mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex w-14 h-14 rounded-2xl gradient-brand text-white items-center justify-center shadow-lg shadow-brand-600/30 mb-4">
                    <i class="fas fa-lock text-xl text-tertiary-300"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-slate-500 mt-1">Masuk ke akun RentRide Anda untuk melanjutkan</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition @error('email') border-rose-500 @enderror">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <i class="fas fa-key absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl gradient-brand text-white font-bold text-sm shadow-md shadow-brand-600/30 hover:opacity-95 transition mt-2">
                    Masuk ke Akun
                </button>
            </form>

            <div class="text-center mt-6 text-xs text-slate-500">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-700 underline">Daftar sekarang</a>
            </div>

            <!-- Demo Quick-Login Pills -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-center text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">
                    <i class="fas fa-magic text-tertiary-500 mr-1"></i> Quick Demo Login (1-Klik)
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('demo.login', 'admin') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-center transition flex flex-col items-center">
                        <span class="text-tertiary-600 font-bold mb-0.5"><i class="fas fa-crown"></i> Admin</span>
                        <span class="text-[10px] text-slate-400">admin@rental.com</span>
                    </a>
                    <a href="{{ route('demo.login', 'pemilik') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-center transition flex flex-col items-center">
                        <span class="text-brand-600 font-bold mb-0.5"><i class="fas fa-motorcycle"></i> Pemilik</span>
                        <span class="text-[10px] text-slate-400">budi.pemilik@...</span>
                    </a>
                    <a href="{{ route('demo.login', 'penyewa') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-center transition flex flex-col items-center">
                        <span class="text-darkness-900 font-bold mb-0.5"><i class="fas fa-id-card-clip text-brand-600"></i> Penyewa KYC</span>
                        <span class="text-[10px] text-slate-400">andi.penyewa@...</span>
                    </a>
                    <a href="{{ route('demo.login', 'penyewa_baru') }}" class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-center transition flex flex-col items-center">
                        <span class="text-slate-600 font-bold mb-0.5"><i class="fas fa-user-clock text-tertiary-500"></i> Penyewa Baru</span>
                        <span class="text-[10px] text-slate-400">siti.baru@...</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
