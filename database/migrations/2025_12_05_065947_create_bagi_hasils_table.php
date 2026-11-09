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
        Schema::create('bagi_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->onDelete('cascade');
            $table->foreignId('pemilik_id')->constrained('penggunas')->onDelete('cascade');
            $table->decimal('total_biaya_sewa', 12, 2);
            $table->decimal('persentase_komisi', 5, 2)->default(10.00); // 10%
            $table->decimal('komisi_admin', 12, 2);
            $table->decimal('bagi_hasil_pemilik', 12, 2);
            $table->enum('status_bagi_hasil', ['pending', 'masuk_saldo'])->default('pending');
            $table->dateTime('tanggal_bagi_hasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagi_hasils');
    }
};
