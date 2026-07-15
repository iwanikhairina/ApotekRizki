<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'owner'], // dipakai sebagai kunci, biar tidak dobel kalau seeder dijalankan ulang
            [
                'name' => 'Rizki Pemilik',
                'email' => 'owner@apotekrizki.test',
                'phone' => '081234567890',
                'alamat' => 'Blang Kolak II, Bebesen, Aceh Tengah',
                'tanggal_lahir' => '1990-01-01',
                'shift' => null,
                'role' => 'owner',
                'is_active' => true,
                'password' => Hash::make('password123'),
            ]
        );
    }
}