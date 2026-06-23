<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil produk yang sudah disetujui DAN penjualnya berstatus 'active'
        $query = Product::where('status', 'Certified Authentic')
            ->whereHas('user', function ($uQuery) {
                $uQuery->where('status', 'active');
            });

        // 2. Logika Fitur Search (Poin 10)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($catQuery) use ($request) {
                        $catQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user', function ($uQuery) use ($request) {
                        $uQuery->where('shop_name', 'like', '%' . $request->search . '%')
                            ->orWhere('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // 3. Ambil data kategori untuk ditampilkan di View agar tidak error (Poin 5)
        $categories = Category::all();

        // 4. Logika Pagination 8 Data
        $products = $query->latest()->paginate(8)->withQueryString();

        // 5. Kirim data products dan categories ke View
        return view('home', compact('products', 'categories'));
    }

    public function show($id)
    {
        // Cari produk berdasarkan ID dan pastikan statusnya Certified Authentic DAN penjualnya berstatus 'active'
        $product = Product::with(['category', 'user'])
            ->where('status', 'Certified Authentic')
            ->whereHas('user', function ($uQuery) {
                $uQuery->where('status', 'active');
            })
            ->findOrFail($id);

        return view('product.show', compact('product'));
    }

    public function storeProfile($id)
    {
        // Pastikan user tersebut adalah penjual dan berstatus active
        $seller = User::where('role', User::ROLE_PENJUAL)
            ->where('status', 'active')
            ->findOrFail($id);

        $products = Product::where('user_id', $id)
            ->where('status', 'Certified Authentic')
            ->latest()
            ->get();

        $reviews = Review::where('seller_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return view('store.profile', compact('seller', 'products', 'reviews'));
    }
}
