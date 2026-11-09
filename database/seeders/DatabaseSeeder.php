<?php

namespace Database\Seeders;

use App\Models\Bagi_hasil;
use App\Models\Motor;
use App\Models\Pembayaran;
use App\Models\Pengguna;
use App\Models\PenarikanDana;
use App\Models\Penyewaan;
use App\Models\Tarif_rental;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Pengguna (Admin, Pemilik, Penyewa)
        $admin = Pengguna::create([
            'nama' => 'Super Admin Platform',
            'email' => 'admin@rental.com',
            'no_telp' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'status_kyc' => 'verified',
        ]);

        $pemilik1 = Pengguna::create([
            'nama' => 'Budi Santoso (Rental Jaya)',
            'email' => 'budi.pemilik@rental.com',
            'no_telp' => '081298765432',
            'role' => 'pemilik',
            'password' => Hash::make('password123'),
            'nama_bank' => 'BCA',
            'no_rekening' => '8830192831',
            'atas_nama_rekening' => 'Budi Santoso',
            'saldo' => 450000.00,
            'status_kyc' => 'verified',
        ]);

        $pemilik2 = Pengguna::create([
            'nama' => 'Dewi Lestari (Motor Barokah)',
            'email' => 'dewi.pemilik@rental.com',
            'no_telp' => '081377889900',
            'role' => 'pemilik',
            'password' => Hash::make('password123'),
            'nama_bank' => 'Mandiri',
            'no_rekening' => '1370019283746',
            'atas_nama_rekening' => 'Dewi Lestari',
            'saldo' => 180000.00,
            'status_kyc' => 'verified',
        ]);

        $penyewaVerified = Pengguna::create([
            'nama' => 'Andi Pratama',
            'email' => 'andi.penyewa@rental.com',
            'no_telp' => '085712345678',
            'role' => 'penyewa',
            'password' => Hash::make('password123'),
            'no_ktp' => '3273012903950001',
            'foto_ktp' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'no_sim' => '950312001928',
            'foto_sim' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=400',
            'foto_selfie' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400',
            'status_kyc' => 'verified',
        ]);

        $penyewaPendingKyc = Pengguna::create([
            'nama' => 'Reza Rahardian (Pending KYC)',
            'email' => 'reza.pending@rental.com',
            'no_telp' => '082199887766',
            'role' => 'penyewa',
            'password' => Hash::make('password123'),
            'no_ktp' => '3171092801990002',
            'foto_ktp' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'no_sim' => '990112882736',
            'foto_sim' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=400',
            'foto_selfie' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
            'status_kyc' => 'pending',
            'catatan_kyc' => 'Menunggu verifikasi admin',
        ]);

        $penyewaBaru = Pengguna::create([
            'nama' => 'Siti Nurhaliza (Belum KYC)',
            'email' => 'siti.baru@rental.com',
            'no_telp' => '087811223344',
            'role' => 'penyewa',
            'password' => Hash::make('password123'),
            'status_kyc' => 'belum_verifikasi',
        ]);

        // 2. Seed Motors
        $motor1 = Motor::create([
            'pemilik_id' => $pemilik1->id,
            'merk' => 'Honda',
            'tipe_motor' => 'Vario 160 ABS',
            'tahun' => 2024,
            'tipe_cc' => '160',
            'no_plat' => 'B 3829 SGK',
            'warna' => 'Matte Black',
            'deskripsi' => 'Kondisi mesin sangat mulus, servis rutin AHASS. Dilengkapi smart key system, helm SNI 2 pcs, dan jas hujan.',
            'tarif_harian' => 110000.00,
            'tarif_mingguan' => 700000.00,
            'tarif_bulanan' => 2500000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_depan' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_samping' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_belakang' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'approved',
            'status' => 'tersedia',
            'is_active' => true,
        ]);

        $motor2 = Motor::create([
            'pemilik_id' => $pemilik1->id,
            'merk' => 'Yamaha',
            'tipe_motor' => 'NMAX 155 Connected',
            'tahun' => 2023,
            'tipe_cc' => '155',
            'no_plat' => 'B 4192 EFW',
            'warna' => 'Prestige Silver',
            'deskripsi' => 'Nyaman untuk perjalanan jauh maupun harian. Bagasi luas muat helm full face, fitur traction control dan ABS.',
            'tarif_harian' => 135000.00,
            'tarif_mingguan' => 850000.00,
            'tarif_bulanan' => 3000000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_depan' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_samping' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_belakang' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'approved',
            'status' => 'tersedia',
            'is_active' => true,
        ]);

        $motor3 = Motor::create([
            'pemilik_id' => $pemilik2->id,
            'merk' => 'Honda',
            'tipe_motor' => 'Beat Street CBS',
            'tahun' => 2023,
            'tipe_cc' => '110',
            'no_plat' => 'D 5521 KLM',
            'warna' => 'Street Black',
            'deskripsi' => 'Sangat irit bensin, lincah bermanuver di jalanan kota. Cocok untuk kuliah atau jalan-jalan santai.',
            'tarif_harian' => 75000.00,
            'tarif_mingguan' => 480000.00,
            'tarif_bulanan' => 1600000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
            'foto_depan' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
            'foto_samping' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_belakang' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'approved',
            'status' => 'disewa',
            'is_active' => true,
        ]);

        $motor4 = Motor::create([
            'pemilik_id' => $pemilik2->id,
            'merk' => 'Vespa',
            'tipe_motor' => 'Sprint S 150 i-Get ABS',
            'tahun' => 2024,
            'tipe_cc' => '150',
            'no_plat' => 'B 6610 VSP',
            'warna' => 'Yellow Sole',
            'deskripsi' => 'Tampil elegan dan berkelas dengan Vespa Sprint S. Unit terawat super mulus, ban tubeless baru.',
            'tarif_harian' => 200000.00,
            'tarif_mingguan' => 1250000.00,
            'tarif_bulanan' => 4500000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
            'foto_depan' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
            'foto_samping' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_belakang' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'approved',
            'status' => 'tersedia',
            'is_active' => true,
        ]);

        // Motor Pending Approval (Untuk Kurasi Admin Sesuai PRD)
        $motorPending = Motor::create([
            'pemilik_id' => $pemilik1->id,
            'merk' => 'Yamaha',
            'tipe_motor' => 'Aerox 155 CyberCity',
            'tahun' => 2024,
            'tipe_cc' => '155',
            'no_plat' => 'B 1188 ABC',
            'warna' => 'Cyber City Blue',
            'deskripsi' => 'Unit baru turun dealer bulan lalu. Pengajuan sewa baru, menunggu verifikasi tim kurasi.',
            'tarif_harian' => 125000.00,
            'tarif_mingguan' => 800000.00,
            'tarif_bulanan' => 2800000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_depan' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'foto_samping' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
            'foto_belakang' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?w=800&auto=format&fit=crop&q=80',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'LENGKAP',
            'status_approval' => 'pending',
            'status' => 'tersedia',
            'is_active' => true,
        ]);

        // Motor Rejected (Contoh Ditolak Admin dengan Catatan Sesuai PRD)
        $motorRejected = Motor::create([
            'pemilik_id' => $pemilik2->id,
            'merk' => 'Suzuki',
            'tipe_motor' => 'Satria F150',
            'tahun' => 2017,
            'tipe_cc' => '150',
            'no_plat' => 'B 9999 XYZ',
            'warna' => 'Hitam Merah',
            'deskripsi' => 'Satria Fu kopling manual kondisi standar.',
            'tarif_harian' => 80000.00,
            'foto_motor' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800',
            'foto_stnk' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=400',
            'dokumen_kepemilikan' => 'TIDAK LENGKAP',
            'status_approval' => 'rejected',
            'catatan_approval' => 'Foto STNK tidak jelas/buram dan masa berlaku pajak 5 tahunan sudah habis. Harap perbarui dokumen kendaraan.',
            'status' => 'nonaktif',
            'is_active' => false,
        ]);

        // 3. Seed Tarif Rentals (Tabel Pendukung)
        foreach ([$motor1, $motor2, $motor3, $motor4, $motorPending] as $m) {
            Tarif_rental::create([
                'motor_id' => $m->id,
                'tarif_harian' => $m->tarif_harian,
                'tarif_mingguan' => $m->tarif_mingguan ?? ($m->tarif_harian * 6.5),
                'tarif_bulanan' => $m->tarif_bulanan ?? ($m->tarif_harian * 25),
            ]);
        }

        // 4. Seed Penyewaan (Booking Aktif: Beat Street sedang disewa oleh Andi)
        $sewaAktif = Penyewaan::create([
            'kode_booking' => 'BK-20261108001',
            'penyewa_id' => $penyewaVerified->id,
            'motor_id' => $motor3->id,
            'tanggal_mulai' => Carbon::now()->subDays(1)->setTime(10, 0, 0),
            'tanggal_selesai' => Carbon::now()->addDays(2)->setTime(10, 0, 0),
            'durasi_hari' => 3,
            'tipe_durasi' => 'harian',
            'tarif_per_hari' => 75000.00,
            'subtotal' => 225000.00,
            'biaya_layanan' => 15000.00,
            'total_harga' => 240000.00,
            'status_penyewaan' => 'on_rent',
            'waktu_diambil' => Carbon::now()->subDays(1)->setTime(10, 15, 0),
            'catatan_penyewa' => 'Ambil helm ukuran L dan jas hujan',
            'catatan_pemilik' => 'Kondisi bensin 4 bar, helm 2 unit diserahkan.',
        ]);

        $bayarAktif = Pembayaran::create([
            'penyewaan_id' => $sewaAktif->id,
            'kode_transaksi' => 'TRX-20261108-001',
            'jumlah' => 240000.00,
            'metode_pembayaran' => 'va_bca',
            'status_pembayaran' => 'lunas',
            'nomor_va_atau_rekening' => '880812345678',
            'tanggal_pembayaran' => Carbon::now()->subDays(1)->setTime(9, 30, 0),
        ]);

        // 5. Seed Penyewaan Selesai (NMAX disewa minggu lalu dengan Bagi Hasil)
        $sewaSelesai = Penyewaan::create([
            'kode_booking' => 'BK-20261101002',
            'penyewa_id' => $penyewaVerified->id,
            'motor_id' => $motor2->id,
            'tanggal_mulai' => Carbon::now()->subDays(7)->setTime(9, 0, 0),
            'tanggal_selesai' => Carbon::now()->subDays(4)->setTime(9, 0, 0),
            'durasi_hari' => 3,
            'tipe_durasi' => 'harian',
            'tarif_per_hari' => 135000.00,
            'subtotal' => 405000.00,
            'biaya_layanan' => 15000.00,
            'total_harga' => 420000.00,
            'status_penyewaan' => 'selesai',
            'waktu_diambil' => Carbon::now()->subDays(7)->setTime(9, 30, 0),
            'waktu_dikembalikan' => Carbon::now()->subDays(4)->setTime(8, 45, 0),
            'catatan_penyewa' => 'Sangat puas, motor enak dipakai!',
            'catatan_pemilik' => 'Motor kembali tepat waktu, kondisi aman bersih.',
        ]);

        $bayarSelesai = Pembayaran::create([
            'penyewaan_id' => $sewaSelesai->id,
            'kode_transaksi' => 'TRX-20261101-002',
            'jumlah' => 420000.00,
            'metode_pembayaran' => 'qris',
            'status_pembayaran' => 'lunas',
            'nomor_va_atau_rekening' => 'QRIS-GOPAY-RENTAL',
            'tanggal_pembayaran' => Carbon::now()->subDays(7)->setTime(8, 50, 0),
        ]);

        $komisiAdmin = 405000.00 * 0.10; // 10% komisi platform
        $bagianPemilik = 405000.00 - $komisiAdmin;

        Bagi_hasil::create([
            'penyewaan_id' => $sewaSelesai->id,
            'pemilik_id' => $pemilik1->id,
            'total_biaya_sewa' => 405000.00,
            'persentase_komisi' => 10.00,
            'komisi_admin' => $komisiAdmin,
            'bagi_hasil_pemilik' => $bagianPemilik,
            'status_bagi_hasil' => 'masuk_saldo',
            'tanggal_bagi_hasil' => Carbon::now()->subDays(4)->setTime(9, 0, 0),
        ]);

        // 6. Seed Penarikan Dana (Withdrawal Request)
        PenarikanDana::create([
            'pemilik_id' => $pemilik1->id,
            'kode_penarikan' => 'WD-20261108-01',
            'jumlah' => 200000.00,
            'nama_bank' => $pemilik1->nama_bank,
            'no_rekening' => $pemilik1->no_rekening,
            'atas_nama_rekening' => $pemilik1->atas_nama_rekening,
            'status' => 'pending',
            'catatan' => 'Permintaan penarikan hasil sewa NMAX',
        ]);
    }
}
