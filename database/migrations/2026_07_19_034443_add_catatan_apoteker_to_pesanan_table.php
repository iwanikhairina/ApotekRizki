<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Catatan dari apoteker saat verifikasi resep: obat apa saja yang
            // dibaca dari resep, dosis, atau alasan penolakan.
            // Beda dengan kolom `catatan` yang isinya catatan dari CUSTOMER.
            $table->text('catatan_apoteker')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('catatan_apoteker');
        });
    }
};