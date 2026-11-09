@extends('layouts.main')

@section('title', 'Instruksi Pembayaran Sewa Motor')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/50">
        <!-- Header -->
        <div class="text-center pb-6 border-b border-slate-100">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-tertiary-100 text-tertiary-800 border border-tertiary-200 mb-3">
                <i class="fas fa-clock animate-pulse"></i> Menunggu Pembayaran
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selesaikan Pembayaran Anda</h1>
            <p class="text-xs text-slate-500 mt-1">Lakukan pembayaran dalam 24 jam sebelum kode booking kedaluwarsa</p>
        </div>

        <!-- Total Tagihan Badge -->
        <div class="my-6 p-6 rounded-2xl bg-brand-50/60 border border-brand-100 text-center">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-1">Total yang Harus Dibayar</span>
            <div class="text-3xl sm:text-4xl font-extrabold text-brand-600">
                Rp {{ number_format($penyewaan->pembayaran->jumlah ?? $penyewaan->total_harga, 0, ',', '.') }}
            </div>
            <div class="text-xs text-slate-500 mt-2">
                Kode Transaksi: <span class="font-mono font-bold text-darkness-900">{{ $penyewaan->pembayaran->kode_transaksi }}</span>
            </div>
        </div>

        <!-- Detail Metode Pembayaran -->
        <div class="space-y-4 mb-8">
            <div class="p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-slate-400 font-medium block">Metode Pembayaran</span>
                    <span class="text-sm font-bold text-slate-800 uppercase">
                        {{ str_replace('_', ' ', $penyewaan->pembayaran->metode_pembayaran) }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-400 font-medium block">Nomor Tagihan / VA</span>
                    <span class="text-sm font-mono font-bold text-brand-600">
                        {{ $penyewaan->pembayaran->nomor_va_atau_rekening ?? '880812345678' }}
                    </span>
                </div>
            </div>

            <!-- Detail Motor Singkat -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                <img src="{{ $penyewaan->motor->foto_utama_url }}" class="w-14 h-14 rounded-xl object-cover">
                <div>
                    <span class="text-[10px] font-bold text-brand-600 uppercase">{{ $penyewaan->motor->merk }}</span>
                    <h4 class="text-xs font-bold text-slate-900">{{ $penyewaan->motor->tipe_motor }} ({{ $penyewaan->motor->no_plat }})</h4>
                    <span class="text-[11px] text-slate-500">
                        {{ $penyewaan->durasi_hari }} Hari &bull; {{ $penyewaan->tanggal_mulai->format('d M Y') }} s/d {{ $penyewaan->tanggal_selesai->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tombol Bayar Instan (Simulasi Payment Gateway Sesuai PRD) -->
        <div class="space-y-3">
            <form method="POST" action="{{ route('penyewa.pembayaran.bayar', $penyewaan->id) }}">
                @csrf
                <button type="submit" class="w-full py-4 rounded-xl gradient-brand text-white font-bold text-sm shadow-xl shadow-brand-600/30 hover:opacity-95 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-tertiary-300"></i> Bayar Sekarang (Simulasi Instan)
                </button>
            </form>

            <a href="{{ route('penyewa.riwayat') }}" class="w-full block py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition">
                Kembali ke Riwayat Pemesanan
            </a>
        </div>
    </div>
</div>
@endsection
