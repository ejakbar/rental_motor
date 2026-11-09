<?php

namespace Tests\Feature;

use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Pengguna;
use App\Models\PenarikanDana;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrdRentalMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    /**
     * Test 1: Landing Page & Public Catalog Access
     */
    public function test_public_pages_load_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('RentRide');

        $catalog = $this->get('/katalog');
        $catalog->assertStatus(200);
        $catalog->assertSee('Katalog Sepeda Motor');
    }

    /**
     * Test 2: Calendar-based search excludes booked motors
     */
    public function test_calendar_search_filters_overlapping_bookings()
    {
        // Cari pada rentang waktu motor3 sedang disewa
        $startDate = Carbon::now()->addDay()->format('Y-m-d\TH:i');
        $endDate = Carbon::now()->addDays(2)->format('Y-m-d\TH:i');

        $response = $this->get("/katalog?tanggal_mulai={$startDate}&tanggal_selesai={$endDate}");
        $response->assertStatus(200);
    }

    /**
     * Test 3: Owner creates motor -> status becomes Pending Approval
     */
    public function test_owner_can_register_motor_with_pending_status()
    {
        $pemilik = Pengguna::where('role', 'pemilik')->first();

        $response = $this->actingAs($pemilik)->post('/pemilik/motor', [
            'merk' => 'Yamaha',
            'tipe_motor' => 'Fazzio Hybrid 125',
            'tahun' => 2024,
            'tipe_cc' => '125',
            'no_plat' => 'B 7788 TST',
            'tarif_harian' => 95000,
        ]);

        $response->assertRedirect('/pemilik/motor');

        $motor = Motor::where('no_plat', 'B 7788 TST')->first();
        $this->assertNotNull($motor);
        $this->assertEquals('pending', $motor->status_approval);
    }

    /**
     * Test 4: Admin can Approve or Reject motor
     */
    public function test_admin_can_approve_pending_motor()
    {
        $admin = Pengguna::where('role', 'admin')->first();
        $pendingMotor = Motor::where('status_approval', 'pending')->first();

        $response = $this->actingAs($admin)->post("/admin/motor/{$pendingMotor->id}/approve");
        $response->assertSessionHas('success');

        $pendingMotor->refresh();
        $this->assertEquals('approved', $pendingMotor->status_approval);
    }

    /**
     * Test 5: Renter KYC check & checkout flow
     */
    public function test_renter_checkout_and_ticket_flow()
    {
        $penyewa = Pengguna::where('role', 'penyewa')->where('status_kyc', 'verified')->first();
        $motor = Motor::approved()->where('status', 'tersedia')->first();

        $mulai = Carbon::now()->addDays(5)->setTime(10, 0, 0);
        $selesai = Carbon::now()->addDays(7)->setTime(10, 0, 0);

        // Checkout process
        $response = $this->actingAs($penyewa)->post('/sewa/proses', [
            'motor_id' => $motor->id,
            'tanggal_mulai' => $mulai->format('Y-m-d H:i:s'),
            'tanggal_selesai' => $selesai->format('Y-m-d H:i:s'),
            'metode_pembayaran' => 'va_bca',
        ]);

        $penyewaan = Penyewaan::where('penyewa_id', $penyewa->id)->latest('id')->first();
        $this->assertNotNull($penyewaan);
        $this->assertEquals('menunggu_pembayaran', $penyewaan->status_penyewaan);

        // Simulate Pay
        $payResponse = $this->actingAs($penyewa)->post("/penyewa/pembayaran/{$penyewaan->id}/bayar");
        $payResponse->assertRedirect("/penyewa/tiket/{$penyewaan->id}");

        $penyewaan->refresh();
        $this->assertEquals('disetujui', $penyewaan->status_penyewaan);
        $this->assertEquals('lunas', $penyewaan->pembayaran->status_pembayaran);
    }

    /**
     * Test 6: Owner completes rental and revenue sharing (Bagi Hasil) is credited
     */
    public function test_owner_completes_rental_and_receives_revenue_share()
    {
        $pemilik = Pengguna::where('role', 'pemilik')->first();
        
        // Buat order disetujui
        $motor = Motor::where('pemilik_id', $pemilik->id)->first();
        $penyewa = Pengguna::where('role', 'penyewa')->first();

        $penyewaan = Penyewaan::create([
            'kode_booking' => 'BK-TEST-' . rand(100, 999),
            'penyewa_id' => $penyewa->id,
            'motor_id' => $motor->id,
            'tanggal_mulai' => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addHours(2),
            'durasi_hari' => 1,
            'tipe_durasi' => 'harian',
            'tarif_per_hari' => 100000,
            'subtotal' => 100000,
            'biaya_layanan' => 15000,
            'total_harga' => 115000,
            'status_penyewaan' => 'on_rent',
        ]);

        $saldoAwal = $pemilik->saldo;

        // Pemilik konfirmasi motor kembali
        $response = $this->actingAs($pemilik)->post("/pemilik/pesanan/{$penyewaan->id}/selesai");
        $response->assertSessionHas('success');

        $penyewaan->refresh();
        $this->assertEquals('selesai', $penyewaan->status_penyewaan);

        $bagiHasil = Bagi_hasil::where('penyewaan_id', $penyewaan->id)->first();
        $this->assertNotNull($bagiHasil);
        $this->assertEquals(90000.00, $bagiHasil->bagi_hasil_pemilik); // 90%
        $this->assertEquals(10000.00, $bagiHasil->komisi_admin); // 10%

        $pemilik->refresh();
        $this->assertEquals($saldoAwal + 90000.00, $pemilik->saldo);
    }

    /**
     * Test 7: Demo Login Switcher works smoothly
     */
    public function test_demo_login_switcher()
    {
        $responseAdmin = $this->get('/demo-login/admin');
        $responseAdmin->assertRedirect('/admin/dashboard');

        $responsePemilik = $this->get('/demo-login/pemilik');
        $responsePemilik->assertRedirect('/pemilik/dashboard');

        $responsePenyewa = $this->get('/demo-login/penyewa');
        $responsePenyewa->assertRedirect('/katalog');
    }
}
