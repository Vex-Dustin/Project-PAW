<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product; // 1. KUNCI: Menambahkan model Product ke dalam service
use Exception;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    // ==========================================
    // LOGIKA BARU: VERIFIKASI PRODUK SONGKET
    // ==========================================
    /**
     * Logika untuk memverifikasi status produk baru
     */
    public function verifyProduct(Product $product, $status)
    {
        // Update status produk menjadi 'Certified Authentic' atau 'Rejected' sesuai tombol
        $product->update([
            'status' => $status
        ]);

        return $product;
    }

    // ==========================================
    // LOGIKA LAMA: VERIFIKASI PENJUAL (Tetap Dipertahankan)
    // ==========================================
    /**
     * Logika untuk memverifikasi pengajuan penjual
     */
    public function verifySeller(User $user, $status)
    {
        // Update status verifikasi
        $user->update(['verification_status' => $status]);

        // Aturan Bisnis: Jika disetujui, otomatis ubah role menjadi penjual
        if ($status === 'approved') {
            $user->update(['role' => 'penjual']);
        }
        // Jika ditolak atau dibatalkan, kembalikan menjadi pembeli
        elseif ($status === 'rejected') {
            $user->update(['role' => 'pembeli']);
        }

        return $user;
    }

    // ==========================================
    // LOGIKA MANAJEMEN USER & ROLE (Tetap Dipertahankan)
    // ==========================================
    /**
     * Logika untuk memperbarui Role Pengguna (Admin/Penjual/Pembeli)
     */
    public function updateUserRole(User $user, $newRole)
    {
        // Proteksi Sistem: Admin tidak boleh mengubah rolenya sendiri secara tidak sengaja
        if ($user->id === Auth::id()) {
            throw new Exception("Gagal. Anda tidak dapat mengubah hak akses (role) akun Anda sendiri.");
        }

        $user->update(['role' => $newRole]);

        return $user;
    }

    /**
     * Logika untuk mengubah status keaktifan akun pengguna (Non-aktif / Aktif kembali)
     * Opsi B: Membatalkan transaksi aktif secara otomatis ketika dinonaktifkan
     */
    public function toggleUserStatus(User $user)
    {
        // Proteksi Sistem: Admin tidak boleh menonaktifkan akunnya sendiri (bunuh diri sistem)
        if ($user->id === Auth::id()) {
            throw new Exception("Gagal. Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif.");
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        // Jika dinonaktifkan, batalkan transaksi aktif (termasuk yang sedang dikirim)
        if ($newStatus === 'inactive') {
            $activeStatuses = ['Belum Dibayar', 'Menunggu Verifikasi', 'Sudah Dibayar', 'Dikirim'];

            if ($user->role === 'pembeli') {
                // Batalkan pesanan yang dibuat oleh pembeli ini yang belum selesai (termasuk yang sedang dikirim)
                \App\Models\Order::where('user_id', $user->id)
                    ->whereIn('status', $activeStatuses)
                    ->update([
                        'status' => 'Dibatalkan',
                        'shipping_status' => 'Dibatalkan oleh Sistem (Akun Dinonaktifkan)'
                    ]);
            } elseif ($user->role === 'penjual') {
                // Batalkan semua pesanan masuk untuk produk penjual ini yang belum selesai (termasuk yang sedang dikirim)
                $ordersToCancel = \App\Models\Order::whereIn('status', $activeStatuses)
                    ->whereHas('items.product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->get();

                foreach ($ordersToCancel as $order) {
                    $order->update([
                        'status' => 'Dibatalkan',
                        'shipping_status' => 'Dibatalkan oleh Sistem (Toko Dinonaktifkan)'
                    ]);
                }
            }
        }

        return $newStatus;
    }
}
