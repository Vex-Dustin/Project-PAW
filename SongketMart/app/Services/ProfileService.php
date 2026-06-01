<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Logika untuk memperbarui data dasar profil
     */
    public function updateProfile(User $user, array $data, $avatarFile = null)
    {
        // Jika ada upload foto profil (opsional, jika aplikasi Anda mendukungnya)
        if ($avatarFile) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $avatarFile->store('avatars', 'public');
        }

        $user->update([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? $user->phone,
            'address' => $data['address'] ?? $user->address,
            'shop_name' => $data['shop_name'] ?? $user->shop_name,
        ]);

        return $user;
    }

    /**
     * Logika untuk mengganti password dengan aman
     */
    public function updatePassword(User $user, array $data)
    {
        // Cek apakah password lama yang dimasukkan sesuai dengan di database
        if (!Hash::check($data['current_password'], $user->password)) {
            throw new Exception("Gagal. Password saat ini yang Anda masukkan salah.");
        }

        // Cegah pengguna menggunakan password yang sama dengan yang lama
        if (Hash::check($data['new_password'], $user->password)) {
            throw new Exception("Gagal. Password baru tidak boleh sama dengan password lama.");
        }

        // Hash dan simpan password baru
        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return true;
    }
}
