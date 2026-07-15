<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_penerima')->nullable()->after('alamat');
            $table->string('no_telepon')->nullable()->after('nama_penerima');
            $table->string('label_alamat')->nullable()->after('no_telepon');
            $table->string('detail_alamat')->nullable()->after('label_alamat');
            $table->string('provinsi')->nullable()->after('detail_alamat');
            $table->string('kota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kota');
            $table->string('kelurahan')->nullable()->after('kecamatan');
            $table->string('kode_pos', 10)->nullable()->after('kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nama_penerima', 'no_telepon', 'label_alamat', 'detail_alamat',
                'provinsi', 'kota', 'kecamatan', 'kelurahan', 'kode_pos',
            ]);
        });
    }
};