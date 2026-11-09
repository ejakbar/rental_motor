@extends('layouts.main')

@section('title', 'Tiket Pemesanan - ' . $penyewaan->kode_booking)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-600">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('penyewa.riwayat') }}" class="hover:text-slate-600">Riwayat Sewa</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-800 font-semibold">{{ $penyewaan->kode_booking }}</span>
    </nav>

    <!-- Main Ticket Card (Sesuai PRD Modul Penyewa) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 overflow-hidden">
        <!-- Ticket Header Banner (Gradient Hijau & Aksen Kuning Tersier) -->
        <div class="gradient-brand text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-bold tracking-widest uppercase bg-darkness-950/40 text-tertiary-300 px-3 py-1 rounded-full inline-block mb-2 border border-white/10">
                    <i class="fas fa-ticket text-xs mr-1 text-tertiary-300"></i> E-Tiket Rental Motor
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight font-mono text-white">
                    {{ $penyewaan->kode_booking }}
                </h1>
                <p class="text-xs text-brand-100 mt-1">
                    Dipesan pada {{ $penyewaan->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold tracking-wider block text-brand-200">Status Sewa</span>
                <div class="mt-1">
                    {!! $penyewaan->status_badge !!}
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <!-- Instruksi Pengambilan Motor Sesuai PRD -->
            <div class="p-5 rounded-2xl bg-brand-50/70 border border-brand-200 text-xs text-brand-950 space-y-2">
                <div class="font-bold text-sm text-brand-900 flex items-center gap-2">
                    <i class="fas fa-circle-info text-brand-600"></i> Instruksi Pengambilan Motor
                </div>
                <ol class="list-decimal list-inside space-y-1 text-slate-700 leading-relaxed">
                    <li>Hubungi kontak Pemilik Motor sebelum menuju lokasi serah terima.</li>
                    <li>Tunjukkan <strong>Kode Booking: {{ $penyewaan->kode_booking }}</strong> dan fisik KTP & SIM C Anda ke pemilik.</li>
                    <li>Periksa kelengkapan fisik motor, helm SNI, dan surat STNK bersama pemilik.</li>
                    <li>Pemilik akan menekan tombol serah terima di aplikasi untuk memulai masa sewa.</li>
                </ol>
            </div>

            <!-- Kontak Pemilik Kendaraan Sesuai PRD -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-lg border border-brand-200">
                        {{ strtoupper(substr($penyewaan->motor->pemilik->nama ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kontak Mitra Pemilik</span>
                        <h3 class="text-sm font-bold text-slate-900">{{ $penyewaan->motor->pemilik->nama ?? 'Mitra Pemilik' }}</h3>
                        <span class="text-xs text-slate-500">{{ $penyewaan->motor->pemilik->no_telp ?? '0812-xxxx-xxxx' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $penyewaan->motor->pemilik->no_telp ?? '08123456789') }}" target="_blank"
                        class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i class="fab fa-whatsapp text-sm text-tertiary-300"></i> Chat WhatsApp
                    </a>
                    <a href="tel:{{ $penyewaan->motor->pemilik->no_telp ?? '' }}" 
                        class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        <i class="fas fa-phone"></i>
                    </a>
                </div>
            </div>

            <!-- Jadwal & Batas Waktu Pengembalian Sesuai PRD -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i class="fas fa-calendar-plus text-brand-600 mr-1"></i> Tanggal Pengambilan
                    </div>
                    <div class="text-sm font-bold text-slate-900">{{ $penyewaan->tanggal_mulai->format('d M Y, H:i') }} WIB</div>
                    <div class="text-xs text-slate-500 mt-1">
                        Waktu Diambil: <strong>{{ $penyewaan->waktu_diambil ? $penyewaan->waktu_diambil->format('d M Y H:i') . ' WIB' : 'Menunggu serah terima' }}</strong>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                        <i class="fas fa-calendar-check text-rose-500 mr-1"></i> Batas Waktu Pengembalian
                    </div>
                    <div class="text-sm font-bold text-slate-900">{{ $penyewaan->tanggal_selesai->format('d M Y, H:i') }} WIB</div>
                    <div class="text-xs text-slate-500 mt-1">
                        Waktu Dikembalikan: <strong>{{ $penyewaan->waktu_dikembalikan ? $penyewaan->waktu_dikembalikan->format('d M Y H:i') . ' WIB' : 'Belum dikembalikan' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Detail Motor yang Disewa -->
            <div class="p-5 rounded-2xl border border-slate-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $penyewaan->motor->foto_utama_url }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                    <div>
                        <span class="text-[10px] font-bold text-brand-600 uppercase">{{ $penyewaan->motor->merk }} &bull; {{ $penyewaan->motor->tipe_cc }} CC</span>
                        <h4 class="text-base font-bold text-slate-900">{{ $penyewaan->motor->tipe_motor }}</h4>
                        <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ $penyewaan->motor->no_plat }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block font-medium">Total Dibayar</span>
                    <span class="text-lg font-extrabold text-brand-600">Rp {{ number_format($penyewaan->total_harga, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-brand-700 font-bold block"><i class="fas fa-check-circle"></i> Lunas</span>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            @if($penyewaan->catatan_penyewa)
                <div class="p-4 rounded-xl bg-slate-50 text-xs text-slate-600">
                    <strong>Catatan Penyewa:</strong> {{ $penyewaan->catatan_penyewa }}
                </div>
            @endif

            <div class="pt-4 flex items-center justify-between">
                <a href="{{ route('penyewa.riwayat') }}" class="text-xs font-bold text-slate-600 hover:text-brand-600 transition flex items-center gap-1.5">
                    <i class="fas fa-arrow-left"></i> Kembali ke Riwayat Sewa
                </a>
                <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-darkness-900 hover:bg-darkness-950 text-tertiary-300 font-bold text-xs transition flex items-center gap-1.5 border border-darkness-800">
                    <i class="fas fa-print"></i> Cetak E-Tiket
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
