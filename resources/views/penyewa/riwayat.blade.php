@extends('layouts.main')

@section('title', 'Riwayat Pemesanan Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Sewa Motor</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar semua transaksi dan tiket pemesanan aktif Anda</p>
        </div>
        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl gradient-brand text-white font-bold text-xs shadow-md shadow-brand-600/30 hover:opacity-95 transition">
            <i class="fas fa-plus text-tertiary-300"></i> Sewa Motor Baru
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 text-2xl flex items-center justify-center mx-auto mb-4 border border-brand-200">
                <i class="fas fa-ticket"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Riwayat Sewa</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                Anda belum pernah melakukan pemesanan sewa motor di platform RentRide.
            </p>
            <a href="{{ route('katalog') }}" class="px-5 py-3 rounded-xl gradient-brand text-white font-bold text-xs shadow-md shadow-brand-600/30">
                Cari & Sewa Motor Sekarang
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Left info: Motor & Code -->
                        <div class="flex items-center gap-4">
                            <img src="{{ $booking->motor->foto_utama_url }}" class="w-20 h-20 rounded-2xl object-cover shrink-0">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono font-bold text-slate-500">{{ $booking->kode_booking }}</span>
                                    {!! $booking->status_badge !!}
                                </div>
                                <h3 class="text-base font-bold text-slate-900">
                                    {{ $booking->motor->merk }} {{ $booking->motor->tipe_motor }}
                                </h3>
                                <span class="text-xs font-mono text-slate-500">{{ $booking->motor->no_plat }} &bull; Mitra: {{ $booking->motor->pemilik->nama ?? 'Mitra' }}</span>
                            </div>
                        </div>

                        <!-- Mid info: Schedule -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 font-medium block">Pengambilan</span>
                                <span class="font-bold text-slate-800">{{ $booking->tanggal_mulai->format('d M Y') }}</span>
                                <span class="text-slate-500 block">{{ $booking->tanggal_mulai->format('H:i') }} WIB</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-medium block">Pengembalian</span>
                                <span class="font-bold text-slate-800">{{ $booking->tanggal_selesai->format('d M Y') }}</span>
                                <span class="text-slate-500 block">{{ $booking->tanggal_selesai->format('H:i') }} WIB</span>
                            </div>
                        </div>

                        <!-- Right info: Price & Action -->
                        <div class="flex items-center justify-between lg:justify-end gap-6 border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100">
                            <div class="text-left lg:text-right">
                                <span class="text-[10px] text-slate-400 font-medium block">Total Pembayaran</span>
                                <span class="text-base font-extrabold text-brand-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $booking->durasi_hari }} Hari Sewa</span>
                            </div>

                            <div>
                                @if($booking->status_penyewaan === 'menunggu_pembayaran')
                                    <a href="{{ route('penyewa.pembayaran', $booking->id) }}" class="px-4 py-2.5 rounded-xl bg-tertiary-500 hover:bg-tertiary-600 text-darkness-950 font-bold text-xs shadow-sm transition">
                                        Bayar Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('penyewa.tiket', $booking->id) }}" class="px-4 py-2.5 rounded-xl gradient-brand text-white font-bold text-xs shadow-sm hover:opacity-95 transition flex items-center gap-1.5">
                                        <i class="fas fa-ticket text-xs text-tertiary-300"></i> Buka Tiket
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
