<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->timestamp('waktu_diambil')->nullable()->after('kurir_id');
            $table->integer('estimasi_menit')->nullable()->after('waktu_diambil');
            $table->text('alasan_batal')->nullable()->after('status');
        });

        // Tambahkan status 'dibatalkan_kurir' ke enum status
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM(
            'menunggu_verifikasi',
            'diproses',
            'ditolak',
            'disiapkan',
            'siap_dikirim',
            'dikirim',
            'selesai',
            'dibatalkan_kurir'
        ) DEFAULT 'menunggu_verifikasi'");
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['waktu_diambil', 'estimasi_menit', 'alasan_batal']);
        });
    }
};