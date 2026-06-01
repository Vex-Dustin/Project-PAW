<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    // Dependency Injection
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Oper ke Service Layer
        $this->productService->createProduct(
            Auth::id(),
            $validatedData,
            $request->file('image')
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan dan menunggu verifikasi admin.');
    }

    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::all();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // 1. Pastikan produk ini milik penjual yang sedang login
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        // 2. Validasi Input
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // 3. Oper seluruh data request (kecuali token dan method) ke Service
        $data = $request->except(['_token', '_method']);

        $this->productService->updateProduct(
            $product,
            $data,
            $request->file('image')
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // 1. Pastikan produk ini milik penjual yang sedang login
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        // 2. Oper ke Service untuk dihapus beserta gambarnya
        $this->productService->deleteProduct($product);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk songket berhasil dihapus!');
    }
}
