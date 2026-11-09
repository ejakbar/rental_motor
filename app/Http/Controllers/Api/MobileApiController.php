<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\PenarikanDana;
use App\Models\Penyewaan;
use App\Models\Tarif_rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileApiController extends Controller
{
    /**
     * 1. Public / Mobile Catalog API (Pencarian Berbasis Kalender Sesuai PRD)
     * GET /api/motors
     */
    public function publicMotors(Request $request)
    {
        $query = Motor::with('pemilik')->approved()->active();

        $startDate = $request->filled('tanggal_mulai') ? Carbon::parse($request->tanggal_mulai) : null;
        $endDate = $request->filled('tanggal_selesai') ? Carbon::parse($request->tanggal_selesai) : null;

        if ($startDate && $endDate) {
            $bookedMotorIds = Penyewaan::whereNotIn('status_penyewaan', ['batal', 'selesai'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->where('tanggal_mulai', '<=', $endDate)
                      ->where('tanggal_selesai', '>=', $startDate);
                })
                ->pluck('motor_id')
                ->toArray();

            $query->whereNotIn('id', $bookedMotorIds);
        } else {
            if (!$request->has('include_all')) {
                $query->where('status', 'tersedia');
            }
        }

        if ($request->filled('merk')) {
            $query->where('merk', $request->merk);
        }
        if ($request->filled('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }
        if ($request->filled('max_harga')) {
            $query->where('tarif_harian', '<=', $request->max_harga);
        }

        $motors = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar motor tersedia berhasil diambil',
            'data' => $motors,
        ]);
    }

    /**
     * Detail Motor Mobile API
     * GET /api/motors/{id}
     */
    public function motorDetail($id)
    {
        $motor = Motor::with(['pemilik', 'tarifRental'])->find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'motor' => $motor,
                'foto_utama' => $motor->foto_utama_url,
                'is_available' => $motor->status === 'tersedia' && $motor->status_approval === 'approved',
            ],
        ]);
    }

    /**
     * 2. Auth Mobile API
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi tidak cocok.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'is_kyc_verified' => $user->isKycVerified(),
            ],
        ]);
    }

    /**
     * POST /api/auth/register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:penggunas,email',
            'no_telp' => 'required|string|max:20',
            'role' => 'required|in:penyewa,pemilik',
            'password' => 'required|min:6',
            'nama_bank' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:50',
            'atas_nama_rekening' => 'nullable|string|max:100',
        ]);

        $user = Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'nama_bank' => $validated['nama_bank'] ?? null,
            'no_rekening' => $validated['no_rekening'] ?? null,
            'atas_nama_rekening' => $validated['atas_nama_rekening'] ?? null,
            'status_kyc' => $validated['role'] === 'pemilik' ? 'verified' : 'belum_verifikasi',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi akun berhasil',
            'data' => [
                'user' => $user,
            ],
        ], 201);
    }

    /**
     * 3. Renter Mobile API: Riwayat & Tiket
     * GET /api/renter/bookings
     */
    public function renterBookings(Request $request)
    {
        $userId = $request->user_id ?? $request->header('X-User-Id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User ID diperlukan'], 400);
        }

        $bookings = Penyewaan::with(['motor.pemilik', 'pembayaran'])
            ->where('penyewa_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * POST /api/renter/bookings (Create booking with anti double-booking & KYC check)
     */
    public function renterCreateBooking(Request $request)
    {
        $userId = $request->user_id ?? $request->header('X-User-Id');
        $user = Pengguna::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan'], 404);
        }

        // KYC check sesuai PRD
        if (!$user->isKycVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Wajib verifikasi dokumen KYC (KTP & SIM C) sebelum melakukan sewa.',
                'status_kyc' => $user->status_kyc,
            ], 403);
        }

        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:now',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|string',
            'catatan_penyewa' => 'nullable|string',
        ]);

        $motor = Motor::findOrFail($validated['motor_id']);
        $mulai = Carbon::parse($validated['tanggal_mulai']);
        $selesai = Carbon::parse($validated['tanggal_selesai']);

        if (!$motor->isAvailableBetween($mulai, $selesai)) {
            return response()->json([
                'success' => false,
                'message' => 'Motor sudah dibooking pada rentang tanggal tersebut (anti double-booking).',
            ], 409);
        }

        $diffInHours = $mulai->diffInHours($selesai);
        $durasiHari = max(1, (int) ceil($diffInHours / 24));
        $subtotal = $motor->tarif_harian * $durasiHari;
        $biayaLayanan = 15000.00;
        $totalHarga = $subtotal + $biayaLayanan;

        $kodeBooking = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $penyewaan = Penyewaan::create([
            'kode_booking' => $kodeBooking,
            'penyewa_id' => $user->id,
            'motor_id' => $motor->id,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'durasi_hari' => $durasiHari,
            'tipe_durasi' => 'harian',
            'tarif_per_hari' => $motor->tarif_harian,
            'subtotal' => $subtotal,
            'biaya_layanan' => $biayaLayanan,
            'total_harga' => $totalHarga,
            'status_penyewaan' => 'menunggu_pembayaran',
            'catatan_penyewa' => $validated['catatan_penyewa'] ?? null,
        ]);

        $kodeTrx = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $nomorVa = match($validated['metode_pembayaran']) {
            'va_bca' => '8808' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
            'va_mandiri' => '8920' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
            'va_bri' => '1023' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
            'qris' => 'QRIS-' . $kodeBooking,
            default => 'TRF-' . rand(10000000, 99999999),
        };

        $pembayaran = Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_transaksi' => $kodeTrx,
            'jumlah' => $totalHarga,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'pending',
            'nomor_va_atau_rekening' => $nomorVa,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat. Silakan lakukan pembayaran.',
            'data' => [
                'penyewaan' => $penyewaan,
                'pembayaran' => $pembayaran,
            ],
        ], 201);
    }

    /**
     * POST /api/payments/{id}/simulate
     */
    public function simulatePayment($penyewaanId)
    {
        $penyewaan = Penyewaan::with('pembayaran')->findOrFail($penyewaanId);

        $penyewaan->pembayaran->update([
            'status_pembayaran' => 'lunas',
            'tanggal_pembayaran' => Carbon::now(),
        ]);

        $penyewaan->update([
            'status_penyewaan' => 'disetujui',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran lunas! Tiket rental aktif.',
            'data' => $penyewaan,
        ]);
    }

    /**
     * 4. Owner Mobile API
     * GET /api/owner/motors
     */
    public function ownerMotors(Request $request)
    {
        $ownerId = $request->owner_id ?? $request->header('X-User-Id');
        if (!$ownerId) {
            return response()->json(['success' => false, 'message' => 'Owner ID diperlukan'], 400);
        }

        $motors = Motor::where('pemilik_id', $ownerId)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $motors,
        ]);
    }

    /**
     * GET /api/owner/orders
     */
    public function ownerOrders(Request $request)
    {
        $ownerId = $request->owner_id ?? $request->header('X-User-Id');
        if (!$ownerId) {
            return response()->json(['success' => false, 'message' => 'Owner ID diperlukan'], 400);
        }

        $orders = Penyewaan::with(['motor', 'penyewa', 'pembayaran'])
            ->whereHas('motor', function ($q) use ($ownerId) {
                $q->where('pemilik_id', $ownerId);
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * POST /api/owner/orders/{id}/handover
     */
    public function ownerHandover($id)
    {
        $penyewaan = Penyewaan::with('motor')->findOrFail($id);

        $penyewaan->update([
            'status_penyewaan' => 'on_rent',
            'waktu_diambil' => Carbon::now(),
        ]);

        $penyewaan->motor->update([
            'status' => 'disewa',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Serah terima motor berhasil dikonfirmasi. Motor kini berstatus On Rent.',
            'data' => $penyewaan,
        ]);
    }

    /**
     * POST /api/owner/orders/{id}/complete
     */
    public function ownerComplete($id)
    {
        $penyewaan = Penyewaan::with(['motor', 'motor.pemilik'])->findOrFail($id);
        $pemilik = $penyewaan->motor->pemilik;

        $now = Carbon::now();
        $denda = 0;
        if ($now->greaterThan($penyewaan->tanggal_selesai)) {
            $hoursLate = $penyewaan->tanggal_selesai->diffInHours($now);
            if ($hoursLate > 1) {
                $denda = $hoursLate * 10000;
            }
        }

        $penyewaan->update([
            'status_penyewaan' => 'selesai',
            'waktu_dikembalikan' => $now,
            'denda_keterlambatan' => $denda,
        ]);

        $penyewaan->motor->update([
            'status' => 'tersedia',
        ]);

        // Bagi hasil 90% pemilik, 10% admin
        $totalSewa = $penyewaan->subtotal + $denda;
        $komisiAdmin = $totalSewa * 0.10;
        $bagiHasilPemilik = $totalSewa - $komisiAdmin;

        $bagiHasil = Bagi_hasil::create([
            'penyewaan_id' => $penyewaan->id,
            'pemilik_id' => $pemilik->id,
            'total_biaya_sewa' => $totalSewa,
            'persentase_komisi' => 10.00,
            'komisi_admin' => $komisiAdmin,
            'bagi_hasil_pemilik' => $bagiHasilPemilik,
            'status_bagi_hasil' => 'masuk_saldo',
            'tanggal_bagi_hasil' => $now,
        ]);

        $pemilik->increment('saldo', $bagiHasilPemilik);

        return response()->json([
            'success' => true,
            'message' => 'Motor kembali dikonfirmasi. Bagi hasil otomatis masuk ke saldo pemilik.',
            'data' => [
                'bagi_hasil' => $bagiHasil,
                'saldo_terbaru' => $pemilik->fresh()->saldo,
            ],
        ]);
    }

    /**
     * GET /api/owner/wallet
     */
    public function ownerWallet(Request $request)
    {
        $ownerId = $request->owner_id ?? $request->header('X-User-Id');
        $pemilik = Pengguna::find($ownerId);

        if (!$pemilik) {
            return response()->json(['success' => false, 'message' => 'Pemilik tidak ditemukan'], 404);
        }

        $bagiHasils = Bagi_hasil::where('pemilik_id', $ownerId)->latest()->get();
        $withdrawals = PenarikanDana::where('pemilik_id', $ownerId)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'saldo' => $pemilik->saldo,
                'rekening' => [
                    'nama_bank' => $pemilik->nama_bank,
                    'no_rekening' => $pemilik->no_rekening,
                    'atas_nama' => $pemilik->atas_nama_rekening,
                ],
                'riwayat_bagi_hasil' => $bagiHasils,
                'riwayat_penarikan' => $withdrawals,
            ],
        ]);
    }
}

