<?php

namespace App\Policies;

use App\Models\Cart; // Sesuaikan jika nama model Anda CartItem
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Mengecek apakah user boleh mengubah kuantitas barang ini.
     */
    public function update(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    /**
     * Mengecek apakah user boleh menghapus barang ini dari keranjangnya.
     */
    public function delete(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }
}
