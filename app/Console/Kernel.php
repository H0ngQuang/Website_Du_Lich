<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Gửi nhắc nhở tour đã xem nhưng chưa mua (chạy hàng ngày lúc 9h sáng)
        $schedule->command('tour:send-reminders')->dailyAt('09:00');

        // Gửi nhắc nhở booking chưa thanh toán (chạy mỗi 2 giờ)
        $schedule->command('booking:send-abandoned-reminders')->everyTwoHours();

        // Gửi ưu đãi cho khách hàng cũ (chạy hàng tuần vào thứ 2)
        $schedule->command('loyalty:send-promotions')->weeklyOn(1, '10:00');

        // Cập nhật tier loyalty (chạy hàng ngày lúc 0h)
        $schedule->command('loyalty:update-tiers')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

