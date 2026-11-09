@extends('layouts.main')

@section('title', $motor->merk . ' ' . $motor->tipe_motor . ' - Detail Sewa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-600">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="{{ route('katalog') }}" class="hover:text-slate-600">Katalog Motor</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-800 font-semibold">{{ $motor->merk }} {{ $motor->tipe_motor }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Kolom Kiri: Galeri Foto & Spesifikasi (7 Cols) -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Galeri Foto (Depan, Samping, Belakang Sesuai PRD) -->
            <div class="bg-white rounded-3xl p-4 border border-slate-200/80 shadow-sm overflow-hidden">
                <!-- Main Featured Photo -->
                <div class="h-80 sm:h-96 w-full rounded-2xl bg-slate-100 overflow-hidden relative mb-3">
                    <img id="mainPhoto" src="{{ $motor->foto_utama_url }}" alt="{{ $motor->merk }}" class="w-full h-full object-cover transition duration-300">
                    
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-600 text-white shadow-md flex items-center gap-1.5">
                            <i class="fas fa-certificate text-[10px] text-tertiary-300"></i> Disetujui Admin Platform
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-darkness-950/85 text-tertiary-300 backdrop-blur-sm border border-darkness-800">
                            STNK {{ $motor->dokumen_kepemilikan }}
                        </span>
                    </div>
                </div>

                <!-- Thumbnails (Depan, Samping, Belakang) Sesuai PRD -->
                <div class="grid grid-cols-4 gap-2">
                    <!-- Foto Utama -->
                    <button type="button" onclick="changePhoto('{{ $motor->foto_utama_url }}')" 
                        class="h-20 rounded-xl overflow-hidden border-2 border-brand-600 hover:opacity-90 transition focus:outline-none">
                        <img src="{{ $motor->foto_utama_url }}" class="w-full h-full object-cover">
                    </button>

                    <!-- Foto Depan -->
                    @if($motor->foto_depan)
                        <button type="button" onclick="changePhoto('{{ asset('storage/' . $motor->foto_depan) }}')" 
                            class="h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-brand-600 transition focus:outline-none">
                            <img src="{{ asset('storage/' . $motor->foto_depan) }}" class="w-full h-full object-cover">
                            <span class="block text-[9px] bg-darkness-950/80 text-white text-center -mt-4 relative">Depan</span>
                        </button>
                    @endif

                    <!-- Foto Samping -->
                    @if($motor->foto_samping)
                        <button type="button" onclick="changePhoto('{{ asset('storage/' . $motor->foto_samping) }}')" 
                            class="h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-brand-600 transition focus:outline-none">
                            <img src="{{ asset('storage/' . $motor->foto_samping) }}" class="w-full h-full object-cover">
                            <span class="block text-[9px] bg-darkness-950/80 text-white text-center -mt-4 relative">Samping</span>
                        </button>
                    @endif

                    <!-- Foto Belakang -->
                    @if($motor->foto_belakang)
                        <button type="button" onclick="changePhoto('{{ asset('storage/' . $motor->foto_belakang) }}')" 
                            class="h-20 rounded-xl overflow-hidden border border-slate-200 hover:border-brand-600 transition focus:outline-none">
                            <img src="{{ asset('storage/' . $motor->foto_belakang) }}" class="w-full h-full object-cover">
                            <span class="block text-[9px] bg-darkness-950/80 text-white text-center -mt-4 relative">Belakang</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Detail Spesifikasi Kendaraan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-brand-600"></i> Spesifikasi & Kondisi Unit
                </h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Merek Motor</span>
                        <span class="text-sm font-bold text-slate-800">{{ $motor->merk }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Model / Tipe</span>
                        <span class="text-sm font-bold text-slate-800">{{ $motor->tipe_motor }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Tahun Keluaran</span>
                        <span class="text-sm font-bold text-slate-800">{{ $motor->tahun }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Kapasitas Mesin</span>
                        <span class="text-sm font-bold text-brand-600">{{ $motor->tipe_cc }} CC</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Nomor Polisi (Plat)</span>
                        <span class="text-sm font-mono font-bold text-slate-900">{{ $motor->no_plat }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Warna Kendaraan</span>
                        <span class="text-sm font-bold text-slate-800">{{ $motor->warna ?? 'Standar' }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block font-medium">Status Pajak & STNK</span>
                        <span class="text-sm font-bold text-brand-600 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Aktif & Lengkap
                        </span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi & Kelengkapan</h4>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $motor->deskripsi ?? 'Unit motor terawat, servis berkala, gratis helm SNI dan jas hujan.' }}
                    </p>
                </div>

                <!-- Informasi Mitra Pemilik -->
                <div class="border-t border-slate-100 mt-6 pt-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-base border border-brand-200">
                            {{ strtoupper(substr($motor->pemilik->nama ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Mitra Pemilik Motor</span>
                            <span class="text-sm font-bold text-slate-900">{{ $motor->pemilik->nama ?? 'Rental Terverifikasi' }}</span>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-darkness-900 text-tertiary-400 border border-darkness-800">
                        <i class="fas fa-badge-check text-tertiary-400"></i> Rekening Verified
                    </span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Kalkulator Sewa & Formulir Checkout (5 Cols) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xl shadow-slate-200/50 sticky top-24">
                <div class="flex items-baseline justify-between pb-6 border-b border-slate-100 mb-6">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Tarif Sewa Harian</span>
                        <div class="text-3xl font-extrabold text-brand-600">
                            Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}
                            <span class="text-xs font-normal text-slate-400">/hari</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-100 text-brand-800 border border-brand-200 flex items-center gap-1">
                        <i class="fas fa-check text-brand-600"></i> Tersedia
                    </span>
                </div>

                <!-- Form Pemilihan Tanggal & Rincian Biaya Sesuai PRD -->
                <form action="{{ route('penyewa.checkout', $motor->id) }}" method="GET" class="space-y-4">
                    <!-- Tanggal Pengambilan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar-plus text-brand-600 mr-1"></i> Tanggal & Jam Pengambilan
                        </label>
                        <input type="datetime-local" id="inputMulai" name="tanggal_mulai" 
                            value="{{ $tanggalMulai }}" required
                            onchange="calculateTotal()"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Tanggal Pengembalian -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar-check text-brand-600 mr-1"></i> Tanggal & Jam Pengembalian
                        </label>
                        <input type="datetime-local" id="inputSelesai" name="tanggal_selesai" 
                            value="{{ $tanggalSelesai }}" required
                            onchange="calculateTotal()"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <!-- Ringkasan Perhitungan Biaya Real-Time Sesuai PRD -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Durasi Sewa:</span>
                            <span id="durasiText" class="font-bold text-slate-800">1 Hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sewa Motor (<span id="durasiCount">1</span> hari x Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}):</span>
                            <span id="subtotalText" class="font-bold text-slate-800">Rp {{ number_format($motor->tarif_harian, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Layanan Platform:</span>
                            <span class="font-bold text-slate-800">Rp 15.000</span>
                        </div>
                        <div class="border-t border-slate-200 pt-2.5 flex justify-between text-sm font-extrabold text-slate-900">
                            <span>Total Estimasi:</span>
                            <span id="totalText" class="text-brand-600">Rp {{ number_format($motor->tarif_harian + 15000, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Syarat KYC Info Sesuai PRD -->
                    <div class="p-3 rounded-xl bg-tertiary-50 border border-tertiary-300 text-[11px] text-tertiary-900 flex items-start gap-2">
                        <i class="fas fa-shield-halved text-tertiary-600 mt-0.5"></i>
                        <span>Wajib verifikasi identitas (KTP & SIM C) sebelum menyelesaikan checkout pemesanan.</span>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl gradient-brand text-white font-bold text-sm shadow-xl shadow-brand-600/30 hover:opacity-95 transition flex items-center justify-center gap-2">
                        <i class="fas fa-key text-tertiary-300"></i> Lanjut ke Checkout Pemesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function changePhoto(url) {
        document.getElementById('mainPhoto').src = url;
    }

    const tarifHarian = {{ $motor->tarif_harian }};
    const biayaLayanan = 15000;

    function calculateTotal() {
        const startVal = document.getElementById('inputMulai').value;
        const endVal = document.getElementById('inputSelesai').value;

        if (!startVal || !endVal) return;

        const start = new Date(startVal);
        const end = new Date(endVal);

        if (end <= start) {
            alert('Tanggal pengembalian harus lebih besar dari tanggal pengambilan.');
            return;
        }

        const diffTime = Math.abs(end - start);
        const diffHours = diffTime / (1000 * 60 * 60);
        let days = Math.ceil(diffHours / 24);
        if (days < 1) days = 1;

        const subtotal = days * tarifHarian;
        const total = subtotal + biayaLayanan;

        document.getElementById('durasiText').textContent = days + ' Hari (' + Math.round(diffHours) + ' Jam)';
        document.getElementById('durasiCount').textContent = days;
        document.getElementById('subtotalText').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('totalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endsection
