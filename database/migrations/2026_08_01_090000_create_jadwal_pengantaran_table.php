<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Master data jadwal pengantaran yang diatur OWNER (bukan per-kurir).
     * Contoh isi: 10:00 - 11:00, 13:00 - 14:00, dst. Slot yang aktif akan
     * muncul sebagai pilihan untuk customer saat checkout.
     */
    public function up(): void
    {
        Schema::create('jadwal_pengantaran', function (Blueprint $table) {
            $table->id();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengantaran');
    }
};
