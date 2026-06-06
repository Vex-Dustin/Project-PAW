<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    protected $orderService;

    // Dependency Injection (SOLID Principle)
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(Request $request)
    {
        // 1. Validasi Input (Tanggung jawab Controller)
        $validatedData = $request->validate([
            'shipping_address' => 'required|string|min:10',
            'payment_method'   => 'required|in:transfer,cod',
            'notes'            => 'nullable|string|max:500',
        ]);

        try {
            // 2. Oper ke Service Layer (Tanggung jawab Service)
            $this->orderService->processCheckout(Auth::id(), $validatedData);

            $pesan = $request->payment_method == 'transfer'
                ? 'Checkout berhasil! Silakan unggah bukti transfer agar pesanan diproses.'
                : 'Checkout berhasil! Pesanan COD Anda akan segera diproses penjual.';

            return redirect()->route('order.index')->with('success', $pesan);
        } catch (\Exception $e) {
            // Tangkap jika stok kurang atau keranjang kosong
            return redirect()->back()->with('error', 'Gagal Checkout: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(5);
        return view('order.index', compact('orders'));
    }

    public function show($id)
    {
        // 1. Cari Order (tanpa where user_id)
        $order = Order::with('items.product')->findOrFail($id);

        // 2. Cek Izin via Policy (Otomatis 403 jika bukan miliknya)
        Gate::authorize('view', $order);

        return view('order.show', compact('order'));
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        // Cek Izin
        Gate::authorize('view', $order);

        if ($request->hasFile('payment_proof')) {
            $this->orderService->uploadPaymentProof($order, $request->file('payment_proof'));
            return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi penjual.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah gambar.');
    }

    public function complete($id)
    {
        // Tetap menyertakan where('status', 'Dikirim') karena itu aturan bisnis, BUKAN aturan otorisasi role
        $order = Order::where('status', 'Dikirim')->findOrFail($id);

        // Cek Izin 
        Gate::authorize('complete', $order);

        $this->orderService->completeOrder($order);

        return redirect()->back()->with('success', 'Konfirmasi berhasil! Terima kasih telah berbelanja.');
    }

    public function showInvoice($id)
    {
        $order = Order::findOrFail($id);

        // Cek Izin
        Gate::authorize('view', $order);

        return view('order.invoice', compact('order'));
    }
}
