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
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('penggunas')->onDelete('cascade');
            $table->string('merk', 50); // Honda, Yamaha, Vespa, dll
            $table->string('tipe_motor', 100); // Vario 160, NMAX, Beat, Sprint, dll
            $table->integer('tahun')->default(2023);
            $table->string('tipe_cc', 20); // 110, 125, 150, 160, 250
            $table->string('no_plat', 20);
            $table->string('warna', 50)->nullable();
            $table->text('deskripsi')->nullable();
            
            // Tarif
            $table->decimal('tarif_harian', 12, 2);
            $table->decimal('tarif_mingguan', 12, 2)->nullable();
            $table->decimal('tarif_bulanan', 12, 2)->nullable();

            // Foto Kendaraan (Depan, Samping, Belakang Sesuai PRD)
            $table->string('foto_motor')->nullable(); // Foto Utama
            $table->string('foto_depan')->nullable();
            $table->string('foto_samping')->nullable();
            $table->string('foto_belakang')->nullable();

            // Dokumen STNK
            $table->string('foto_stnk')->nullable();
            $table->enum('dokumen_kepemilikan', ['LENGKAP', 'TIDAK LENGKAP'])->default('LENGKAP');

            // Status Kurasi & Ketersediaan Sesuai PRD
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_approval')->nullable(); // Alasan jika ditolak oleh Admin
            $table->enum('status', ['tersedia', 'disewa', 'nonaktif'])->default('tersedia');
            $table->boolean('is_active')->default(true); // Admin force disable if problem reported

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
