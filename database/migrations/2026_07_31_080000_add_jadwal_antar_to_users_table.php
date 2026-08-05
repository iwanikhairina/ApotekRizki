<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jam antar spesifik per kurir (diatur owner di halaman staff),
     * lebih detail dari 'shift' (pagi/sore) yang sudah ada.
     * Contoh: kurir A jam_antar_mulai=10:00, jam_antar_selesai=12:00 —
     * berarti kurir ini hanya akan diberi batch pengiriman otomatis
     * antara jam 10.00 - 12.00, meskipun shift-nya 'pagi' (08.00-17.00).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->time('jam_antar_mulai')->nullable()->after('shift');
            $table->time('jam_antar_selesai')->nullable()->after('jam_antar_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jam_antar_mulai', 'jam_antar_selesai']);
        });
    }
};
