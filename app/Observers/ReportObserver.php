<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\Report;

class ReportObserver
{
    /**
     * Handle the Report "updated" event.
     * Kirim notifikasi saat status laporan berubah
     */
    public function updated(Report $report): void
    {
        // Cek apakah status berubah
        if ($report->isDirty('status')) {
            $oldStatus = $report->getOriginal('status');
            $newStatus = $report->status;

            // Buat notifikasi untuk user
            $this->createStatusNotification($report, $oldStatus, $newStatus);
        }

        // Cek apakah admin_note berubah (dan tidak kosong)
        if ($report->isDirty('admin_note') && !empty($report->admin_note)) {
            $this->createAdminNoteNotification($report);
        }
    }

    /**
     * Buat notifikasi untuk perubahan status
     */
    protected function createStatusNotification(Report $report, string $oldStatus, string $newStatus): void
    {
        $statusMessages = [
            Report::STATUS_PENDING => [
                'title' => 'Laporan Diterima',
                'message' => "Laporan Anda \"{$report->title}\" telah diterima dan menunggu verifikasi admin.",
            ],
            Report::STATUS_PROCESS => [
                'title' => 'Laporan Dalam Proses',
                'message' => "Laporan \"{$report->title}\" sedang dalam proses penanganan oleh tim kami.",
            ],
            Report::STATUS_DONE => [
                'title' => 'Laporan Selesai ✓',
                'message' => "Laporan \"{$report->title}\" telah diselesaikan. Terima kasih atas partisipasi Anda!",
            ],
            Report::STATUS_REJECTED => [
                'title' => 'Laporan Ditolak',
                'message' => "Laporan \"{$report->title}\" tidak dapat diproses. " . ($report->admin_note ? "Alasan: {$report->admin_note}" : ''),
            ],
        ];

        $data = $statusMessages[$newStatus] ?? [
            'title' => 'Update Laporan',
            'message' => "Status laporan \"{$report->title}\" telah diperbarui.",
        ];

        Notification::create([
            'user_id' => $report->user_id,
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);
    }

    /**
     * Buat notifikasi untuk catatan admin baru
     */
    protected function createAdminNoteNotification(Report $report): void
    {
        Notification::create([
            'user_id' => $report->user_id,
            'title' => '💬 Catatan dari Admin',
            'message' => "Admin memberikan catatan pada laporan \"{$report->title}\": {$report->admin_note}",
            'is_read' => false,
        ]);
    }
}
