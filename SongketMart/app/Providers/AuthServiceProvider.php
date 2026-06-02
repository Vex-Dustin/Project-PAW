<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //Tempat untuk mendaftarkan kebijakan otorisasi (authorization policies)
        //Tidak jadi dipakai karena kita menggunakan middleware untuk mengatur akses berdasarkan role
    }
}
