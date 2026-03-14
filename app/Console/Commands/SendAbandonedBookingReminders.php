<?php

namespace App\Console\Commands;

use App\Mail\AbandonedBookingEmail;
use App\Models\clients\BookingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAbandonedBookingReminders extends Command
{
    protected $signature = 'booking:send-abandoned-reminders';
    protected $description = 'Gửi email nhắc nhở cho booking chưa thanh toán (sau 2 giờ, tối đa 3 lần)';

    public function handle()
    {
        $reminderModel = new BookingReminder();
        $abandonedBookings = $reminderModel->getAbandonedBookings();

        if ($abandonedBookings->isEmpty()) {
            $this->info('Không có booking chưa thanh toán nào cần nhắc nhở.');
            return;
        }

        $sentCount = 0;

        foreach ($abandonedBookings as $booking) {
            $currentCount = $reminderModel->getReminderCount($booking->bookingId);

            if ($currentCount >= BookingReminder::MAX_REMINDERS) {
                continue;
            }

            try {
                Mail::to($booking->email)->send(
                    new AbandonedBookingEmail($booking, $currentCount + 1)
                );

                $reminderModel->recordReminder($booking->bookingId, 'email');
                $sentCount++;

                $this->info("Đã gửi nhắc nhở lần " . ($currentCount + 1) . " cho booking #{$booking->bookingId} - {$booking->email}");
            } catch (\Exception $e) {
                $this->error("Lỗi gửi email cho booking #{$booking->bookingId}: {$e->getMessage()}");
            }
        }

        $this->info("Hoàn tất! Đã gửi {$sentCount} email nhắc nhở booking.");
    }
}
