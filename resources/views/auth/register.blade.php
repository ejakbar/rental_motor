@extends('layouts.main')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="py-12 sm:py-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex w-14 h-14 rounded-2xl gradient-brand text-white items-center justify-center shadow-lg shadow-brand-600/30 mb-4">
                    <i class="fas fa-user-plus text-xl text-tertiary-300"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun RentRide</h1>
                <p class="text-xs text-slate-500 mt-1">Pilih peran Anda dan mulai sewa atau sewakan motor</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Role Selector Buttons -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Peran Akun</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer hover:border-brand-500 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <input type="radio" name="role" value="penyewa" checked class="sr-only" onchange="toggleBankSection(false)">
                            <div class="flex items-center justify-between mb-1">
                                <i class="fas fa-key text-brand-600 text-lg"></i>
                                <span class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-brand-600 hidden"></span>
                                </span>
                            </div>
                            <span class="font-bold text-sm text-slate-900">Penyewa</span>
                            <span class="text-[11px] text-slate-500">Mencari & menyewa motor</span>
                        </label>

                        <label class="relative flex flex-col p-4 border rounded-2xl cursor-pointer hover:border-brand-500 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <input type="radio" name="role" value="pemilik" {{ old('role') === 'pemilik' ? 'checked' : '' }} class="sr-only" onchange="toggleBankSection(true)">
                            <div class="flex items-center justify-between mb-1">
                                <i class="fas fa-motorcycle text-brand-600 text-lg"></i>
                                <span class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full bg-brand-600 hidden"></span>
                                </span>
                            </div>
                            <span class="font-bold text-sm text-slate-900">Pemilik Motor</span>
                            <span class="text-[11px] text-slate-500">Sewakan aset & hasilkan dana</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition @error('nama') border-rose-500 @enderror">
                    @error('nama')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition @error('email') border-rose-500 @enderror">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp / Telepon</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required placeholder="081234567890"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition @error('no_telp') border-rose-500 @enderror">
                    @error('no_telp')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section Rekening Bank (Khusus Pemilik Sesuai PRD) -->
                <div id="bankSection" class="{{ old('role') === 'pemilik' ? '' : 'hidden' }} p-4 rounded-2xl bg-brand-50/60 border border-brand-200 space-y-3">
                    <div class="flex items-center gap-2 text-brand-950 font-bold text-xs uppercase tracking-wider">
                        <i class="fas fa-university text-brand-600"></i> Rekening Bank Pemilik (Untuk Pencairan Hasil Sewa)
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Bank</label>
                            <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" placeholder="Contoh: BCA / Mandiri / BRI"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nomor Rekening</label>
                            <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" placeholder="Contoh: 1234567890"
                                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand-500 font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening') }}" placeholder="Nama pemilik rekening bank"
                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition @error('password') border-rose-500 @enderror">
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ulangi Sandi</label>
                        <input type="password" name="password_confirmation" required placeholder="Ketik ulang sandi"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl gradient-brand text-white font-bold text-sm shadow-md shadow-brand-600/30 hover:opacity-95 transition mt-4">
                    Buat Akun Sekarang
                </button>
            </form>

            <div class="text-center mt-6 text-xs text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleBankSection(show) {
        const section = document.getElementById('bankSection');
        if (show) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }
</script>
@endsection
