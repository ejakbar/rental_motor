<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Pengguna;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // 6 motor unggulan yang approved & available
        $featuredMotors = Motor::with('pemilik')
            ->approved()
            ->active()
            ->where('status', 'tersedia')
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'total_motor' => Motor::approved()->count(),
            'total_pemilik' => Pengguna::where('role', 'pemilik')->count(),
            'total_penyewa' => Pengguna::where('role', 'penyewa')->count(),
            'total_transaksi' => Penyewaan::where('status_penyewaan', 'selesai')->count(),
        ];

        return view('public.index', compact('featuredMotors', 'stats'));
    }

    public function katalog(Request $request)
    {
        $query = Motor::with('pemilik')->approved()->active();

        // 1. Filter Pencarian Berbasis Kalender Sesuai PRD
        $startDate = $request->filled('tanggal_mulai') ? Carbon::parse($request->tanggal_mulai) : null;
        $endDate = $request->filled('tanggal_selesai') ? Carbon::parse($request->tanggal_selesai) : null;

        if ($startDate && $endDate) {
            // Dapatkan id motor yang bertabrakan pada rentang tanggal tersebut
            $bookedMotorIds = Penyewaan::whereNotIn('status_penyewaan', ['batal', 'selesai'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->where('tanggal_mulai', '<=', $endDate)
                      ->where('tanggal_selesai', '>=', $startDate);
                })
                ->pluck('motor_id')
                ->toArray();

            // Saring hanya motor yang tidak sedang di-booking
            $query->whereNotIn('id', $bookedMotorIds);
        } else {
            // Default hanya tampilkan yang statusnya tersedia
            if (!$request->has('include_all')) {
                $query->where('status', 'tersedia');
            }
        }

        // 2. Filter Merk
        if ($request->filled('merk')) {
            $query->where('merk', $request->merk);
        }

        // 3. Filter CC
        if ($request->filled('tipe_cc')) {
            $query->where('tipe_cc', $request->tipe_cc);
        }

        // 4. Filter Kata Kunci
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('merk', 'like', "%{$keyword}%")
                  ->orWhere('tipe_motor', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        // 5. Filter Harga Maksimum
        if ($request->filled('max_harga')) {
            $query->where('tarif_harian', '<=', $request->max_harga);
        }

        // 6. Pengurutan (Sorting)
        if ($request->sort === 'harga_asc') {
            $query->orderBy('tarif_harian', 'asc');
        } elseif ($request->sort === 'harga_desc') {
            $query->orderBy('tarif_harian', 'desc');
        } elseif ($request->sort === 'tahun_desc') {
            $query->orderBy('tahun', 'desc');
        } else {
            $query->latest();
        }

        $motors = $query->paginate(9)->withQueryString();

        // Data filter untuk dropdown
        $allMerks = Motor::approved()->distinct()->pluck('merk');
        $allCcs = Motor::approved()->distinct()->pluck('tipe_cc')->sort();

        return view('public.katalog', compact('motors', 'allMerks', 'allCcs', 'startDate', 'endDate'));
    }

    public function detailMotor(Request $request, $id)
    {
        $motor = Motor::with(['pemilik', 'tarifRental'])->findOrFail($id);

        // Rekomendasi motor lain
        $relatedMotors = Motor::where('id', '!=', $motor->id)
            ->where('status_approval', 'approved')
            ->where('status', 'tersedia')
            ->take(3)
            ->get();

        // Tanggal sewa dari query param (jika penyewa sudah memilih tanggal di katalog)
        $tanggalMulai = $request->query('tanggal_mulai', Carbon::tomorrow()->format('Y-m-d\TH:00'));
        $tanggalSelesai = $request->query('tanggal_selesai', Carbon::tomorrow()->addDays(2)->format('Y-m-d\TH:00'));

        return view('public.detail', compact('motor', 'relatedMotors', 'tanggalMulai', 'tanggalSelesai'));
    }
}

