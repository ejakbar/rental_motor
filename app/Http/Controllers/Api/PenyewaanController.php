<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\BagiHasil;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenyewaanController extends Controller
{
    /**
     * Create new booking (Penyewa only)
     * POST /api/renter/bookings
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check motor availability
        $motor = Motor::with('tarifRental')->find($request->motor_id);

        if ($motor->status !== 'tersedia') {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak tersedia'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate duration
            $tanggalMulai = \Carbon\Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = \Carbon\Carbon::parse($request->tanggal_selesai);
            $durasi = $tanggalSelesai->diffInDays($tanggalMulai) + 1;

            // Calculate price
            $harga = 0;
            switch($request->tipe_durasi) {
                case 'harian':
                    $harga = $motor->tarifRental->tarif_harian * $durasi;
                    break;
                case 'mingguan':
                    $harga = $motor->tarifRental->tarif_mingguan * ceil($durasi / 7);
                    break;
                case 'bulanan':
                    $harga = $motor->tarifRental->tarif_bulanan * ceil($durasi / 30);
                    break;
            }

            // Create penyewaan
            $penyewaan = Penyewaan::create([
                'penyewa_id' => $request->user()->id,
                'motor_id' => $request->motor_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi' => $durasi,
                'tipe_durasi' => $request->tipe_durasi,
                'harga' => $harga,
                'status' => 'pending',
                'tanggal' => now()
            ]);

            // Update motor status
            $motor->update(['status' => 'disewa']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $penyewaan->load('motor.tarifRental')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment (Penyewa only)
     * POST /api/renter/payments
     */
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:cash,transfer,ewallet',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $penyewaan = Penyewaan::where('penyewa_id', $request->user()->id)
            ->find($request->penyewaan_id);

        if (!$penyewaan) {
            return response()->json([
                'success' => false,
                'message' => 'Penyewaan not found or unauthorized'
            ], 404);
        }

        if ($penyewaan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diproses'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create transaction
            $transaksi = Pembayaran::create([
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'lunas',
                'tanggal' => now()
            ]);

            // Update penyewaan status
            $penyewaan->update(['status' => 'dikonfirmasi']);

            // Calculate bagi hasil (70% pemilik, 30% admin)
            $bagiHasilPemilik = $penyewaan->harga * 0.7;
            $bagiHasilAdmin = $penyewaan->harga * 0.3;

            Bagi_hasil::create([
                'penyewaan_id' => $penyewaan->id,
                'bagi_hasil_pemilik' => $bagiHasilPemilik,
                'bagi_hasil_admin' => $bagiHasilAdmin,
                'settled_at' => 'pending',
                'tanggal' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil',
                'data' => [
                    'penyewaan' => $penyewaan,
                    'transaksi' => $transaksi
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking history (Penyewa only)
     * GET /api/renter/bookings/history
     */
    public function history(Request $request)
    {
        $penyewaans = Penyewaan::with(['motor.tarifRental', 'transaksi'])
            ->where('penyewa_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $penyewaans
        ], 200);
    }

    /**
     * Cancel booking (Penyewa only)
     * POST /api/renter/bookings/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        $penyewaan = Penyewaan::where('penyewa_id', $request->user()->id)->find($id);

        if (!$penyewaan) {
            return response()->json([
                'success' => false,
                'message' => 'Penyewaan not found or unauthorized'
            ], 404);
        }

        if ($penyewaan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this booking'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $penyewaan->update(['status' => 'dibatalkan']);
            $penyewaan->motor->update(['status' => 'tersedia']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
