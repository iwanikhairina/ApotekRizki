<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan nilai enum baru 'dibatalkan_customer' ke kolom status,
     * dipakai saat customer membatalkan pesanan sendiri (beda dari
     * 'ditolak' oleh apoteker atau 'dibatalkan_kurir' oleh kurir).
     * Semua nilai enum lama dipertahankan persis seperti sebelumnya.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pesanan
            MODIFY COLUMN status ENUM(
                'menunggu_verifikasi',
                'diproses',
                'ditolak',
                'disiapkan',
                'siap_dikirim',
                'dikirim',
                'selesai',
                'dibatalkan_kurir',
                'dibatalkan_customer'
            ) DEFAULT 'menunggu_verifikasi'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pesanan
            MODIFY COLUMN status ENUM(
                'menunggu_verifikasi',
                'diproses',
                'ditolak',
                'disiapkan',
                'siap_dikirim',
                'dikirim',
                'selesai',
                'dibatalkan_kurir'
            ) DEFAULT 'menunggu_verifikasi'
        ");
    }
};