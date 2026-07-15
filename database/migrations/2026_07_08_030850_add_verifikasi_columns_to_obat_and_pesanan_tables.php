<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->boolean('butuh_resep')->default(false)->after('klasifikasi');
            $table->boolean('butuh_ktp')->default(false)->after('butuh_resep');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('ktp_path')->nullable()->after('resep_path');
            $table->enum('status_resep', ['tidak_perlu', 'menunggu', 'disetujui', 'ditolak'])
                  ->default('tidak_perlu')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn(['butuh_resep', 'butuh_ktp']);
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['ktp_path', 'status_resep']);
        });
    }
};