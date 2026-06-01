<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Menangani logika pembuatan user baru ke database
     */
    public function registerUser(array $data)
    {
        return User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => $data['role'],
            'is_verified' => false,
            // Jika pembeli, shop_name otomatis null. Jika penjual, ambil dari input
            'shop_name'   => ($data['role'] === 'penjual') ? ($data['shop_name'] ?? null) : null,
        ]);
    }

    /**
     * Menentukan arah redirect berdasarkan role pengguna setelah login
     */
    public function getRedirectPath($user)
    {
        if ($user->role === 'admin') {
            return '/admin/dashboard';
        } elseif ($user->role === 'penjual') {
            return '/seller/dashboard';
        }

        // Default untuk pembeli / user biasa
        return '/';
    }
}
