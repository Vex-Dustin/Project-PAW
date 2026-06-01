<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    // Dependency Injection (SOLID)
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // Menampilkan halaman keranjang (Read data tidak apa-apa tetap di Controller)
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('cart.index', compact('carts'));
    }

    // Menambahkan barang ke keranjang
    public function store(Request $request, $productId)
    {
        $qty = $request->input('quantity', 1); // Default quantity 1

        try {
            $this->cartService->addToCart(Auth::id(), $productId, $qty);
            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Memperbarui jumlah barang di keranjang
    public function update(Request $request, $id)
    {
        // Validasi agar jumlah minimal adalah 1
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->updateQuantity(Auth::id(), $id, $request->quantity);
            return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    // Menghapus barang dari keranjang
    public function destroy($id)
    {
        try {
            $this->cartService->removeFromCart(Auth::id(), $id);
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus produk.');
        }
    }
}
