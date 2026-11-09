@extends('layouts.main')

@section('title', 'Katalog Motor Tersedia')

@section('content')
<div class="bg-slate-100/70 border-b border-slate-200/80 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Sepeda Motor</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Cari motor berdasarkan jadwal pengambilan & pengembalian Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 shadow-sm flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-brand-600"></i> Semua motor terkurasi Admin
                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Filter (Pencarian Kalender Sesuai PRD) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm sticky top-24">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fas fa-sliders text-brand-600"></i> Filter Pencarian
                    </h3>
                    <a href="{{ route('katalog') }}" class="text-[11px] font-semibold text-brand-600 hover:underline">Reset</a>
                </div>

                <form method="GET" action="{{ route('katalog') }}" class="space-y-4">
                    <!-- Tanggal Pengambilan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar-plus text-brand-600 mr-1"></i> Tanggal Pengambilan
                        </label>
                        <input type="datetime-local" name="tanggal_mulai" 
                            value="{{ request('tanggal_mulai', $startDate ? $startDate->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Tanggal Pengembalian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar-check text-brand-600 mr-1"></i> Tanggal Pengembalian
                        </label>
                        <input type="datetime-local" name="tanggal_selesai" 
                            value="{{ request('tanggal_selesai', $endDate ? $endDate->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Pencarian Kata Kunci -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Kunci</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NMAX, Vario, Beat..."
                                class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <!-- Filter Merek -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Merek</label>
                        <select name="merk" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-brand-500">
                            <option value="">Semua Merek</option>
                            @foreach($allMerks as $merk)
                                <option value="{{ $merk }}" {{ request('merk') == $merk ? 'selected' : '' }}>{{ $merk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter CC Motor -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kapasitas Mesin (CC)</label>
                        <select name="tipe_cc" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-brand-500">
                            <option value="">Semua CC</option>
                            @foreach($allCcs as $cc)
                                <option value="{{ $cc }}" {{ request('tipe_cc') == $cc ? 'selected' : '' }}>{{ $cc }} CC</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Maksimum Harga -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Maks. Tarif Harian (Rp)</label>
                        <input type="number" name="max_harga" value="{{ request('max_harga') }}" placeholder="Contoh: 150000"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Urutkan (Sorting) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutkan</label>
                        <select name="sort" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-brand-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                            <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                            <option value="tahun_desc" {{ request('sort') == 'tahun_desc' ? 'selected' : '' }}>Tahun Keluaran Terbaru</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl gradient-brand text-white font-bold text-xs shadow-md shadow-brand-600/30 hover:opacity-95 transition">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Motor Catalog Cards -->
        <div class="lg:col-span-3">
            <!-- Active Search Filter Pill Banner -->
            @if(request('tanggal_mulai') && request('tanggal_selesai'))
                <div class="mb-6 p-4 rounded-2xl bg-brand-50 border border-brand-200 flex items-center justify-between text-xs text-brand-950">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-check text-brand-600 text-base"></i>
                        <span>
                            Menampilkan motor <strong>tersedia</strong> dari 
                            <strong>{{ date('d M Y H:i', strtotime(request('tanggal_mulai'))) }}</strong> s/d 
                            <strong>{{ date('d M Y H:i', strtotime(request('tanggal_selesai'))) }}</strong>
                        </span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-darkness-900 text-tertiary-400 font-bold text-[10px] border border-darkness-800">
                        <i class="fas fa-shield text-[9px] mr-1"></i> Anti Double-Booking
                    </span>
                </div>
            @endif

            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-slate-500">
                    Menampilkan <strong>{{ $motors->total() }}</strong> motor pilihan
                </span>
            </div>

            <!-- Motors Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($motors as $motor)
                    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                        <div>
                            <!-- Motor Photo -->
                            <div class="relative h-48 bg-slate-100 overflow-hidden">
                                <img src="{{ $motor->foto_utama_url }}" alt="{{ $motor->merk }}" class="w-full h-full object-cover">
                                
                                <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-darkness-950/85 text-tertiary-300 backdrop-blur-sm">
                                        {{ $motor->tipe_cc }} CC
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/90 text-slate-800 shadow-sm">
                                        Tahun {{ $motor->tahun }}
                                    </span>
                                </div>

                                <div class="absolute bottom-3 right-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold bg-darkness-950/85 text-white">
                                        {{ $motor->no_plat }}
                                    </span>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="p-5">
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                                    <span>{{ $motor->merk }}</span>
                                    <span class="text-slate-600 font-medium">
                                        <i class="fas fa-store text-brand-600 mr-1"></i>{{ Str::limit($motor->pemilik->nama ?? 'Mitra', 12) }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 leading-snug mb-2">
                                    {{ $motor->merk }} {{ $motor->tipe_motor }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mb-3">
                                    {{ $motor->deskripsi ?? 'Kondisi prima, servis berkala dan surat STNK lengkap.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Price & Action (Primary Hijau) -->
                        <div class="p-5 pt-0 border-t border-slate-100 flex items-center justify-between mt-2">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Tarif Harian</span>
                                <span class="text-base font-extrabold text-brand-600">
                                    Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}
                                    <span class="text-[11px] font-normal text-slate-400">/hari</span>
                                </span>
                            </div>

                            @php
                                $detailUrl = route('motor.detail', [
                                    'id' => $motor->id,
                                    'tanggal_mulai' => request('tanggal_mulai'),
                                    'tanggal_selesai' => request('tanggal_selesai')
                                ]);
                            @endphp
                            <a href="{{ $detailUrl }}" class="px-3.5 py-2 rounded-xl gradient-brand text-white text-xs font-bold shadow-md shadow-brand-600/25 hover:opacity-95 transition">
                                Sewa Unit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-3xl p-12 text-center border border-slate-200">
                        <div class="w-16 h-16 rounded-2xl bg-tertiary-50 text-tertiary-500 text-2xl flex items-center justify-center mx-auto mb-4 border border-tertiary-200">
                            <i class="fas fa-calendar-xmark"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Motor yang Cocok</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                            Tidak ditemukan motor yang tersedia pada rentang tanggal atau filter yang dipilih. Coba ganti rentang tanggal pengambilan & pengembalian Anda.
                        </p>
                        <a href="{{ route('katalog') }}" class="inline-flex px-4 py-2 rounded-xl gradient-brand text-white text-xs font-bold">
                            Reset Semua Filter
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $motors->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
