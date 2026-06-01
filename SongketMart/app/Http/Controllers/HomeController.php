<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil produk yang sudah disetujui
        $query = Product::where('status', 'Certified Authentic');

        // 2. Logika Fitur Search (Poin 10)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($catQuery) use ($request) {
                        $catQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // 3. Ambil data kategori untuk ditampilkan di View agar tidak error (Poin 5)
        $categories = Category::all();

        // 4. Logika Pagination 10 Data (Poin 18)
        $products = $query->latest()->paginate(5)->withQueryString();

        // 5. Kirim data products dan categories ke View
        return view('home', compact('products', 'categories'));
    }

    public function show($id)
    {
        // Cari produk berdasarkan ID dan pastikan statusnya Certified Authentic
        $product = Product::with(['category', 'user'])
            ->where('status', 'Certified Authentic')
            ->findOrFail($id);

        return view('product.show', compact('product'));
    }
}
