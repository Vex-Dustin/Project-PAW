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
    /**
     * Logika untuk memproses Checkout
     */
    public function processCheckout($userId, array $data)
    {
        $carts = Cart::with('product')->where('user_id', $userId)->get();

        if ($carts->isEmpty()) {
            throw new Exception('Keranjang Anda kosong!');
        }

        return DB::transaction(function () use ($carts, $userId, $data) {
            $totalPrice = 0;

            foreach ($carts as $cart) {
                if ($cart->product->stock < $cart->quantity) {
                    throw new Exception("Stok produk {$cart->product->name} tidak mencukupi.");
                }
                $totalPrice += $cart->product->price * $cart->quantity;
            }

            $order = Order::create([
                'user_id'          => $userId,
                'total_price'      => $totalPrice,
                'status'           => 'Belum Dibayar',
                'shipping_address' => $data['shipping_address'],
                'payment_method'   => $data['payment_method'],
                'notes'            => $data['notes'] ?? null,
            ]);

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

            Cart::where('user_id', $userId)->delete();

            return $order;
        });
    }

    public function uploadPaymentProof(Order $order, $file)
    {
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

    public function verifyPayment(Order $order)
    {
        if ($order->status !== 'Menunggu Verifikasi') {
            throw new Exception('Pesanan ini tidak memerlukan verifikasi pembayaran saat ini.');
        }

        $order->update(['status' => 'Sudah Dibayar']);

        return $order;
    }

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

    public function completeOrder(Order $order)
    {
        $order->update(['status' => 'Selesai']);
        return $order;
    }

    /**
     * LOGIKA BARU: Membatalkan Pesanan & Mengembalikan Stok Otomatis
     */
    public function cancelOrder(Order $order)
    {
        if ($order->status === 'Dibatalkan') {
            return $order; // Cegah error jika sudah batal
        }

        return DB::transaction(function () use ($order) {
            $order->update(['status' => 'Dibatalkan']);

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            return $order;
        });
    }
}
