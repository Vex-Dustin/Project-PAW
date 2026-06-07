<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SellerOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        // -------------------------------------------------------------------
        // LAZY CHECKING: Auto-Cancel Pesanan Transfer yang Lewat 2 Jam
        // -------------------------------------------------------------------
        $expiredOrders = Order::where('payment_method', 'transfer')
            ->where('status', 'Belum Dibayar')
            ->where('created_at', '<=', now()->subHour(1))
            ->with('items.product')
            ->get();

        if ($expiredOrders->isNotEmpty()) {
            foreach ($expiredOrders as $expOrder) {
                // Batalkan dan kembalikan stok
                $this->orderService->cancelOrder($expOrder);
            }
        }
        // -------------------------------------------------------------------

        $orders = Order::whereHas('items.product', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(5);

        return view('seller.orders.index', compact('orders'));
    }

    public function verifyPayment($id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('manageAsSeller', $order);

        try {
            $this->orderService->verifyPayment($order);
            return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi. Silakan proses pesanan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('manageAsSeller', $order);

        // Tambahkan 'Dibatalkan' ke dalam validasi
        $validatedData = $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Dibatalkan',
            'resi_number' => 'required_if:status,Dikirim|nullable|string|max:50'
        ]);

        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            if ($oldStatus === $newStatus) {
                return redirect()->back()->with('info', 'Status tidak ada perubahan.');
            }

            // Jika Penjual Memilih Batalkan
            if ($newStatus === 'Dibatalkan') {
                $this->orderService->cancelOrder($order);
                return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.');
            }

            // Jika Diproses / Dikirim
            $this->orderService->updateShippingStatus($order, $validatedData);
            return redirect()->back()->with('success', 'Pesanan berhasil diupdate menjadi: ' . $newStatus);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        Gate::authorize('manageAsSeller', $order);

        // Kunci detail pesanan untuk metode Transfer yang Belum Dibayar
        if ($order->payment_method === 'transfer' && $order->status === 'Belum Dibayar') {
            return redirect()->route('seller.orders.index')
                ->with('error', 'Detail pesanan metode transfer tidak dapat dilihat sebelum pembeli mengunggah bukti pembayaran.');
        }

        return view('seller.orders.show', compact('order'));
    }
}
