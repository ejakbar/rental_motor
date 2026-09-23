<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\BagiHasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Create payment for booking
     * POST /api/payments
     */
    public function createPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
            'bukti_pembayaran' => 'nullable|image|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $penyewaan = Penyewaan::with(['motor', 'penyewa'])->find($request->penyewaan_id);

        // Check if user is authorized
        if ($penyewaan->penyewa_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Check if already paid
        if ($penyewaan->transaksi && $penyewaan->transaksi->status === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah lunas'
            ], 400);
        }

        // Check booking status
        if ($penyewaan->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibatalkan'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $transaksiData = [
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->metode_pembayaran === 'cash' ? 'lunas' : 'pending',
                'tanggal' => now()
            ];

            // Handle bukti pembayaran upload
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('payments', 'public');
                $transaksiData['bukti_pembayaran'] = $path;
            }

            // Create or update transaction
            $transaksi = Transaksi::updateOrCreate(
                ['penyewaan_id' => $penyewaan->id],
                $transaksiData
            );

            // If payment is confirmed (cash), process bagi hasil
            if ($transaksi->status === 'lunas') {
                $this->processBagiHasil($penyewaan);
                $penyewaan->update(['status' => 'dikonfirmasi']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $transaksi->status === 'lunas' 
                    ? 'Pembayaran berhasil dikonfirmasi' 
                    : 'Pembayaran berhasil dibuat, menunggu verifikasi',
                'data' => [
                    'transaksi' => $transaksi,
                    'penyewaan' => $penyewaan->load('transaksi')
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify payment (Admin only)
     * POST /api/admin/payments/{id}/verify
     */
    public function verifyPayment(Request $request, $id)
    {
        $transaksi = Transaksi::with('penyewaan')->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if ($transaksi->status === 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diverifikasi'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:lunas,gagal',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $transaksi->update([
                'status' => $request->status,
                'catatan' => $request->catatan
            ]);

            if ($request->status === 'lunas') {
                // Process bagi hasil
                $this->processBagiHasil($transaksi->penyewaan);
                
                // Update penyewaan status
                $transaksi->penyewaan->update(['status' => 'dikonfirmasi']);
                
                // Update motor status to disewa
                $transaksi->penyewaan->motor->update(['status' => 'disewa']);
            } else {
                // Payment failed, cancel booking
                $transaksi->penyewaan->update(['status' => 'dibatalkan']);
                $transaksi->penyewaan->motor->update(['status' => 'tersedia']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->status === 'lunas' 
                    ? 'Pembayaran berhasil diverifikasi' 
                    : 'Pembayaran ditolak',
                'data' => $transaksi->load('penyewaan')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment detail
     * GET /api/payments/{id}
     */
    public function show(Request $request, $id)
    {
        $transaksi = Transaksi::with(['penyewaan.motor', 'penyewaan.penyewa'])->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        // Check authorization
        if ($transaksi->penyewaan->penyewa_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ], 200);
    }

    /**
     * Get payment history
     * GET /api/payments
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['penyewaan.motor', 'penyewaan.penyewa']);

        // Filter by user role
        if ($request->user()->role === 'penyewa') {
            $query->whereHas('penyewaan', function($q) use ($request) {
                $q->where('penyewa_id', $request->user()->id);
            });
        } elseif ($request->user()->role === 'pemilik') {
            $query->whereHas('penyewaan.motor', function($q) use ($request) {
                $q->where('pemilik_id', $request->user()->id);
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        // Filter by date range
        if ($request->has('tanggal_mulai') && $request->has('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transaksis
        ], 200);
    }

    /**
     * Get pending payments (Admin only)
     * GET /api/admin/payments/pending
     */
    public function pendingPayments()
    {
        $pendingPayments = Transaksi::with(['penyewaan.motor', 'penyewaan.penyewa'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $pendingPayments
        ], 200);
    }

    /**
     * Cancel payment
     * POST /api/payments/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        $transaksi = Transaksi::with('penyewaan')->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        // Check authorization
        if ($transaksi->penyewaan->penyewa_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($transaksi->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this payment'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $transaksi->update(['status' => 'gagal']);
            $transaksi->penyewaan->update(['status' => 'dibatalkan']);
            $transaksi->penyewaan->motor->update(['status' => 'tersedia']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibatalkan'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment statistics
     * GET /api/payments/statistics
     */
    public function statistics(Request $request)
    {
        $query = Transaksi::query();

        // Filter by user role
        if ($request->user()->role === 'penyewa') {
            $query->whereHas('penyewaan', function($q) use ($request) {
                $q->where('penyewa_id', $request->user()->id);
            });
        } elseif ($request->user()->role === 'pemilik') {
            $query->whereHas('penyewaan.motor', function($q) use ($request) {
                $q->where('pemilik_id', $request->user()->id);
            });
        }

        $stats = [
            'total_transaksi' => $query->count(),
            'total_lunas' => (clone $query)->where('status', 'lunas')->count(),
            'total_pending' => (clone $query)->where('status', 'pending')->count(),
            'total_gagal' => (clone $query)->where('status', 'gagal')->count(),
            'total_amount' => (clone $query)->where('status', 'lunas')->sum('jumlah'),
            'metode_pembayaran' => (clone $query)->where('status', 'lunas')
                ->select('metode_pembayaran', DB::raw('COUNT(*) as count'), DB::raw('SUM(jumlah) as total'))
                ->groupBy('metode_pembayaran')
                ->get(),
            'monthly_revenue' => (clone $query)->where('status', 'lunas')
                ->whereYear('tanggal', now()->year)
                ->select(DB::raw('MONTH(tanggal) as month'), DB::raw('SUM(jumlah) as total'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ], 200);
    }

    /**
     * Request refund (Penyewa only)
     * POST /api/payments/{id}/refund
     */
    public function requestRefund(Request $request, $id)
    {
        $transaksi = Transaksi::with('penyewaan')->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        // Check authorization
        if ($transaksi->penyewaan->penyewa_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($transaksi->status !== 'lunas') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot refund unpaid transaction'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'alasan' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transaksi->update([
                'refund_status' => 'requested',
                'refund_reason' => $request->alasan,
                'refund_requested_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan refund berhasil diajukan',
                'data' => $transaksi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process refund (Admin only)
     * POST /api/admin/payments/{id}/refund/process
     */
    public function processRefund(Request $request, $id)
    {
        $transaksi = Transaksi::with('penyewaan')->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->action === 'approve') {
                $transaksi->update([
                    'refund_status' => 'approved',
                    'refund_processed_at' => now(),
                    'refund_note' => $request->catatan,
                    'status' => 'refunded'
                ]);

                // Reverse bagi hasil if exists
                $bagiHasil = BagiHasil::where('penyewaan_id', $transaksi->penyewaan_id)->first();
                if ($bagiHasil) {
                    $bagiHasil->update(['settled_at' => 'reversed']);
                }

                $message = 'Refund berhasil disetujui';
            } else {
                $transaksi->update([
                    'refund_status' => 'rejected',
                    'refund_processed_at' => now(),
                    'refund_note' => $request->catatan
                ]);

                $message = 'Refund ditolak';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $transaksi
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses refund',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private: Process bagi hasil
     */
    private function processBagiHasil($penyewaan)
    {
        // Calculate bagi hasil (70% pemilik, 30% admin)
        $bagiHasilPemilik = $penyewaan->harga * 0.7;
        $bagiHasilAdmin = $penyewaan->harga * 0.3;

        BagiHasil::updateOrCreate(
            ['penyewaan_id' => $penyewaan->id],
            [
                'bagi_hasil_pemilik' => $bagiHasilPemilik,
                'bagi_hasil_admin' => $bagiHasilAdmin,
                'settled_at' => 'pending',
                'tanggal' => now()
            ]
        );
    }
}

class AdminController extends Controller
{
    /**
     * Verify motor (Admin only)
     * PATCH /api/admin/motors/{id}/verify
     */
    public function verifyMotor(Request $request, $id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor not found'
            ], 404);
        }

        $motor->update(['status' => 'tersedia']);

        return response()->json([
            'success' => true,
            'message' => 'Motor berhasil diverifikasi',
            'data' => $motor
        ], 200);
    }

    /**
     * Confirm booking (Admin only)
     * PATCH /api/admin/bookings/{id}/confirm
     */
    public function confirmBooking(Request $request, $id)
    {
        $penyewaan = Penyewaan::find($id);

        if (!$penyewaan) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $penyewaan->update(['status' => 'disewa']);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dikonfirmasi',
            'data' => $penyewaan
        ], 200);
    }

    /**
     * Get revenue report (Admin only)
     * GET /api/admin/reports/revenue
     */
    public function revenueReport(Request $request)
    {
        $totalPenyewaan = Penyewaan::count();
        $totalRevenue = Penyewaan::sum('harga');
        $totalBagiHasilAdmin = BagiHasil::sum('bagi_hasil_admin');
        $totalBagiHasilPemilik = BagiHasil::sum('bagi_hasil_pemilik');

        $monthlyData = Penyewaan::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(harga) as revenue')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $topMotors = Motor::withCount('penyewaans')
            ->orderBy('penyewaans_count', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_penyewaan' => $totalPenyewaan,
                    'total_revenue' => $totalRevenue,
                    'total_bagi_hasil_admin' => $totalBagiHasilAdmin,
                    'total_bagi_hasil_pemilik' => $totalBagiHasilPemilik,
                ],
                'monthly_data' => $monthlyData,
                'top_motors' => $topMotors
            ]
        ], 200);
    }

    /**
     * Get all users (Admin only)
     * GET /api/admin/users
     */
    public function users(Request $request)
    {
        $users = User::when($request->role, function($query) use ($request) {
            return $query->where('role', $request->role);
        })->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    /**
     * Get all bookings (Admin only)
     * GET /api/admin/bookings
     */
    public function bookings(Request $request)
    {
        $bookings = Penyewaan::with(['penyewa', 'motor', 'transaksi'])
            ->when($request->status, function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ], 200);
    }