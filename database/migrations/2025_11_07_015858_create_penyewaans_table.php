<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyewaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->foreignId('penyewa_id')->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade');
            
            // Tanggal dan Jam Sesuai PRD
            $table->dateTime('tanggal_mulai'); // Tanggal & Jam Pengambilan
            $table->dateTime('tanggal_selesai'); // Tanggal & Jam Pengembalian
            $table->integer('durasi_hari')->default(1);
            $table->enum('tipe_durasi', ['harian', 'mingguan', 'bulanan'])->default('harian');

            // Biaya Sesuai PRD: (Tarif Harian x Durasi) + Biaya Layanan Platform
            $table->decimal('tarif_per_hari', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('biaya_layanan', 12, 2)->default(15000);
            $table->decimal('total_harga', 12, 2);

            // Status Alur Transaksi & Resolusi
            $table->enum('status_penyewaan', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
                'disetujui',
                'on_rent',
                'selesai',
                'batal',
                'dispute'
            ])->default('menunggu_pembayaran');

            $table->dateTime('waktu_diambil')->nullable();
            $table->dateTime('waktu_dikembalikan')->nullable();
            $table->decimal('denda_keterlambatan', 12, 2)->default(0);
            $table->text('catatan_penyewa')->nullable();
            $table->text('catatan_pemilik')->nullable();
            $table->text('catatan_resolusi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
