<?php

namespace App\Http\Controllers;

use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Pengguna;
use App\Models\PenarikanDana;
use App\Models\Penyewaan;
use App\Models\Tarif_rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemilikController extends Controller
{
    /**
     * Dashboard Pemilik
     */
    public function dashboard()
    {
        $pemilik = Auth::user();

        $motors = Motor::where('pemilik_id', $pemilik->id)->get();
        $totalMotor = $motors->count();
        $motorAvailable = $motors->where('status_approval', 'approved')->where('status', 'tersedia')->count();
        $motorOnRent = $motors->where('status', 'disewa')->count();
        $motorPending = $motors->where('status_approval', 'pending')->count();

        // Pesanan aktif saat ini
        $activeRentals = Penyewaan::with(['motor', 'penyewa'])
            ->whereHas('motor', function ($q) use ($pemilik) {
                $q->where('pemilik_id', $pemilik->id);
            })
            ->whereIn('status_penyewaan', ['disetujui', 'on_rent'])
            ->latest()
            ->take(5)
            ->get();

        // Ringkasan Pendapatan
        $totalPendapatan = Bagi_hasil::where('pemilik_id', $pemilik->id)->sum('bagi_hasil_pemilik');
        $saldoTersedia = $pemilik->saldo;

        return view('pemilik.dashboard', compact(
            'pemilik',
            'totalMotor',
            'motorAvailable',
            'motorOnRent',
            'motorPending',
            'activeRentals',
            'totalPendapatan',
            'saldoTersedia'
        ));
    }

    /**
     * Daftar Motor Milik Pemilik
     */
    public function motorIndex()
    {
        $pemilikId = Auth::id();
        $motors = Motor::where('pemilik_id', $pemilikId)
            ->with(['penyewaans' => function($q) {
                $q->whereIn('status_penyewaan', ['disetujui', 'on_rent'])->latest();
            }])
            ->latest()
            ->paginate(10);

        return view('pemilik.motor.index', compact('motors'));
    }

    /**
     * Form Tambah Motor
     */
    public function motorCreate()
    {
        return view('pemilik.motor.create');
    }

    /**
     * Simpan Motor Baru (Status Pending Approval Admin sesuai PRD)
     */
    public function motorStore(Request $request)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'tahun' => 'required|integer|min:2010|max:' . (date('Y') + 1),
            'tipe_cc' => 'required|string|max:20',
            'no_plat' => 'required|string|max:20',
            'warna' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'tarif_harian' => 'required|numeric|min:10000',
            'tarif_mingguan' => 'nullable|numeric|min:50000',
            'tarif_bulanan' => 'nullable|numeric|min:200000',
            'foto_depan' => 'nullable|image|max:3072',
            'foto_samping' => 'nullable|image|max:3072',
            'foto_belakang' => 'nullable|image|max:3072',
            'foto_stnk' => 'nullable|image|max:3072',
        ]);

        $motorData = [
            'pemilik_id' => Auth::id(),
            'merk' => $validated['merk'],
            'tipe_motor' => $validated['tipe_motor'],
            'tahun' => $validated['tahun'],
            'tipe_cc' => $validated['tipe_cc'],
            'no_plat' => strtoupper($validated['no_plat']),
            'warna' => $validated['warna'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tarif_harian' => $validated['tarif_harian'],
            'tarif_mingguan' => $validated['tarif_mingguan'] ?? null,
            'tarif_bulanan' => $validated['tarif_bulanan'] ?? null,
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'pending', // Menunggu kurasi admin sesuai PRD
            'status' => 'tersedia',
            'is_active' => true,
        ];

        // Upload Foto Depan, Samping, Belakang, STNK Sesuai PRD
        if ($request->hasFile('foto_depan')) {
            $path = $request->file('foto_depan')->store('motors', 'public');
            $motorData['foto_depan'] = $path;
            $motorData['foto_motor'] = $path;
        }
        if ($request->hasFile('foto_samping')) {
            $motorData['foto_samping'] = $request->file('foto_samping')->store('motors', 'public');
        }
        if ($request->hasFile('foto_belakang')) {
            $motorData['foto_belakang'] = $request->file('foto_belakang')->store('motors', 'public');
        }
        if ($request->hasFile('foto_stnk')) {
            $motorData['foto_stnk'] = $request->file('foto_stnk')->store('stnk', 'public');
        }

        $motor = Motor::create($motorData);

        // Tambah ke tarif_rentals
        Tarif_rental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => $motor->tarif_harian,
            'tarif_mingguan' => $motor->tarif_mingguan ?? ($motor->tarif_harian * 6.5),
            'tarif_bulanan' => $motor->tarif_bulanan ?? ($motor->tarif_harian * 25),
        ]);

        return redirect()->route('pemilik.motor.index')
            ->with('success', 'Motor berhasil ditambahkan dan sedang dalam antrean kurasi Admin (Status: Pending Approval).');
    }

    /**
     * Form Edit Motor
     */
    public function motorEdit($id)
    {
        $motor = Motor::where('pemilik_id', Auth::id())->findOrFail($id);
        return view('pemilik.motor.edit', compact('motor'));
    }

    /**
     * Update Data Motor
     */
    public function motorUpdate(Request $request, $id)
    {
        $motor = Motor::where('pemilik_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'merk' => 'required|string|max:50',
            'tipe_motor' => 'required|string|max:100',
            'tahun' => 'required|integer',
            'tipe_cc' => 'required|string|max:20',
            'no_plat' => 'required|string|max:20',
            'warna' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'tarif_harian' => 'required|numeric|min:10000',
            'tarif_mingguan' => 'nullable|numeric',
            'tarif_bulanan' => 'nullable|numeric',
            'foto_depan' => 'nullable|image|max:3072',
            'foto_samping' => 'nullable|image|max:3072',
            'foto_belakang' => 'nullable|image|max:3072',
            'foto_stnk' => 'nullable|image|max:3072',
        ]);

        $motor->merk = $validated['merk'];
        $motor->tipe_motor = $validated['tipe_motor'];
        $motor->tahun = $validated['tahun'];
        $motor->tipe_cc = $validated['tipe_cc'];
        $motor->no_plat = strtoupper($validated['no_plat']);
        $motor->warna = $validated['warna'] ?? null;
        $motor->deskripsi = $validated['deskripsi'] ?? null;
        $motor->tarif_harian = $validated['tarif_harian'];
        $motor->tarif_mingguan = $validated['tarif_mingguan'] ?? null;
        $motor->tarif_bulanan = $validated['tarif_bulanan'] ?? null;

        if ($request->hasFile('foto_depan')) {
            $path = $request->file('foto_depan')->store('motors', 'public');
            $motor->foto_depan = $path;
            $motor->foto_motor = $path;
        }
        if ($request->hasFile('foto_samping')) {
            $motor->foto_samping = $request->file('foto_samping')->store('motors', 'public');
        }
        if ($request->hasFile('foto_belakang')) {
            $motor->foto_belakang = $request->file('foto_belakang')->store('motors', 'public');
        }
        if ($request->hasFile('foto_stnk')) {
            $motor->foto_stnk = $request->file('foto_stnk')->store('stnk', 'public');
        }

        $motor->save();

        return redirect()->route('pemilik.motor.index')
            ->with('success', 'Data motor berhasil diperbarui.');
    }

    /**
     * Hapus Motor
     */
    public function motorDestroy($id)
    {
        $motor = Motor::where('pemilik_id', Auth::id())->findOrFail($id);

        // Jangan hapus jika sedang disewa
        if ($motor->status === 'disewa') {
            return back()->with('error', 'Motor tidak dapat dihapus karena sedang disewa oleh penyewa.');
        }

        $motor->delete();
        return redirect()->route('pemilik.motor.index')->with('success', 'Motor berhasil dihapus.');
    }

    /**
     * Manajemen Pesanan Masuk Pemilik
     */
    public function pesananIndex()
    {
        $pemilikId = Auth::id();

        $pesanans = Penyewaan::with(['motor', 'penyewa', 'pembayaran'])
            ->whereHas('motor', function ($q) use ($pemilikId) {
                $q->where('pemilik_id', $pemilikId);
            })
            ->latest()
            ->paginate(15);

        return view('pemilik.pesanan.index', compact('pesanans'));
    }

    /**
     * Aksi Serah Terima Motor ke Penyewa (Tanggal Pengambilan)
     */
    public function serahTerima(Request $request, $id)
    {
        $pemilikId = Auth::id();
        $penyewaan = Penyewaan::whereHas('motor', function ($q) use ($pemilikId) {
            $q->where('pemilik_id', $pemilikId);
        })->findOrFail($id);

        if (!in_array($penyewaan->status_penyewaan, ['disetujui', 'menunggu_konfirmasi'])) {
            return back()->with('error', 'Status pesanan tidak memungkinkan untuk serah terima.');
        }

        DB::transaction(function () use ($penyewaan, $request) {
            $penyewaan->update([
                'status_penyewaan' => 'on_rent',
                'waktu_diambil' => Carbon::now(),
                'catatan_pemilik' => $request->catatan_pemilik ?? 'Unit telah diserahkan lengkap dengan helm dan STNK.',
            ]);

            // Set status ketersediaan motor menjadi disewa
            $penyewaan->motor->update([
                'status' => 'disewa',
            ]);
        });

        return back()->with('success', '✅ Serah terima motor berhasil dikonfirmasi! Status pesanan kini: Sedang Digunakan (On Rent).');
    }

    /**
     * Aksi Konfirmasi Motor Dikembalikan (Tanggal Pengembalian)
     * Otomatis menghitung bagi hasil dan memasukkan dana ke saldo pemilik sesuai PRD!
     */
    public function konfirmasiSelesai(Request $request, $id)
    {
        $pemilik = Auth::user();
        $penyewaan = Penyewaan::whereHas('motor', function ($q) use ($pemilik) {
            $q->where('pemilik_id', $pemilik->id);
        })->with('motor')->findOrFail($id);

        if ($penyewaan->status_penyewaan !== 'on_rent') {
            return back()->with('error', 'Pesanan harus dalam status on_rent untuk dapat diselesaikan.');
        }

        DB::transaction(function () use ($penyewaan, $pemilik, $request) {
            $now = Carbon::now();

            // Hitung denda keterlambatan jika melebihi batas pengembalian
            $denda = 0;
            if ($now->greaterThan($penyewaan->tanggal_selesai)) {
                $hoursLate = $penyewaan->tanggal_selesai->diffInHours($now);
                if ($hoursLate > 1) {
                    // Denda 10.000 per jam terlambat
                    $denda = $hoursLate * 10000;
                }
            }

            $penyewaan->update([
                'status_penyewaan' => 'selesai',
                'waktu_dikembalikan' => $now,
                'denda_keterlambatan' => $denda,
                'catatan_pemilik' => $request->catatan_pemilik ?? 'Unit motor telah dikembalikan dalam kondisi baik.',
            ]);

            // Set motor kembali tersedia
            $penyewaan->motor->update([
                'status' => 'tersedia',
            ]);

            // Hitung Bagi Hasil: Komisi Platform 10%, Bagian Pemilik 90% (dari subtotal biaya sewa)
            $totalSewa = $penyewaan->subtotal + $denda;
            $persentaseKomisi = 10.00;
            $komisiAdmin = $totalSewa * ($persentaseKomisi / 100);
            $pendapatanPemilik = $totalSewa - $komisiAdmin;

            Bagi_hasil::create([
                'penyewaan_id' => $penyewaan->id,
                'pemilik_id' => $pemilik->id,
                'total_biaya_sewa' => $totalSewa,
                'persentase_komisi' => $persentaseKomisi,
                'komisi_admin' => $komisiAdmin,
                'bagi_hasil_pemilik' => $pendapatanPemilik,
                'status_bagi_hasil' => 'masuk_saldo',
                'tanggal_bagi_hasil' => $now,
            ]);

            // Tambahkan ke saldo dompet pemilik
            $pemilik->increment('saldo', $pendapatanPemilik);
        });

        return back()->with('success', '🎉 Motor berhasil dikonfirmasi kembali! Dana bagi hasil telah otomatis masuk ke saldo dompet Anda.');
    }

    /**
     * Laporan Pendapatan & Dompet Penarikan Dana (Withdrawal)
     */
    public function pendapatan()
    {
        $pemilik = Auth::user();

        $bagiHasils = Bagi_hasil::with(['penyewaan.motor', 'penyewaan.penyewa'])
            ->where('pemilik_id', $pemilik->id)
            ->latest()
            ->paginate(10);

        $penarikanList = PenarikanDana::where('pemilik_id', $pemilik->id)->latest()->get();

        $totalPendapatanKotor = Bagi_hasil::where('pemilik_id', $pemilik->id)->sum('total_biaya_sewa');
        $totalKomisiPlatform = Bagi_hasil::where('pemilik_id', $pemilik->id)->sum('komisi_admin');
        $totalPendapatanBersih = Bagi_hasil::where('pemilik_id', $pemilik->id)->sum('bagi_hasil_pemilik');

        return view('pemilik.pendapatan', compact(
            'pemilik',
            'bagiHasils',
            'penarikanList',
            'totalPendapatanKotor',
            'totalKomisiPlatform',
            'totalPendapatanBersih'
        ));
    }

    /**
     * Pengajuan Tarik Dana (Withdrawal)
     */
    public function tarikDana(Request $request)
    {
        $pemilik = Auth::user();

        if (empty($pemilik->no_rekening) || empty($pemilik->nama_bank)) {
            return back()->with('error', 'Silakan lengkapi informasi rekening bank Anda terlebih dahulu pada tab Pengaturan Rekening.');
        }

        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:50000|max:' . $pemilik->saldo,
        ], [
            'jumlah.max' => 'Jumlah penarikan tidak boleh melebihi saldo dompet Anda saat ini.',
            'jumlah.min' => 'Minimal penarikan dana adalah Rp 50.000.',
        ]);

        DB::transaction(function () use ($pemilik, $validated) {
            $jumlah = $validated['jumlah'];

            // Kurangi saldo
            $pemilik->decrement('saldo', $jumlah);

            $kodePenarikan = 'WD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            PenarikanDana::create([
                'pemilik_id' => $pemilik->id,
                'kode_penarikan' => $kodePenarikan,
                'jumlah' => $jumlah,
                'nama_bank' => $pemilik->nama_bank,
                'no_rekening' => $pemilik->no_rekening,
                'atas_nama_rekening' => $pemilik->atas_nama_rekening ?? $pemilik->nama,
                'status' => 'pending',
                'catatan' => 'Permintaan penarikan dana oleh pemilik',
            ]);
        });

        return back()->with('success', 'Permintaan penarikan dana berhasil diajukan! Admin akan memproses transfer ke rekening Anda.');
    }

    /**
     * Simpan / Perbarui Rekening Bank Pemilik
     */
    public function updateRekening(Request $request)
    {
        $pemilik = Auth::user();

        $validated = $request->validate([
            'nama_bank' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:50',
            'atas_nama_rekening' => 'required|string|max:100',
        ]);

        $pemilik->update($validated);

        return back()->with('success', 'Informasi rekening bank berhasil disimpan.');
    }
}

