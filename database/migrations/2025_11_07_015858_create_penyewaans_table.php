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
            $table->foreignId('penyewa_id')->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('motors_id')->constrained('motors')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('tipe-durasi', ['harian', 'mingguan', 'bulanan']) ->default('harian');
            $table->string('harga', 10, 2);
            $table->enum('status_penyewaan', ['diproses', 'disetujui', 'selesai', 'batal'])->default('diproses');
            $table->date('tanggal')->nullable();
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
