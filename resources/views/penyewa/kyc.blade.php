@extends('layouts.main')

@section('title', 'Verifikasi Identitas Penyewa (KYC)')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Verifikasi Identitas (KYC)</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Dokumen Anda disimpan secara terenkripsi dan diverifikasi oleh Admin platform</p>
    </div>

    <!-- Status KYC Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-8">
        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 font-medium block">Status Verifikasi Saat Ini</span>
                <div class="mt-1">
                    @if($user->status_kyc === 'verified')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-brand-100 text-brand-800 border border-brand-200">
                            <i class="fas fa-circle-check text-brand-600"></i> Terverifikasi (Siap Sewa Motor)
                        </span>
                    @elseif($user->status_kyc === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-tertiary-100 text-tertiary-900 border border-tertiary-300">
                            <i class="fas fa-hourglass-half text-tertiary-600 animate-spin"></i> Menunggu Verifikasi Admin (1x24 Jam)
                        </span>
                    @elseif($user->status_kyc === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            <i class="fas fa-times-circle text-rose-600"></i> Dokumen Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                            <i class="fas fa-circle-xmark text-slate-400"></i> Belum Melakukan Verifikasi
                        </span>
                    @endif
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $user->isKycVerified() ? 'bg-brand-50 text-brand-600 border border-brand-200' : 'bg-darkness-900 text-tertiary-400' }} flex items-center justify-center text-xl">
                <i class="fas fa-shield-halved"></i>
            </div>
        </div>

        @if($user->catatan_kyc)
            <div class="mt-4 p-4 rounded-2xl {{ $user->status_kyc === 'rejected' ? 'bg-rose-50 border border-rose-200 text-rose-900' : 'bg-slate-50 text-slate-700' }} text-xs">
                <strong>Catatan Admin:</strong> {{ $user->catatan_kyc }}
            </div>
        @endif
    </div>

    <!-- Upload KYC Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <h3 class="font-bold text-slate-900 text-sm mb-4 flex items-center gap-2">
            <i class="fas fa-id-card-clip text-brand-600"></i> Formulir Unggah Dokumen KYC
        </h3>

        <form method="POST" action="{{ route('penyewa.kyc.submit') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Nomor KTP -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Nomor Induk Kependudukan (NIK / KTP)
                </label>
                <input type="text" name="no_ktp" value="{{ old('no_ktp', $user->no_ktp) }}" required placeholder="16 digit nomor KTP Anda"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('no_ktp') border-rose-500 @enderror">
                @error('no_ktp')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto KTP -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Foto e-KTP Asli
                </label>
                <input type="file" name="foto_ktp" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="text-[11px] text-slate-400 mt-1">Pastikan foto KTP tidak buram, nama dan NIK terbaca jelas.</p>
            </div>

            <!-- Nomor SIM C -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Nomor Surat Izin Mengemudi (SIM C)
                </label>
                <input type="text" name="no_sim" value="{{ old('no_sim', $user->no_sim) }}" required placeholder="Nomor SIM C Anda"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('no_sim') border-rose-500 @enderror">
                @error('no_sim')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto SIM C -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Foto SIM C Asli
                </label>
                <input type="file" name="foto_sim" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="text-[11px] text-slate-400 mt-1">SIM C wajib masih berlaku.</p>
            </div>

            <!-- Upload Foto Selfie dengan KTP -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Foto Selfie (Swafoto) Wajah
                </label>
                <input type="file" name="foto_selfie" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="text-[11px] text-slate-400 mt-1">Foto wajah jelas tanpa kacamata hitam atau masker.</p>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl gradient-brand text-white font-bold text-sm shadow-xl shadow-brand-600/30 hover:opacity-95 transition">
                Simpan & Kirim Dokumen untuk Verifikasi
            </button>
        </form>
    </div>
</div>
@endsection
