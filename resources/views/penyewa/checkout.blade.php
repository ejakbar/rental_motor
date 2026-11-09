@extends('layouts.main')

@section('title', 'Checkout Pemesanan Sewa Motor')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Checkout Pemesanan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Periksa kembali detail sewa motor dan pilih metode pembayaran</p>
    </div>

    <!-- Pengecekan KYC Sesuai PRD -->
    @if(!$user->isKycVerified())
        <div class="p-6 rounded-3xl bg-tertiary-50 border border-tertiary-300 mb-8 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-tertiary-500 text-darkness-950 font-bold flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-darkness-950">Verifikasi Identitas (KYC) Diperlukan</h3>
                    <p class="text-xs text-slate-700 mt-1 leading-relaxed">
                        Sesuai standar operasional keamanan di PRD platform kami, setiap penyewa <strong>wajib</strong> mengunggah KTP dan SIM C serta diverifikasi oleh Admin sebelum dapat menyelesaikan pemesanan.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('penyewa.kyc') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-darkness-900 hover:bg-darkness-950 text-tertiary-300 font-bold text-xs shadow-sm transition">
                            <i class="fas fa-upload text-tertiary-400"></i> Lengkapi Dokumen KYC Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('penyewa.checkout.process') }}">
        @csrf
        <input type="hidden" name="motor_id" value="{{ $motor->id }}">
        <input type="hidden" name="tanggal_mulai" value="{{ $mulai->format('Y-m-d H:i:s') }}">
        <input type="hidden" name="tanggal_selesai" value="{{ $selesai->format('Y-m-d H:i:s') }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Kolom Kiri: Detail Sewa & Metode Pembayaran (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Card Detail Jadwal -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-brand-600"></i> Jadwal Pengambilan & Pengembalian
                    </h3>

                    <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Tanggal Pengambilan</span>
                            <span class="text-xs font-bold text-slate-800 block">{{ $mulai->format('d M Y') }}</span>
                            <span class="text-[11px] text-brand-600 font-semibold">{{ $mulai->format('H:i') }} WIB</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Tanggal Pengembalian</span>
                            <span class="text-xs font-bold text-slate-800 block">{{ $selesai->format('d M Y') }}</span>
                            <span class="text-[11px] text-brand-600 font-semibold">{{ $selesai->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="mt-3 text-xs text-slate-500 flex items-center justify-between">
                        <span>Total Durasi: <strong>{{ $durasiHari }} Hari</strong></span>
                        <a href="{{ route('motor.detail', $motor->id) }}" class="text-brand-600 font-semibold hover:underline">Ubah Jadwal</a>
                    </div>
                </div>

                <!-- Card Pilihan Metode Pembayaran Sesuai PRD -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-brand-600"></i> Pilihan Metode Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <!-- VA BCA -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:border-brand-600 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="metode_pembayaran" value="va_bca" checked class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">BCA Virtual Account</div>
                                    <div class="text-[10px] text-slate-400">Verifikasi instan otomatis 24 jam</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">BCA</span>
                        </label>

                        <!-- VA Mandiri -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:border-brand-600 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="metode_pembayaran" value="va_mandiri" class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">Mandiri Virtual Account</div>
                                    <div class="text-[10px] text-slate-400">Verifikasi instan otomatis 24 jam</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">Mandiri</span>
                        </label>

                        <!-- VA BRI -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:border-brand-600 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="metode_pembayaran" value="va_bri" class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">BRI BRIVA</div>
                                    <div class="text-[10px] text-slate-400">Verifikasi instan otomatis 24 jam</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">BRI</span>
                        </label>

                        <!-- QRIS / E-Wallet -->
                        <label class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 cursor-pointer hover:border-brand-600 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50/50">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="metode_pembayaran" value="qris" class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">QRIS (GoPay, OVO, ShopeePay, Dana)</div>
                                    <div class="text-[10px] text-slate-400">Scan QR Code instan dari aplikasi apa saja</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">QRIS</span>
                        </label>
                    </div>
                </div>

                <!-- Catatan Tambahan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan untuk Pemilik (Opsional)</label>
                    <textarea name="catatan_penyewa" rows="2" placeholder="Contoh: Titip 2 helm ukuran L dan jas hujan..."
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500"></textarea>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Tagihan Sesuai PRD (5 Cols) -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm sticky top-24">
                    <h3 class="font-bold text-slate-900 text-sm mb-4">Ringkasan Pesanan</h3>

                    <!-- Mini Motor Card -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100 mb-6">
                        <img src="{{ $motor->foto_utama_url }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        <div>
                            <span class="text-[10px] font-bold text-brand-600 uppercase">{{ $motor->merk }} &bull; {{ $motor->tipe_cc }} CC</span>
                            <h4 class="text-sm font-bold text-slate-900">{{ $motor->tipe_motor }}</h4>
                            <span class="text-xs font-mono text-slate-500">{{ $motor->no_plat }}</span>
                        </div>
                    </div>

                    <!-- Breakdown Biaya Sesuai PRD -->
                    <div class="space-y-3 text-xs text-slate-600 border-b border-slate-100 pb-5 mb-5">
                        <div class="flex justify-between">
                            <span>Tarif Sewa Harian</span>
                            <span class="font-semibold text-slate-800">Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Durasi Sewa</span>
                            <span class="font-semibold text-slate-800">{{ $durasiHari }} Hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subtotal Sewa Motor</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-brand-700">
                            <span class="flex items-center gap-1">
                                Biaya Layanan Platform
                                <i class="fas fa-circle-info text-[10px]" title="Biaya pemeliharaan platform & asuransi transaksi"></i>
                            </span>
                            <span class="font-bold">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Total Tagihan -->
                    <div class="flex items-baseline justify-between mb-6">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Total Pembayaran</span>
                        <div class="text-2xl font-extrabold text-brand-600">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </div>
                    </div>

                    @if($user->isKycVerified())
                        <div class="mb-4 p-3 rounded-xl bg-brand-50 border border-brand-200 text-[11px] text-brand-900 flex items-center gap-2">
                            <i class="fas fa-check-circle text-brand-600"></i>
                            <span>Status KYC: <strong>Terverifikasi</strong> (No. SIM: {{ $user->no_sim }})</span>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl gradient-brand text-white font-bold text-sm shadow-xl shadow-brand-600/30 hover:opacity-95 transition flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-tertiary-300"></i> Konfirmasi & Lanjut Bayar
                        </button>
                    @else
                        <button type="button" disabled class="w-full py-4 rounded-xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i> Lengkapi KYC untuk Bayar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
