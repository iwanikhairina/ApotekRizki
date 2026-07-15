<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom is_active kalau belum ada
        //    Dipakai untuk fitur "nonaktifkan staff" & hitung staff aktif di dashboard
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function ($table) {
                $table->boolean('is_active')->default(true)->after('role');
            });
        }

        // 2. Ubah enum role: tambahkan 'owner'
        //    Dibuat NULL-able karena ada baris lama dengan role masih kosong.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('apoteker', 'kurir', 'owner') NULL");

        // 3. Pastikan kolom shift boleh NULL, karena owner tidak punya shift
        DB::statement("ALTER TABLE users MODIFY COLUMN shift ENUM('pagi', 'sore') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan role ke kondisi sebelumnya (tanpa owner)
        // Catatan: kalau sudah ada user dengan role='owner', rollback ini akan gagal
        // kecuali data owner dihapus/diubah dulu.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('apoteker', 'kurir') NOT NULL");

        if (Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('is_active');
            });
        }
    }
};