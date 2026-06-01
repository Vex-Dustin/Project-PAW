<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (Mendukung pengecekan lebih dari satu role sekaligus)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, tolak akses (Tampilkan error 403 Forbidden)
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk melihat halaman ini');
    }
}
