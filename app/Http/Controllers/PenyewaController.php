<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenyewaController extends Controller
{
    /**
     * Tampilan Form Checkout
     */
    public function checkoutForm(Request $request, $motorId)
    {
        $user = Auth::user();
        $motor = Motor::with('pemilik')->findOrFail($motorId);

        // Validasi ketersediaan dasar motor
        if ($motor->status_approval !== 'approved' || !$motor->is_active) {
            return redirect()->route('katalog')->with('error', 'Motor ini sedang tidak dapat disewa.');
        }

        // Tanggal dan durasi
        $mulai = $request->filled('tanggal_mulai') ? Carbon::parse($request->tanggal_mulai) : Carbon::tomorrow()->setTime(9, 0);
        $selesai = $request->filled('tanggal_selesai') ? Carbon::parse($request->tanggal_selesai) : Carbon::tomorrow()->addDays(2)->setTime(9, 0);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            $selesai = (clone $mulai)->addDays(1);
        }

        // Concurrency Check (Mencegah Double-Booking sesuai PRD)
        if (!$motor->isAvailableBetween($mulai, $selesai)) {
            return redirect()->route('motor.detail', $motor->id)
                ->with('error', 'Mohon maaf, motor ini sudah di-booking pada rentang tanggal tersebut. Silakan pilih tanggal lain atau motor lainnya.');
        }

        // Perhitungan Durasi & Biaya Sesuai PRD: (Tarif Harian x Durasi Hari) + Biaya Layanan Platform
        $diffInHours = $mulai->diffInHours($selesai);
        $durasiHari = max(1, (int) ceil($diffInHours / 24));
        
        $subtotal = $motor->tarif_harian * $durasiHari;
        $biayaLayanan = 15000.00; // Biaya layanan platform sesuai PRD
        $totalHarga = $subtotal + $biayaLayanan;

        return view('penyewa.checkout', compact('motor', 'user', 'mulai', 'selesai', 'durasiHari', 'subtotal', 'biayaLayanan', 'totalHarga'));
    }

    /**
     * Proses Pembuatan Booking & Penguncian Tanggal (Concurrency Handling)
     */
    public function processCheckout(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi KYC Sesuai PRD: Wajib KTP dan SIM C terverifikasi sebelum checkout
        if (!$user->isKycVerified()) {
            return redirect()->route('penyewa.kyc')
                ->with('warning', 'Sesuai standar keamanan platform, Anda wajib melengkapi dan memverifikasi identitas (KTP & SIM C) sebelum melakukan sewa motor.');
        }

        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:now',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|string',
            'catatan_penyewa' => 'nullable|string|max:500',
        ]);

        $motor = Motor::findOrFail($validated['motor_id']);
        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $selesai = Carbon::parse($validated['tanggal_selesai']);

        return DB::transaction(function () use ($user, $motor, $mulai, $selesai, $validated) {
            // Lock motor row for update untuk mencegah race condition (Concurrency handling)
            $lockedMotor = Motor::where('id', $motor->id)->lockForUpdate()->first();

            if (!$lockedMotor->isAvailableBetween($mulai, $selesai)) {
                return redirect()->route('motor.detail', $motor->id)
                    ->with('error', 'Motor baru saja dipesan oleh pengguna lain untuk tanggal tersebut. Silakan pilih tanggal atau motor lain.');
            }

            $diffInHours = $mulai->diffInHours($selesai);
            $durasiHari = max(1, (int) ceil($diffInHours / 24));
            $subtotal = $lockedMotor->tarif_harian * $durasiHari;
            $biayaLayanan = 15000.00;
            $totalHarga = $subtotal + $biayaLayanan;

            $kodeBooking = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Buat data penyewaan
            $penyewaan = Penyewaan::create([
                'kode_booking' => $kodeBooking,
                'penyewa_id' => $user->id,
                'motor_id' => $lockedMotor->id,
                'tanggal_mulai' => $mulai,
                'tanggal_selesai' => $selesai,
                'durasi_hari' => $durasiHari,
                'tipe_durasi' => 'harian',
                'tarif_per_hari' => $lockedMotor->tarif_harian,
                'subtotal' => $subtotal,
                'biaya_layanan' => $biayaLayanan,
                'total_harga' => $totalHarga,
                'status_penyewaan' => 'menunggu_pembayaran',
                'catatan_penyewa' => $validated['catatan_penyewa'] ?? null,
            ]);

            // Buat tagihan pembayaran
            $kodeTrx = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $nomorVa = match($validated['metode_pembayaran']) {
                'va_bca' => '8808' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'va_mandiri' => '8920' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'va_bri' => '1023' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'qris' => 'QRIS-' . $kodeBooking,
                default => 'TRF-' . rand(10000000, 99999999),
            };

            Pembayaran::create([
                'penyewaan_id' => $penyewaan->id,
                'kode_transaksi' => $kodeTrx,
                'jumlah' => $totalHarga,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'pending',
                'nomor_va_atau_rekening' => $nomorVa,
            ]);

            return redirect()->route('penyewa.pembayaran', $penyewaan->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        });
    }

    /**
     * Halaman Pembayaran (Instruksi & Tombol Bayar)
     */
    public function paymentPage($penyewaanId)
    {
        $penyewaan = Penyewaan::with(['motor.pemilik', 'pembayaran'])->findOrFail($penyewaanId);

        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403);
        }

        return view('penyewa.pembayaran', compact('penyewaan'));
    }

    /**
     * Simulasi Konfirmasi Pembayaran Berhasil
     */
    public function simulatePay(Request $request, $penyewaanId)
    {
        $penyewaan = Penyewaan::with('pembayaran', 'motor')->findOrFail($penyewaanId);

        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($penyewaan) {
            $penyewaan->pembayaran->update([
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => Carbon::now(),
            ]);

            $penyewaan->update([
                'status_penyewaan' => 'disetujui', // Siap diambil / menunggu serah terima pemilik
            ]);
        });

        return redirect()->route('penyewa.tiket', $penyewaan->id)
            ->with('success', '🎉 Pembayaran Berhasil! Tiket sewa motor Anda telah aktif.');
    }

    /**
     * Tiket Pemesanan Aktif Sesuai PRD:
     * Instruksi pengambilan, kontak Pemilik, batas pengembalian
     */
    public function showTicket($penyewaanId)
    {
        $penyewaan = Penyewaan::with(['motor.pemilik', 'pembayaran'])->findOrFail($penyewaanId);

        if ($penyewaan->penyewa_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('penyewa.tiket', compact('penyewaan'));
    }

    /**
     * Riwayat Pemesanan Penyewa
     */
    public function myBookings()
    {
        $bookings = Penyewaan::with(['motor.pemilik', 'pembayaran'])
            ->where('penyewa_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('penyewa.riwayat', compact('bookings'));
    }

    /**
     * Halaman KYC Penyewa
     */
    public function kycForm()
    {
        $user = Auth::user();
        return view('penyewa.kyc', compact('user'));
    }

    /**
     * Submit Dokumen KYC Penyewa
     */
    public function kycSubmit(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'no_ktp' => 'required|string|min:16|max:20',
            'no_sim' => 'required|string|min:10|max:20',
            'foto_ktp' => 'nullable|image|max:3072',
            'foto_sim' => 'nullable|image|max:3072',
            'foto_selfie' => 'nullable|image|max:3072',
        ]);

        $dataUpdate = [
            'no_ktp' => $validated['no_ktp'],
            'no_sim' => $validated['no_sim'],
            'status_kyc' => 'pending', // Menunggu kurasi verifikasi admin sesuai PRD
            'catatan_kyc' => 'Dokumen baru dikirim, menunggu verifikasi Admin.',
        ];

        if ($request->hasFile('foto_ktp')) {
            $dataUpdate['foto_ktp'] = $request->file('foto_ktp')->store('kyc', 'public');
        }
        if ($request->hasFile('foto_sim')) {
            $dataUpdate['foto_sim'] = $request->file('foto_sim')->store('kyc', 'public');
        }
        if ($request->hasFile('foto_selfie')) {
            $dataUpdate['foto_selfie'] = $request->file('foto_selfie')->store('kyc', 'public');
        }

        $user->update($dataUpdate);

        return redirect()->route('penyewa.kyc')
            ->with('success', 'Dokumen identitas berhasil diunggah! Admin platform akan memverifikasi dalam kurun waktu 1x24 jam.');
    }
}

