<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')-> constrained('penggunas')->onDelete('cascade');
            $table->foreignId('penggunas_id')-> constrained('penggunas')->onDelete('cascade');
            $table->string('merk', 50);
            $table->enum('tipe_cc', ['100', '125', '150']);
            $table->string('no_plat', 8);
            $table->enum('status', ['tersedia', 'disewa'])->default('tersedia');
            $table->text('foto_motor')->nullable();
            $table->enum('dokumen_kepemilikan', ['LENGKAP', 'TIDAK LENGKAP'])->default('LENGKAP');
            $table->num('tarif_harian', 20);
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
