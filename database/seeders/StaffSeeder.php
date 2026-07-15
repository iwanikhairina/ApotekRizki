<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'kurir1'],
            [
                'name' => 'Kurir Satu',
                'email' => 'kurir1@apotekrizki.test',
                'phone' => '081200000001',
                'password' => Hash::make('password123'),
                'role' => 'kurir',
            ]
        );

        User::updateOrCreate(
            ['username' => 'apoteker1'],
            [
                'name' => 'Apoteker Satu',
                'email' => 'apoteker1@apotekrizki.test',
                'phone' => '081200000002',
                'password' => Hash::make('password123'),
                'role' => 'apoteker',
            ]
        );
    }
}