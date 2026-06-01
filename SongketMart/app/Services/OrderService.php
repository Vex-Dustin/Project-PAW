<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class OrderService
{
    
    // Logika untuk memproses Checkout
     
    public function processCheckout($userId, array $data)
    {
        $carts = Cart::with('product')->where('user_id', $userId)->get();

        if ($carts->isEmpty()) {
            throw new Exception('Keranjang Anda kosong!');
        }

        // Gunakan Database Transaction agar aman (ACID compliance)
        return DB::transaction(function () use ($carts, $userId, $data) {
            $totalPrice = 0;

            // 1. Pengecekan stok awal
            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    throw new Exception("Stok produk {$cart->product->name} tidak mencukupi.");
                }
                $totalPrice += $cart->product->price * $cart->quantity;
            }

            // 2. Buat Order Utama
            $order = Order::create([
                'user_id'          => $userId,
                'total_price'      => $totalPrice,
                'status'           => 'Belum Dibayar',
                'shipping_address' => $data['shipping_address'],
                'payment_method'   => $data['payment_method'],
            ]);

            // 3. Pindahkan Keranjang ke Order Item & Kurangi Stok
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $cart->product->price
                ]);

                // Pengurangan stok otomatis
                $cart->product->decrement('stock', $cart->quantity);
            }

            // 4. Kosongkan Keranjang User
            Cart::where('user_id', $userId)->delete();

            return $order;
        });
    }

    
    // Logika untuk Upload Bukti Pembayaran
    
    public function uploadPaymentProof(Order $order, $file)
    {
        // Hapus foto lama jika ada (mencegah penumpukan file sampah)
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $file->store('payment_proofs', 'public');

        $order->update([
            'payment_proof' => $path,
            'status'        => 'Menunggu Verifikasi'
        ]);

        return $order;
    }

    
    // Logika untuk Penjual Memverifikasi Pembayaran
    
    public function verifyPayment(Order $order)
    {
        if ($order->status !== 'Menunggu Verifikasi') {
            throw new Exception('Pesanan ini tidak memerlukan verifikasi pembayaran saat ini.');
        }

        $order->update(['status' => 'Sudah Dibayar']);

        return $order;
    }

    
    // Logika untuk Penjual Memperbarui Status Pengiriman (Diproses/Dikirim)
     
    public function updateShippingStatus(Order $order, array $data)
    {
        if ($order->status == 'Selesai') {
            throw new Exception('Pesanan sudah selesai dan tidak dapat diubah lagi.');
        }

        $order->update([
            'status'          => $data['status'],
            'resi_number'     => $data['resi_number'] ?? $order->resi_number,
            'shipping_status' => $data['status'] == 'Dikirim' ? 'Dalam Perjalanan' : $order->shipping_status
        ]);

        return $order;
    }

    
    // Logika untuk Pembeli Menyelesaikan Pesanan
    
    public function completeOrder(Order $order)
    {
        $order->update(['status' => 'Selesai']);
        return $order;
    }
}
