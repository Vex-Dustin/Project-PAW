<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // TES POTONG KOMPAS TANPA BLADE LAYOUT
        // if (Auth::check()) {
        //     return "Halo! Sinyal aman. Anda sudah login sebagai: " . Auth::user()->name;
        // }

        // return "Anda belum login!";
        $user = Auth::user();

        // Cek Role untuk menentukan View & Data
        if ($user->role == 'admin') {
            // DATA GLOBAL UNTUK ADMIN (Tanpa filter user_id)
            $data = [
                'user' => $user,
                'total_users' => \App\Models\User::count(),
                'total_sellers' => \App\Models\User::where('role', 'penjual')->count(),
                'total_products' => \App\Models\Product::count(),
                'pending_products' => \App\Models\Product::where('status', 'Pending')->count(),
                'total_orders' => \App\Models\Order::count(),
                'total_revenue' => \App\Models\Order::where('status', 'Selesai')->sum('total_price'),
            ];

            return view('admin.dashboard', $data);
        } elseif ($user->role == 'penjual') {
            // DATA SPESIFIK UNTUK PENJUAL (Dengan filter user_id)
            $data = [
                'user' => $user,
                'product_count' => \App\Models\Product::where('user_id', $user->id)->count(),
                'pending_count' => \App\Models\Product::where('user_id', $user->id)->where('status', 'Pending')->count(),
            ];

            // 1. Hitung Total Pesanan
            $data['total_orders'] = \App\Models\Order::whereHas('items.product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            // 2. Hitung Pendapatan Real (Hanya yang statusnya 'Selesai')
            $data['total_revenue'] = \App\Models\Order::where('status', 'Selesai')
                ->whereHas('items.product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->sum('total_price');

            // 3. Hitung Pesanan yang Perlu Tindakan
            $data['pending_action'] = \App\Models\Order::where('status', 'Sudah Dibayar')
                ->whereHas('items.product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count();

            return view('seller.dashboard', $data);
        } else {
            // DATA SPESIFIK UNTUK PEMBELI
            $data = [
                'user' => $user,
            ];
            return view('dashboard', $data);
        }
    }
}
