<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Satu "batch" = satu kali berangkat kurir yang membawa BEBERAPA
     * pesanan sekaligus (seperti model pengantaran Alfagift), diambil
     * otomatis begitu jam_antar kurir tiba.
     */
    public function up(): void
    {
        Schema::create('pengiriman_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurir_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('berjalan'); // berjalan, selesai
            $table->integer('jumlah_pesanan')->default(0);
            $table->decimal('total_jarak_km', 8, 2)->nullable();
            $table->timestamp('dimulai_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_batch');
    }
};
