<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('alamat')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('resep_path')->nullable();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status', [
                'menunggu_verifikasi',
                'diproses',
                'ditolak',
                'disiapkan',
                'siap_dikirim',
                'dikirim',
                'selesai',
            ])->default('menunggu_verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};