<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('pengiriman_batch_id')->nullable()->after('kurir_id')
                ->constrained('pengiriman_batch')->nullOnDelete();

            // Urutan berhenti dalam satu batch (1 = diantar duluan, dst),
            // dihitung otomatis dari yang terdekat lebih dulu.
            $table->unsignedInteger('urutan_pengiriman')->nullable()->after('pengiriman_batch_id');

            // Jarak & estimasi dari TITIK SEBELUMNYA (bukan dari apotek),
            // dipakai untuk ETA per rumah di rute berantai.
            $table->decimal('jarak_leg_km', 8, 2)->nullable()->after('urutan_pengiriman');
            $table->unsignedInteger('estimasi_leg_menit')->nullable()->after('jarak_leg_km');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pengiriman_batch_id');
            $table->dropColumn(['urutan_pengiriman', 'jarak_leg_km', 'estimasi_leg_menit']);
        });
    }
};
