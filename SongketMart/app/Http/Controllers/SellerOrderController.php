<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // <-- Tambahkan ini

class SellerOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        // Kueri index ini dibiarkan (TIDAK DIUBAH) karena berfungsi sebagai filter tabel
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

        // Cek izin akses sebagai penjual
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

        // Cek izin akses sebagai penjual
        Gate::authorize('manageAsSeller', $order);

        $validatedData = $request->validate([
            'status' => 'required|in:Diproses,Dikirim',
            'resi_number' => 'required_if:status,Dikirim|nullable|string|max:50'
        ]);

        try {
            $this->orderService->updateShippingStatus($order, $validatedData);
            return redirect()->back()->with('success', 'Pesanan berhasil diupdate menjadi: ' . $request->status);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        // Cari order dan muat relasinya untuk efisiensi
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        // Cek izin akses sebagai penjual
        Gate::authorize('manageAsSeller', $order);

        return view('seller.orders.show', compact('order'));
    }
}
