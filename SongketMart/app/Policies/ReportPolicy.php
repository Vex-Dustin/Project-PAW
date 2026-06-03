<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Cek apakah user boleh melihat detail laporan ini.
     * Aturan: Admin boleh melihat SEMUA laporan, User biasa HANYA melihat miliknya.
     */
    public function view(User $user, Report $report): bool
    {
        return $user->role === 'admin' || $user->id === $report->user_id;
    }

    /**
     * Cek apakah user boleh merubah status laporan (Memproses/Menyelesaikan).
     * Aturan: HANYA Admin yang boleh melakukan ini.
     */
    public function updateStatus(User $user, Report $report): bool
    {
        return $user->role === 'admin';
    }
}
