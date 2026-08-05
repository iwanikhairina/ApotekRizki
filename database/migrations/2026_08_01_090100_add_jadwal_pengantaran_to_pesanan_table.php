<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Simpan jadwal pengantaran yang DIPILIH customer saat checkout.
     *
     * jadwal_pengantaran_id  -> referensi ke master data (bisa null kalau
     *                           slot master-nya sudah dihapus owner).
     * jadwal_antar_mulai/selesai -> SNAPSHOT jam pada saat pesanan dibuat,
     *                           supaya histori pesanan tetap akurat walau
     *                           owner nanti mengubah/menghapus slot master.
     * jadwal_popup_shown     -> flag agar pop-up info jadwal di halaman
     *                           Detail Pesanan cuma tampil sekali (otomatis
     *                           muncul setelah pesanan dibuat / pertama kali
     *                           halaman detail dibuka), tidak tiap kunjungan.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('jadwal_pengantaran_id')->nullable()->after('catatan')
                ->constrained('jadwal_pengantaran')->nullOnDelete();
            $table->time('jadwal_antar_mulai')->nullable()->after('jadwal_pengantaran_id');
            $table->time('jadwal_antar_selesai')->nullable()->after('jadwal_antar_mulai');
            $table->boolean('jadwal_popup_shown')->default(false)->after('jadwal_antar_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jadwal_pengantaran_id');
            $table->dropColumn(['jadwal_antar_mulai', 'jadwal_antar_selesai', 'jadwal_popup_shown']);
        });
    }
};
