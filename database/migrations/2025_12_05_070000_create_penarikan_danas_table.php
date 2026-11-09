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
        Schema::create('penarikan_danas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('penggunas')->onDelete('cascade');
            $table->string('kode_penarikan', 40)->unique();
            $table->decimal('jumlah', 14, 2);
            $table->string('nama_bank', 50);
            $table->string('no_rekening', 50);
            $table->string('atas_nama_rekening', 100);
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->dateTime('tanggal_diproses')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_danas');
    }
};

