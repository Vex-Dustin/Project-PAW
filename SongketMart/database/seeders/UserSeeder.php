<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin (Dekranasda)
        User::create([
            'name' => 'Admin Dekranasda',
            'email' => 'admin@songketmart.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN,
            'is_verified' => true,
        ]);

        // 2. Akun Penjual (Contoh UMKM)
        User::create([
            'name' => 'Zainal Songket',
            'shop_name' => 'Zainal Songket Official',
            'email' => 'penjual@email.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PENJUAL,
            'is_verified' => true, // Anggap saja sudah diverifikasi admin
        ]);

        // 3. Akun Pembeli (User Umum)
        User::create([
            'name' => 'Budi Pembeli',
            'email' => 'pembeli@email.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMBELI,
            'is_verified' => false,
        ]);
    }
}
