<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Logika membuat produk baru beserta upload gambar
     */
    public function createProduct($userId, array $data, $imageFile = null)
    {
        $imagePath = null;

        // Logika penanganan file
        if ($imageFile) {
            $imagePath = $imageFile->store('products', 'public');
        }

        // Pembuatan produk dengan aturan bisnis: default status 'Pending'
        return Product::create([
            'user_id'     => $userId,
            'category_id' => $data['category_id'],
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'image'       => $imagePath,
            'status'      => 'Pending',
        ]);
    }

    /**
     * Logika update produk dan penggantian gambar jika ada
     */
    public function updateProduct(Product $product, array $data, $imageFile = null)
    {
        // Jika ada file gambar baru yang diunggah
        if ($imageFile) {
            // Hapus gambar lama agar storage server tidak penuh
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $data['image'] = $imageFile->store('products', 'public');
        }

        // Update data ke database
        $product->update($data);

        return $product;
    }

    /**
     * Logika menghapus produk beserta file gambarnya dari storage
     */
    public function deleteProduct(Product $product)
    {
        // Bersihkan storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus data dari database
        $product->delete();
    }
}
