<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Marketplace Rental Motor
|--------------------------------------------------------------------------
*/

// Public & Guest Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/katalog', [PublicController::class, 'katalog'])->name('katalog');
Route::get('/motor/{id}', [PublicController::class, 'detailMotor'])->name('motor.detail');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // Fallback GET logout

// Quick Demo Switcher (Memudahkan Pengujian PRD Antar Role)
Route::get('/demo-login/{type}', [AuthController::class, 'demoLogin'])->name('demo.login');

// Unified Redirect Dashboard
Route::get('/dashboard', function () {
    if (!Auth::check()) return redirect()->route('login');
    $user = Auth::user();
    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isPemilik()) return redirect()->route('pemilik.dashboard');
    return redirect()->route('katalog');
})->name('dashboard');

// ==========================================
// RENTER / PENYEWA ROUTES
// ==========================================
Route::middleware(['auth', 'role:penyewa,admin'])->group(function () {
    Route::get('/sewa/{motorId}', [PenyewaController::class, 'checkoutForm'])->name('penyewa.checkout');
    Route::post('/sewa/proses', [PenyewaController::class, 'processCheckout'])->name('penyewa.checkout.process');
    Route::get('/penyewa/pembayaran/{id}', [PenyewaController::class, 'paymentPage'])->name('penyewa.pembayaran');
    Route::post('/penyewa/pembayaran/{id}/bayar', [PenyewaController::class, 'simulatePay'])->name('penyewa.pembayaran.bayar');
    Route::get('/penyewa/tiket/{id}', [PenyewaController::class, 'showTicket'])->name('penyewa.tiket');
    Route::get('/penyewa/riwayat', [PenyewaController::class, 'myBookings'])->name('penyewa.riwayat');
    Route::get('/penyewa/kyc', [PenyewaController::class, 'kycForm'])->name('penyewa.kyc');
    Route::post('/penyewa/kyc', [PenyewaController::class, 'kycSubmit'])->name('penyewa.kyc.submit');
});

// ==========================================
// OWNER / PEMILIK ROUTES
// ==========================================
Route::middleware(['auth', 'role:pemilik,admin'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    
    // CRUD Motor Sesuai PRD
    Route::get('/motor', [PemilikController::class, 'motorIndex'])->name('motor.index');
    Route::get('/motor/create', [PemilikController::class, 'motorCreate'])->name('motor.create');
    Route::post('/motor', [PemilikController::class, 'motorStore'])->name('motor.store');
    Route::get('/motor/{id}/edit', [PemilikController::class, 'motorEdit'])->name('motor.edit');
    Route::put('/motor/{id}', [PemilikController::class, 'motorUpdate'])->name('motor.update');
    Route::delete('/motor/{id}', [PemilikController::class, 'motorDestroy'])->name('motor.destroy');

    // Manajemen Pesanan & Serah Terima
    Route::get('/pesanan', [PemilikController::class, 'pesananIndex'])->name('pesanan.index');
    Route::post('/pesanan/{id}/serah-terima', [PemilikController::class, 'serahTerima'])->name('pesanan.serahTerima');
    Route::post('/pesanan/{id}/selesai', [PemilikController::class, 'konfirmasiSelesai'])->name('pesanan.selesai');

    // Laporan Pendapatan & Withdrawal
    Route::get('/pendapatan', [PemilikController::class, 'pendapatan'])->name('pendapatan');
    Route::post('/tarik-dana', [PemilikController::class, 'tarikDana'])->name('tarikDana');
    Route::post('/rekening', [PemilikController::class, 'updateRekening'])->name('rekening.update');
});

// ==========================================
// ADMIN DASHBOARD ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Approval System Motor (Kurasi Kendaraan Sesuai PRD)
    Route::get('/motor', [AdminController::class, 'motorList'])->name('motor.index');
    Route::get('/motor/{id}', [AdminController::class, 'motorDetail'])->name('motor.detail');
    Route::post('/motor/{id}/approve', [AdminController::class, 'approveMotor'])->name('motor.approve');
    Route::post('/motor/{id}/reject', [AdminController::class, 'rejectMotor'])->name('motor.reject');
    Route::post('/motor/{id}/toggle-status', [AdminController::class, 'toggleMotorStatus'])->name('motor.toggle');

    // Verifikasi KYC Penyewa Sesuai PRD
    Route::get('/kyc', [AdminController::class, 'kycList'])->name('kyc.index');
    Route::post('/kyc/{id}/approve', [AdminController::class, 'approveKyc'])->name('kyc.approve');
    Route::post('/kyc/{id}/reject', [AdminController::class, 'rejectKyc'])->name('kyc.reject');

    // Pusat Resolusi & Transaksi Sesuai PRD
    Route::get('/transaksi', [AdminController::class, 'transaksiList'])->name('transaksi.index');
    Route::post('/transaksi/{id}/resolusi', [AdminController::class, 'simpanResolusi'])->name('transaksi.resolusi');

    // Penarikan Dana Pemilik (Withdrawals)
    Route::get('/penarikan-dana', [AdminController::class, 'withdrawalsList'])->name('withdrawals.index');
    Route::post('/penarikan-dana/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/penarikan-dana/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

    // Manajemen Pengguna
    Route::get('/pengguna', [AdminController::class, 'usersList'])->name('users.index');
});

// Legacy backward-compatible redirects
Route::get('/motor', fn() => redirect('/admin/motor'));
Route::get('/pengguna', fn() => redirect('/admin/pengguna'));
Route::get('/penyewaan', fn() => redirect('/admin/transaksi'));
Route::get('/pembayaran', fn() => redirect('/admin/transaksi'));
