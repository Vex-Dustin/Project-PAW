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

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Tenun Ikat Sumba',
            'description' => 'Tenun ikat dari Sumba dengan motif tradisional yang kaya warna.',
            'price' => 1500000,
            'stock' => 7,
            'image' => 'ikat.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 4,
            'name' => 'Kain Ulos Batak',
            'description' => 'Kain ulos tradisional dari suku Batak dengan motif khas.',
            'price' => 800000,
            'stock' => 12,
            'image' => 'ulos.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Endek Bali',
            'description' => 'Tenun endek khas Bali dengan motif yang indah dan warna cerah.',
            'price' => 1200000,
            'stock' => 8,
            'image' => 'endek.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Sumba',
            'description' => 'Tenun ikat dari Sumba dengan motif tradisional yang kaya warna.',
            'price' => 1500000,
            'stock' => 7,
            'image' => 'ikat.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Sasirangan Kalimantan',
            'description' => 'Tenun sasirangan khas Kalimantan dengan motif yang unik dan warna cerah.',
            'price' => 900000,
            'stock' => 10,
            'image' => 'sasirangan.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Baduy',
            'description' => 'Tenun khas suku Baduy dengan motif sederhana namun kaya makna.',
            'price' => 700000,
            'stock' => 15,
            'image' => 'baduy.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Dayak Kalimantan',
            'description' => 'Tenun khas suku Dayak dengan motif yang kaya simbolisme dan warna cerah.',
            'price' => 1100000,
            'stock' => 9,
            'image' => 'dayak.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Toraja',
            'description' => 'Tenun khas suku Toraja dengan motif yang kaya simbolisme dan warna cerah.',
            'price' => 1300000,
            'stock' => 6,
            'image' => 'toraja.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Sasirangan Kalimantan',
            'description' => 'Tenun sasirangan khas Kalimantan dengan motif yang unik dan warna cerah.',
            'price' => 900000,
            'stock' => 10,
            'image' => 'sasirangan.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);

        \App\Models\Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Kain Tenun Baduy',
            'description' => 'Tenun khas suku Baduy dengan motif sederhana namun kaya makna.',
            'price' => 700000,
            'stock' => 15,
            'image' => 'baduy.jpg',
            'status' => 'Certified Authentic',
            'is_verified' => true,
        ]);
    }
}
