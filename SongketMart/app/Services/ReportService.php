<?php

namespace App\Services;

use App\Models\Report;

class ReportService
{
    /**
     * Logika untuk membuat laporan baru dari pengguna
     */
    public function createReport($userId, array $data)
    {
        // Aturan bisnis: setiap laporan baru otomatis berstatus 'pending'
        return Report::create([
            'user_id' => $userId,
            'type'    => $data['type'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status'  => 'pending',
        ]);
    }

    /**
     * Logika untuk memperbarui status laporan (biasanya oleh Admin)
     */
    public function updateStatus(Report $report, $newStatus)
    {
        $report->update(['status' => $newStatus]);

        return $report;
    }
}
