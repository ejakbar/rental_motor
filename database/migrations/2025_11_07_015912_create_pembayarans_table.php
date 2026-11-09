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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->onDelete('cascade');
            $table->string('kode_transaksi', 40)->unique();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode_pembayaran', 50)->default('va_bca'); // va_bca, va_mandiri, va_bri, qris, gopay, ovo, transfer_bank, cash
            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal', 'kadaluarsa'])->default('pending');
            $table->string('nomor_va_atau_rekening', 50)->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
