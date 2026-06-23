<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId, $sellerId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Pastikan pesanan tersebut milik user login dan statusnya Selesai
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'Selesai')
            ->findOrFail($orderId);

        // Pastikan ada produk milik penjual ini di dalam pesanan tersebut
        $hasSellerProduct = $order->items()->whereHas('product', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId);
        })->exists();

        if (!$hasSellerProduct) {
            return redirect()->back()->with('error', 'Penjual ini tidak berasosiasi dengan pesanan Anda.');
        }

        // Cek apakah ulasan untuk penjual ini pada pesanan ini sudah pernah dibuat
        $exists = Review::where('order_id', $orderId)
            ->where('seller_id', $sellerId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk penjual ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'seller_id' => $sellerId,
            'order_id' => $orderId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan toko Anda!');
    }
}
