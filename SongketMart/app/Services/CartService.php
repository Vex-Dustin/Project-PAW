<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Exception;

class CartService
{
    // Logika menambahkan barang ke keranjang

    public function addToCart($userId, $productId, $quantity)
    {
        // Pastikan produk valid
        $product = Product::findOrFail($productId);

        // Cari keranjang user untuk produk ini
        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cart) {
            // Jika produk sudah ada, tambahkan kuantitasnya
            // (Opsional: Anda bisa menambahkan validasi cek stok di sini juga)
            $newQuantity = $cart->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                throw new Exception("Gagal. Jumlah total pesanan melebihi stok yang tersedia ({$product->stock} pcs).");
            }

            $cart->update(['quantity' => $newQuantity]);
        } else {
            if ($quantity > $product->stock) {
                throw new Exception("Gagal. Stok tidak mencukupi.");
            }

            // Jika belum ada, buat baru
            Cart::create([
                'user_id'    => $userId,
                'product_id' => $productId,
                'quantity'   => $quantity
            ]);
        }

        return true;
    }

    
    // Logika mengupdate jumlah barang di keranjang
     
    public function updateQuantity($userId, $cartId, $quantity)
    {
        $cart = Cart::where('user_id', $userId)->findOrFail($cartId);

        if ($quantity > $cart->product->stock) {
            throw new Exception("Gagal. Stok yang tersedia hanya {$cart->product->stock} pcs.");
        }

        $cart->update([
            'quantity' => $quantity
        ]);

        return $cart;
    }

    // Logika menghapus barang dari keranjang
     
    public function removeFromCart($userId, $cartId)
    {
        $cart = Cart::where('user_id', $userId)->findOrFail($cartId);
        $cart->delete();

        return true;
    }
}
