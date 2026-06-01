<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (Penjual)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Relasi ke tabel categories
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->string('name'); // Nama Produk
            $table->text('description')->nullable(); // Deskripsi Produk
            $table->bigInteger('price'); // Harga Produk
            $table->integer('stock'); // Stok Produk
            $table->string('image')->nullable(); // URL atau path gambar produk
            $table->boolean('is_verified')->default(false); // Status verifikasi produk oleh admin

            // Status sertifikasi
            $table->enum('status', ['Pending', 'Certified Authentic', 'Rejected'])->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
