<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Mengecek apakah user yang sedang login adalah pemilik pesanan ini.
     * Digunakan untuk melihat detail, upload bukti, dan invoice.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Mengecek apakah user boleh mengklik tombol "Selesai".
     */
    public function complete(User $user, Order $order): bool
    {
        // Hanya bisa di-complete oleh pembeli aslinya
        return $user->id === $order->user_id;
    }

    /**
     * Mengecek apakah user (penjual) berhak mengelola pesanan ini.
     * Penjual berhak jika ada produk miliknya di dalam pesanan tersebut.
     */
    public function manageAsSeller(User $user, Order $order): bool
    {
        return $order->items()->whereHas('product', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }
}
