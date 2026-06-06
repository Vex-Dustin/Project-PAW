<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Kategori dulu (Sesuai gambar Anda)
        Category::create([
            'name' => 'Songket Palembang',
            'slug' => 'songket-palembang',
            'description' => 'Kain tenun tradisional khas Palembang yang mewah.'
        ]);

        Category::create([
            'name' => 'Batik Jumputan',
            'slug' => 'batik-jumputan',
            'description' => 'Kain batik motif jumputan dengan warna cerah.'
        ]);

        Category::create([
            'name' => 'Baik Tenun',
            'slug' => 'batik-tenun',
            'description' => 'Kain tenun ikat dari Sumba dengan motif tradisional.'
        ]);

        Category::create([
            'name' => 'Kain Ulos',
            'slug' => 'kain-ulos',
            'description' => 'Kain ulos tradisional dari suku Batak dengan motif khas.'
        ]);

        // 2. Panggil UserSeeder (yang berisi Admin, Penjual, Pembeli)
        $this->call([
            UserSeeder::class,
        ]);

        // 3. Panggil ProductSeeder (yang berisi produk songket dan jumputan)
        // Pastikan di dalam ProductSeeder, category_id-nya merujuk ke ID yang benar
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
