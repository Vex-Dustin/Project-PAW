<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_PENJUAL = 'penjual';
    const ROLE_PEMBELI = 'pembeli';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shop_name',
        'is_verified',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isPenjual()
    {
        return $this->role === self::ROLE_PENJUAL;
    }
    public function isPembeli()
    {
        return $this->role === self::ROLE_PEMBELI;
    }

    public function sellerReviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function averageSellerRating()
    {
        return round($this->sellerReviews()->avg('rating') ?? 0, 1);
    }
}
