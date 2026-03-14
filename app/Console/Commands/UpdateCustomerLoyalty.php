<?php

namespace App\Console\Commands;

use App\Models\clients\CustomerLoyalty;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCustomerLoyalty extends Command
{
    protected $signature = 'loyalty:update-tiers';
    protected $description = 'Cập nhật tier loyalty cho tất cả khách hàng dựa trên tổng chi tiêu';

    public function handle()
    {
        $loyaltyModel = new CustomerLoyalty();

        // Lấy tất cả users có booking hoàn thành
        $usersWithBookings = DB::table('tbl_booking')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->where('tbl_booking.bookingStatus', 'f')
            ->select(
                'tbl_booking.userId',
                DB::raw('SUM(tbl_booking.totalPrice) as total_spent'),
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('MAX(tbl_booking.bookingDate) as last_booking_at')
            )
            ->groupBy('tbl_booking.userId')
            ->get();

        $updatedCount = 0;

        foreach ($usersWithBookings as $user) {
            $loyalty = $loyaltyModel->getOrCreate($user->userId);
            $newTier = $loyaltyModel->calculateTier($user->total_spent);
            $newPoints = floor($user->total_spent / CustomerLoyalty::POINTS_PER_UNIT);

            DB::table('tbl_customer_loyalty')
                ->where('userId', $user->userId)
                ->update([
                    'total_spent' => $user->total_spent,
                    'total_bookings' => $user->total_bookings,
                    'loyalty_tier' => $newTier,
                    'points' => $newPoints,
                    'last_booking_at' => $user->last_booking_at,
                    'updated_at' => now()
                ]);

            $updatedCount++;
            $this->info("Updated: User #{$user->userId} - Spent: " . number_format($user->total_spent) . " - Tier: {$newTier}");
        }

        $this->info("Hoàn tất! Đã cập nhật {$updatedCount} khách hàng.");
    }
}
