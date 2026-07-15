<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->enum('klasifikasi', ['obat_bebas', 'obat_bebas_terbatas', 'obat_keras'])->default('obat_bebas');
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};