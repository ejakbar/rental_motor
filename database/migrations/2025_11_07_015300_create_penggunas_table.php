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
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('no_telp', 25)->nullable();
            $table->enum('role', ['penyewa', 'pemilik', 'admin'])->default('penyewa');
            $table->string('password');
            $table->string('foto_profil')->nullable();
            
            // Rekening Pemilik (Withdrawal)
            $table->string('nama_bank', 50)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('atas_nama_rekening', 100)->nullable();
            $table->decimal('saldo', 14, 2)->default(0);

            // KYC Penyewa
            $table->string('no_ktp', 30)->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('no_sim', 30)->nullable();
            $table->string('foto_sim')->nullable();
            $table->string('foto_selfie')->nullable();
            $table->enum('status_kyc', ['belum_verifikasi', 'pending', 'verified', 'rejected'])->default('belum_verifikasi');
            $table->text('catatan_kyc')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};
