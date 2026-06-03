<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Menentukan apakah user (penjual) boleh mengubah produk ini.
     */
    public function update(User $user, Product $product): bool
    {
        // Hanya boleh di-update jika ID user yang login sama dengan user_id pemilik produk
        return $user->id === $product->user_id;
    }

    /**
     * Menentukan apakah user (penjual) boleh menghapus produk ini.
     */
    public function delete(User $user, Product $product): bool
    {
        // Logikanya sama dengan update, hanya pemilik aslinya yang boleh menghapus
        return $user->id === $product->user_id;
    }
}
