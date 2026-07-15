<?php

namespace Database\Seeders;

use App\Models\Obat;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $wani = User::updateOrCreate(
            ['email' => 'wani@example.com'],
            [
                'name' => 'Wani',
                'username' => 'wani_customer',
                'phone' => '081200000010',
                'password' => bcrypt('password123'),
            ]
        );

        $budi = User::updateOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi',
                'username' => 'budi_customer',
                'phone' => '081200000011',
                'password' => bcrypt('password123'),
            ]
        );

        $paracetamol = Obat::firstOrCreate(['nama' => 'Paracetamol 500mg'], [
            'klasifikasi' => 'obat_bebas',
            'harga' => 5000,
            'stok' => 100,
            'tanggal_kadaluarsa' => now()->addYear(),
        ]);

        $amoxicillin = Obat::firstOrCreate(['nama' => 'Amoxicillin 500mg'], [
            'klasifikasi' => 'obat_keras',
            'harga' => 15000,
            'stok' => 50,
            'tanggal_kadaluarsa' => now()->addMonths(8),
        ]);

        // Pesanan P001 - Wani - Menunggu Verifikasi
        $pesanan1 = Pesanan::create([
            'user_id' => $wani->id,
            'alamat' => 'Jl. Teuku Umar No. 12, Banda Aceh',
            'metode_pembayaran' => 'Transfer Bank',
            'status' => 'menunggu_verifikasi',
            'total_harga' => 25000,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan1->id,
            'obat_id' => $paracetamol->id,
            'jumlah' => 2,
            'harga_satuan' => 5000,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan1->id,
            'obat_id' => $amoxicillin->id,
            'jumlah' => 1,
            'harga_satuan' => 15000,
        ]);

        // Pesanan P002 - Budi - Diproses
        $pesanan2 = Pesanan::create([
            'user_id' => $budi->id,
            'alamat' => 'Jl. Sultan Iskandar Muda No. 5, Banda Aceh',
            'metode_pembayaran' => 'Bayar di Tempat (COD)',
            'status' => 'diproses',
            'total_harga' => 10000,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan2->id,
            'obat_id' => $paracetamol->id,
            'jumlah' => 2,
            'harga_satuan' => 5000,
        ]);
    }
}