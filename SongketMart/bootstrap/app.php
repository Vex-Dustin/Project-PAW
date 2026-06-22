<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // // Konfigurasi redirect saat belum login
        // // (opsional, default sudah mengarah ke route 'login')
        // $middleware->redirectGuestsTo('/login');
        // // Konfigurasi redirect saat sudah login dan mencoba akses halaman guest
        // $middleware->redirectUsersTo('/dashboard');

        // Daftarkan alias middleware role kita di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'active_user' => \App\Http\Middleware\CheckUserStatus::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
