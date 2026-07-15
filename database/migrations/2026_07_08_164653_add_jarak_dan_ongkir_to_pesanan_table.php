<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->decimal('jarak_km', 6, 2)->nullable()->after('alamat');
            $table->decimal('ongkir', 12, 2)->default(0)->after('jarak_km');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['jarak_km', 'ongkir']);
        });
    }
};