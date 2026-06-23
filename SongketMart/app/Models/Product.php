<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Pastikan field ini sudah ada agar data bisa disimpan
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Tambahkan Relasi ke tabel users (Penjual)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan Relasi ke tabel categories (Kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
