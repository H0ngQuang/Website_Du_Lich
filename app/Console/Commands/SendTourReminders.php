<?php

namespace App\Console\Commands;

use App\Mail\TourReminderEmail;
use App\Models\clients\TourView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTourReminders extends Command
{
    protected $signature = 'tour:send-reminders';
    protected $description = 'Gửi email nhắc nhở cho khách đã xem tour nhưng chưa mua (sau 24h)';

    public function handle()
    {
        $tourView = new TourView();
        $unconvertedViews = $tourView->getUnconvertedViews();

        if ($unconvertedViews->isEmpty()) {
            $this->info('Không có tour view nào cần nhắc nhở.');
            return;
        }

        $sentCount = 0;

        // Group by user để không gửi spam nhiều email
        $grouped = $unconvertedViews->groupBy('userId');

        foreach ($grouped as $userId => $views) {
            // Chỉ gửi cho tour gần nhất đã xem
            $latestView = $views->sortByDesc('viewed_at')->first();

            try {
                Mail::to($latestView->email)->send(
                    new TourReminderEmail($latestView->username, $latestView)
                );

                // Đánh dấu tất cả views của user này đã gửi reminder
                foreach ($views as $view) {
                    $tourView->markReminderSent($view->viewId);
                }

                $sentCount++;
                $this->info("Đã gửi reminder cho: {$latestView->email} - Tour: {$latestView->title}");
            } catch (\Exception $e) {
                $this->error("Lỗi gửi email cho {$latestView->email}: {$e->getMessage()}");
            }
        }

        $this->info("Hoàn tất! Đã gửi {$sentCount} email nhắc nhở tour.");
    }
}
