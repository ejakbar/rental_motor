<?php

namespace App\Http\Controllers;

use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\PenarikanDana;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard Ringkasan Admin
     */
    public function dashboard()
    {
        $totalMotor = Motor::count();
        $pendingMotorsCount = Motor::where('status_approval', 'pending')->count();
        $pendingKycCount = Pengguna::where('role', 'penyewa')->where('status_kyc', 'pending')->count();
        $totalPenyewaan = Penyewaan::count();
        $activeRentalsCount = Penyewaan::where('status_penyewaan', 'on_rent')->count();

        // Pendapatan Platform dari Biaya Layanan & Komisi 10%
        $totalKomisiBagiHasil = Bagi_hasil::sum('komisi_admin');
        $totalBiayaLayanan = Penyewaan::whereIn('status_penyewaan', ['disetujui', 'on_rent', 'selesai'])->sum('biaya_layanan');
        $totalRevenuePlatform = $totalKomisiBagiHasil + $totalBiayaLayanan;

        // Antrean Butuh Tindakan Segera
        $pendingMotors = Motor::with('pemilik')->where('status_approval', 'pending')->latest()->take(5)->get();
        $pendingKycs = Pengguna::where('role', 'penyewa')->where('status_kyc', 'pending')->latest()->take(5)->get();
        $pendingWithdrawals = PenarikanDana::with('pemilik')->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMotor',
            'pendingMotorsCount',
            'pendingKycCount',
            'totalPenyewaan',
            'activeRentalsCount',
            'totalRevenuePlatform',
            'pendingMotors',
            'pendingKycs',
            'pendingWithdrawals'
        ));
    }

    /**
     * Approval System (Verifikasi Motor) Sesuai PRD
     */
    public function motorList(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Motor::with('pemilik');

        if ($status === 'pending') {
            $query->where('status_approval', 'pending');
        } elseif ($status === 'approved') {
            $query->where('status_approval', 'approved');
        } elseif ($status === 'rejected') {
            $query->where('status_approval', 'rejected');
        }

        $motors = $query->latest()->paginate(15)->withQueryString();

        return view('admin.motor.index', compact('motors', 'status'));
    }

    /**
     * Detail Motor untuk Tinjauan Kurasi
     */
    public function motorDetail($id)
    {
        $motor = Motor::with(['pemilik', 'tarifRental'])->findOrFail($id);
        return view('admin.motor.detail', compact('motor'));
    }

    /**
     * Setujui Motor (Approve Sesuai PRD -> Publikasi ke Katalog)
     */
    public function approveMotor($id)
    {
        $motor = Motor::findOrFail($id);
        $motor->update([
            'status_approval' => 'approved',
            'catatan_approval' => null,
            'status' => 'tersedia',
            'is_active' => true,
        ]);

        return back()->with('success', "Motor {$motor->merk} {$motor->tipe_motor} ({$motor->no_plat}) berhasil disetujui dan telah terbit di katalog publik!");
    }

    /**
     * Tolak Motor (Reject Sesuai PRD dengan Catatan Alasan)
     */
    public function rejectMotor(Request $request, $id)
    {
        $request->validate([
            'catatan_approval' => 'required|string|max:500',
        ]);

        $motor = Motor::findOrFail($id);
        $motor->update([
            'status_approval' => 'rejected',
            'catatan_approval' => $request->catatan_approval,
            'is_active' => false,
        ]);

        return back()->with('success', "Motor telah ditolak dengan catatan: {$request->catatan_approval}");
    }

    /**
     * Manajemen Ketersediaan: Nonaktifkan / Aktifkan Motor Secara Paksa oleh Admin Sesuai PRD
     */
    public function toggleMotorStatus($id)
    {
        $motor = Motor::findOrFail($id);
        $motor->is_active = !$motor->is_active;
        if (!$motor->is_active) {
            $motor->status = 'nonaktif';
        } else {
            $motor->status = 'tersedia';
        }
        $motor->save();

        $statusText = $motor->is_active ? 'diaktifkan kembali' : 'dinonaktifkan secara paksa';
        return back()->with('success', "Motor {$motor->no_plat} berhasil {$statusText}.");
    }

    /**
     * Verifikasi KYC Penyewa Sesuai PRD
     */
    public function kycList(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = Pengguna::where('role', 'penyewa');

        if ($status !== 'all') {
            $query->where('status_kyc', $status);
        }

        $renters = $query->latest()->paginate(15)->withQueryString();

        return view('admin.kyc.index', compact('renters', 'status'));
    }

    /**
     * Setujui KYC Penyewa
     */
    public function approveKyc($id)
    {
        $user = Pengguna::where('role', 'penyewa')->findOrFail($id);
        $user->update([
            'status_kyc' => 'verified',
            'catatan_kyc' => 'Dokumen identitas telah diverifikasi oleh tim Admin platform.',
        ]);

        return back()->with('success', "KYC atas nama {$user->nama} berhasil disetujui. Penyewa kini dapat melakukan booking motor.");
    }

    /**
     * Tolak KYC Penyewa dengan Catatan
     */
    public function rejectKyc(Request $request, $id)
    {
        $request->validate([
            'catatan_kyc' => 'required|string|max:500',
        ]);

        $user = Pengguna::where('role', 'penyewa')->findOrFail($id);
        $user->update([
            'status_kyc' => 'rejected',
            'catatan_kyc' => $request->catatan_kyc,
        ]);

        return back()->with('success', "KYC atas nama {$user->nama} telah ditolak dengan catatan: {$request->catatan_kyc}");
    }

    /**
     * Pusat Resolusi & Transaksi Sesuai PRD
     */
    public function transaksiList(Request $request)
    {
        $query = Penyewaan::with(['penyewa', 'motor.pemilik', 'pembayaran', 'bagiHasil']);

        if ($request->filled('status')) {
            $query->where('status_penyewaan', $request->status);
        }

        $transaksiList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.transaksi.index', compact('transaksiList'));
    }

    /**
     * Resolusi Keterlambatan / Perselisihan (Dispute)
     */
    public function simpanResolusi(Request $request, $id)
    {
        $penyewaan = Penyewaan::findOrFail($id);

        $request->validate([
            'status_penyewaan' => 'required|in:disetujui,on_rent,selesai,batal,dispute',
            'denda_keterlambatan' => 'nullable|numeric|min:0',
            'catatan_resolusi' => 'required|string',
        ]);

        $penyewaan->update([
            'status_penyewaan' => $request->status_penyewaan,
            'denda_keterlambatan' => $request->denda_keterlambatan ?? 0,
            'catatan_resolusi' => $request->catatan_resolusi,
        ]);

        return back()->with('success', 'Catatan resolusi perselisihan berhasil disimpan.');
    }

    /**
     * Manajemen Penarikan Dana Pemilik (Withdrawals)
     */
    public function withdrawalsList()
    {
        $withdrawals = PenarikanDana::with('pemilik')->latest()->paginate(15);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Setujui Penarikan Dana
     */
    public function approveWithdrawal(Request $request, $id)
    {
        $wd = PenarikanDana::findOrFail($id);
        $wd->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan ?? 'Dana berhasil ditransfer ke rekening pemilik.',
            'tanggal_diproses' => Carbon::now(),
        ]);

        return back()->with('success', "Penarikan dana {$wd->kode_penarikan} sejumlah Rp " . number_format($wd->jumlah, 0, ',', '.') . " telah disetujui.");
    }

    /**
     * Tolak Penarikan Dana (Kembalikan Saldo ke Pemilik)
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $wd = PenarikanDana::with('pemilik')->findOrFail($id);

        DB::transaction(function () use ($wd, $request) {
            $wd->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'tanggal_diproses' => Carbon::now(),
            ]);

            // Kembalikan saldo ke pemilik
            $wd->pemilik->increment('saldo', $wd->jumlah);
        });

        return back()->with('success', "Penarikan dana {$wd->kode_penarikan} ditolak dan saldo telah dikembalikan ke dompet pemilik.");
    }

    /**
     * Daftar Semua Pengguna Platform
     */
    public function usersList(Request $request)
    {
        $query = Pengguna::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }
}

