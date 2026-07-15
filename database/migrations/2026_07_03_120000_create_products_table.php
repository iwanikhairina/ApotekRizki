<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('sku')->nullable()->unique(); // kode produk dari sistem kasir/POS, dipakai untuk sinkronisasi ulang saat re-import
            $table->string('category'); // slug: obat, nutrisi, suplemen, bayi, herbal, alkes, mata, kecantikan, dewasa
            $table->unsignedInteger('price');
            $table->unsignedInteger('cost_price')->nullable(); // harga modal, untuk laporan margin (opsional, tidak ditampilkan ke customer)
            $table->integer('stock')->default(0); // integer biasa (bukan unsigned) supaya bisa nampung stok minus dari data lama, lalu dibenerin manual
            $table->string('image')->nullable(); // path di storage, misal products/paracetamol.jpg

            // 'keras' | 'terbatas' | 'bebas' | null (kalau bukan golongan obat, misal produk bayi)
            $table->string('drug_class')->nullable();

            // Informasi obat untuk halaman detail produk
            $table->text('description')->nullable();
            $table->text('indication')->nullable();
            $table->text('composition')->nullable();
            $table->text('dosage')->nullable();
            $table->text('usage')->nullable();
            $table->text('contraindication')->nullable();
            $table->text('side_effect')->nullable();
            $table->string('manufacturer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
