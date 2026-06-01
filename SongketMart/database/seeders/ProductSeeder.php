<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'user_id' => 2, // ID si Zainal Songket
            'category_id' => 1,
            'name' => 'Songket Palembang Sutra',
            'description' => 'Songket asli Palembang dengan benang emas berkualitas tinggi.',
            'price' => 2500000,
            'stock' => 5,
            'image' => 'songket.jpg', // Nanti kita urus upload filenya
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 2,
            'name' => 'Batik Jumputan',
            'description' => 'Batik khas Palembang dengan motif jumputan warna cerah.',
            'price' => 350000,
            'stock' => 10,
            'image' => 'jumputan.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);
    }
}
