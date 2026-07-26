<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pesanan MODIFY status VARCHAR(30) NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanan MODIFY status ENUM('menunggu','diproses','dikirim','terkirim','dibatalkan','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};