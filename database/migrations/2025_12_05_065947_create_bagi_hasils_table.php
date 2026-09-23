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
            $table->foreignId('penyewaans_id')->constrained('penyewaans')->onDelete('cascade');
            $table->decimal('bagi_hasil_pemilik', 10, 2);
            $table->decimal('bagi_hasil_admin', 10, 2);
            $table->enum('status_bagi_hasil', ['pending', 'selesai'])->default('pending');
            $table->date('tanggal_bagi_hasil');
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
