<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Gate;

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
        // 1. Cari produknya dulu tanpa peduli siapa pemiliknya
        $product = Product::findOrFail($id);

        // 2. Gunakan POLICY untuk mengecek izin akses (Otomatis melempar 403 jika gagal)
        Gate::authorize('update', $product);

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Cek izin akses via Policy
        Gate::authorize('update', $product);

        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

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
        $product = Product::findOrFail($id);

        // Cek izin akses via Policy
        Gate::authorize('delete', $product);

        $this->productService->deleteProduct($product);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk songket berhasil dihapus!');
    }
}
