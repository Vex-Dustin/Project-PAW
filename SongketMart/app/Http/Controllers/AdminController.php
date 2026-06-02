<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product; // 1. KUNCI UTAMA: Pastikan model Product di-import!
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    // Dependency Injection untuk Service Layer
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    // ==========================================
    // BAGIAN 1: VERIFIKASI PRODUK SONGKET
    // ==========================================
    public function verifications()
    {
        // Mengambil produk berkategori songket yang statusnya 'Pending'
        // Eager loading ('with') digunakan agar loading data user & kategori jadi ringan
        $pendingProducts = Product::with(['user', 'category'])
            ->where('status', 'Pending')
            ->latest()
            ->get();

        // Mengirimkan variabel $pendingProducts (Ini akan menyelesaikan error Undefined Variable)
        return view('admin.verifications.index', compact('pendingProducts'));
    }

    public function verify(Request $request, $id)
    {
        // Validasi status sesuai dengan nilai tombol yang ada di file Blade Anda
        $request->validate([
            'status' => 'required|in:Certified Authentic,Rejected'
        ]);

        $product = Product::findOrFail($id);

        // Melempar logika update ke AdminService agar aman dan rapi (SOLID)
        $this->adminService->verifyProduct($product, $request->status);

        $pesan = $request->status === 'Certified Authentic'
            ? 'Produk berhasil disertifikasi sebagai Barang Asli.'
            : 'Produk songket telah ditolak.';

        return redirect()->back()->with('success', $pesan);
    }

    // ==========================================
    // BAGIAN 2: MANAJEMEN USER & ROLE
    // ==========================================
    public function users()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,penjual,pembeli'
        ]);

        $user = User::findOrFail($id);

        try {
            $this->adminService->updateUserRole($user, $request->role);
            return redirect()->back()->with('success', "Role pengguna berhasil diubah menjadi {$request->role}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        try {
            $this->adminService->deleteUser($user);
            return redirect()->back()->with('success', 'Pengguna berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
