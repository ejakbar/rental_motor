@extends('layouts.main')

@section('title', 'Sewa Motor Aman & Mudah Berbasis Kalender')

@section('content')
<!-- Hero Section (Secondary Hitam Pekat dengan Aksen Hijau & Kuning) -->
<section class="relative overflow-hidden bg-gradient-to-b from-darkness-950 via-black to-darkness-900 text-white pt-16 pb-28">
    <!-- Background glow decoration (Hijau Primary & Kuning Tersier) -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-brand-500/20 blur-[130px] rounded-full pointer-events-none"></div>
    <div class="absolute top-1/3 right-1/4 w-[400px] h-[250px] bg-tertiary-500/10 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-brand-500/15 text-brand-400 border border-brand-500/30 mb-6 shadow-inner">
                <i class="fas fa-shield-alt text-tertiary-400"></i> Platform Marketplace Terkurasi Admin
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight sm:leading-tight">
                Sewa Sepeda Motor <br>
                <span class="bg-gradient-to-r from-brand-400 via-emerald-300 to-tertiary-300 bg-clip-text text-transparent">
                    Bebas Double-Booking
                </span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-300 font-normal leading-relaxed">
                Temukan motor pilihan terverifikasi sesuai rentang tanggal perjalanan Anda. Aman dengan verifikasi KYC terpusat dan kurasi kendaraan langsung oleh Admin.
            </p>
        </div>

        <!-- Search Bar Card (Pencarian Berbasis Kalender Sesuai PRD) -->
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl shadow-black/60 p-5 sm:p-7 border border-slate-100 text-slate-800">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2 text-sm font-bold text-slate-900">
                    <i class="fas fa-calendar-check text-brand-600"></i>
                    <span>Cari Ketersediaan Motor Sesuai Jadwal</span>
                </div>
                <span class="text-xs text-slate-400 hidden sm:inline flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                    Pencarian real-time < 3 detik
                </span>
            </div>

            <form action="{{ route('katalog') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tanggal & Jam Pengambilan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-calendar-plus text-brand-600 mr-1"></i> Tanggal Pengambilan
                    </label>
                    <input type="datetime-local" name="tanggal_mulai" 
                        value="{{ request('tanggal_mulai', date('Y-m-d\T09:00', strtotime('+1 day'))) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <!-- Tanggal & Jam Pengembalian -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-calendar-check text-brand-600 mr-1"></i> Tanggal Pengembalian
                    </label>
                    <input type="datetime-local" name="tanggal_selesai" 
                        value="{{ request('tanggal_selesai', date('Y-m-d\T09:00', strtotime('+3 days'))) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>

                <!-- Filter Merk -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fas fa-tag text-tertiary-500 mr-1"></i> Merek Motor
                    </label>
                    <select name="merk" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <option value="">Semua Merek</option>
                        <option value="Honda">Honda</option>
                        <option value="Yamaha">Yamaha</option>
                        <option value="Vespa">Vespa</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Kawasaki">Kawasaki</option>
                    </select>
                </div>

                <!-- Submit Button (Primary Hijau) -->
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl gradient-brand text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/30 hover:opacity-95 transition flex items-center justify-center gap-2">
                        <i class="fas fa-search text-tertiary-300"></i>
                        Cari Motor Tersedia
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Stats / KPIs Section Sesuai PRD -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-motorcycle"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['total_motor'] }}+</div>
                <div class="text-xs text-slate-500 font-medium">Motor Terkurasi</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-darkness-900 text-tertiary-400 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-handshake"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['total_pemilik'] }}+</div>
                <div class="text-xs text-slate-500 font-medium">Mitra Pemilik Aktif</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['total_penyewa'] }}+</div>
                <div class="text-xs text-slate-500 font-medium">Penyewa Terdaftar</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-tertiary-50 text-tertiary-600 flex items-center justify-center text-xl shrink-0 border border-tertiary-200">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-slate-900">100%</div>
                <div class="text-xs text-slate-500 font-medium">KYC Terverifikasi</div>
            </div>
        </div>
    </div>
</section>

<!-- Alur Kerja Utama Section (Core User Flows Sesuai PRD) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center max-w-2xl mx-auto mb-14">
        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest flex items-center justify-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-tertiary-500"></span> Cara Kerja Marketplace
        </span>
        <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Alur Transaksi yang Aman & Transparan</h2>
        <p class="text-slate-500 text-sm mt-2">Sistem mempertemukan Pemilik dan Penyewa dengan jaminan keamanan kurasi Admin platform.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Step 1 (Hijau Primary Accent) -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative group hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 font-black text-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-brand-200">
                01
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">1. Pendaftaran & Kurasi Kendaraan</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pemilik mendaftarkan motor dengan foto depan, samping, belakang, serta STNK. Tim Admin memverifikasi kelayakan motor maksimal 1x24 jam sebelum tayang di katalog.
            </p>
        </div>

        <!-- Step 2 (Hitam Secondary & Kuning Tersier Accent) -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative group hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl bg-darkness-950 text-tertiary-400 font-black text-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-darkness-800">
                02
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">2. Cari Berbasis Kalender & KYC</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Penyewa memilih tanggal pengambilan dan pengembalian tanpa takut tabrakan jadwal (*double-booking*). Penyewa mengunggah KTP dan SIM C sebelum pembayaran.
            </p>
        </div>

        <!-- Step 3 (Hijau Primary Accent) -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative group hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 font-black text-xl flex items-center justify-center mb-6 group-hover:scale-110 transition border border-brand-200">
                03
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">3. Serah Terima & Bagi Hasil Otomatis</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pengambilan dan pengembalian dicatat ketat. Setelah pemilik konfirmasi motor kembali, dana sewa otomatis diteruskan ke saldo dompet pemilik (dipotong komisi platform).
            </p>
        </div>
    </div>
</section>

<!-- Featured Motors Section -->
<section class="bg-slate-100/70 py-20 border-y border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Katalog Unggulan</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Motor Siap Sewa Hari Ini</h2>
                <p class="text-slate-500 text-sm mt-1">Unit terverifikasi admin, siap pakai dengan dokumen STNK lengkap.</p>
            </div>
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700 transition">
                Lihat Semua Motor <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredMotors as $motor)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/70 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Image Container with Badges -->
                        <div class="relative h-52 bg-slate-100 overflow-hidden">
                            <img src="{{ $motor->foto_utama_url }}" alt="{{ $motor->merk }} {{ $motor->tipe_motor }}" class="w-full h-full object-cover">
                            
                            <!-- Badges (Hijau & Hitam + Kuning) -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-600 text-white shadow-sm flex items-center gap-1">
                                    <i class="fas fa-check-circle text-[9px] text-tertiary-300"></i> Terverifikasi Admin
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-darkness-950/85 text-tertiary-300 backdrop-blur-sm">
                                    {{ $motor->tipe_cc }} CC
                                </span>
                            </div>

                            <div class="absolute bottom-3 right-3">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-white/90 text-slate-800 shadow-sm border border-slate-200">
                                    {{ $motor->no_plat }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                                <span>{{ $motor->merk }} &bull; Tahun {{ $motor->tahun }}</span>
                                <span class="text-slate-600 font-semibold flex items-center gap-1">
                                    <i class="fas fa-user-circle text-brand-600"></i> {{ Str::limit($motor->pemilik->nama ?? 'Mitra', 12) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 leading-snug mb-2">
                                {{ $motor->merk }} {{ $motor->tipe_motor }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                {{ $motor->deskripsi ?? 'Unit motor dalam kondisi prima dan siap digunakan.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer / Price & Action -->
                    <div class="p-5 pt-0 border-t border-slate-100 flex items-center justify-between mt-2">
                        <div>
                            <div class="text-[11px] text-slate-400 font-medium">Tarif Sewa Harian</div>
                            <div class="text-lg font-extrabold text-brand-600">
                                Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-400">/hari</span>
                            </div>
                        </div>
                        <a href="{{ route('motor.detail', $motor->id) }}" class="px-4 py-2.5 rounded-xl gradient-brand text-white text-xs font-bold shadow-md shadow-brand-600/25 hover:opacity-95 transition flex items-center gap-1.5">
                            Lihat Unit <i class="fas fa-chevron-right text-[10px] text-tertiary-300"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">
                    Belum ada motor yang aktif.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section for Owners (Secondary Hitam Pekat dengan Aksen Hijau & Kuning) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="rounded-3xl gradient-dark text-white p-8 sm:p-14 relative overflow-hidden shadow-2xl border border-darkness-800">
        <div class="absolute right-0 top-0 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-2xl relative z-10">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-tertiary-400/20 text-tertiary-300 border border-tertiary-400/30 mb-4">
                <i class="fas fa-coins text-tertiary-400"></i> Untuk Pemilik & Pengusaha Rental
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                Punya Motor Menganggur? <br>
                <span class="text-brand-400">Dapatkan Penghasilan Pasif</span> Sekarang!
            </h2>
            <p class="mt-4 text-sm text-slate-300 leading-relaxed">
                Daftarkan motor Anda ke platform RentRide. Kami menjamin keamanan aset dengan verifikasi ketat KTP & SIM penyewa serta sistem pencatatan tanggal serah terima. Dana otomatis masuk ke rekening Anda.
            </p>
            <div class="mt-8 flex flex-wrap gap-4 items-center">
                <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl gradient-brand text-white font-bold text-sm shadow-lg shadow-brand-600/30 hover:opacity-95 transition">
                    Daftar Sebagai Pemilik Motor
                </a>
                <a href="{{ route('demo.login', 'pemilik') }}" class="px-6 py-3.5 rounded-xl bg-darkness-900 hover:bg-darkness-850 text-slate-200 font-semibold text-sm transition border border-darkness-800">
                    Coba Demo Dashboard Pemilik
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
