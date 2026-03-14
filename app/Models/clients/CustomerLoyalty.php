<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerLoyalty extends Model
{
    use HasFactory;

    protected $table = 'tbl_customer_loyalty';
    protected $primaryKey = 'loyaltyId';

    // Tier thresholds (VND)
    const TIER_BRONZE = 'bronze';       // 0+
    const TIER_SILVER = 'silver';       // >= 5,000,000
    const TIER_GOLD = 'gold';           // >= 20,000,000
    const TIER_PLATINUM = 'platinum';   // >= 50,000,000

    const SILVER_THRESHOLD = 5000000;
    const GOLD_THRESHOLD = 20000000;
    const PLATINUM_THRESHOLD = 50000000;

    // Points: 1 point per 10,000 VND spent
    const POINTS_PER_UNIT = 10000;

    /**
     * Lấy hoặc tạo loyalty record cho user
     */
    public function getOrCreate($userId)
    {
        $loyalty = DB::table($this->table)->where('userId', $userId)->first();

        if (!$loyalty) {
            $id = DB::table($this->table)->insertGetId([
                'userId' => $userId,
                'total_spent' => 0,
                'total_bookings' => 0,
                'loyalty_tier' => self::TIER_BRONZE,
                'points' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $loyalty = DB::table($this->table)->where('loyaltyId', $id)->first();
        }

        return $loyalty;
    }

    /**
     * Cập nhật sau khi booking hoàn thành
     */
    public function addBookingPoints($userId, $totalPrice)
    {
        $loyalty = $this->getOrCreate($userId);
        $newPoints = floor($totalPrice / self::POINTS_PER_UNIT);
        $newTotalSpent = $loyalty->total_spent + $totalPrice;
        $newTotalBookings = $loyalty->total_bookings + 1;
        $newTier = $this->calculateTier($newTotalSpent);

        return DB::table($this->table)
            ->where('userId', $userId)
            ->update([
                'total_spent' => $newTotalSpent,
                'total_bookings' => $newTotalBookings,
                'points' => $loyalty->points + $newPoints,
                'loyalty_tier' => $newTier,
                'last_booking_at' => now(),
                'updated_at' => now()
            ]);
    }

    /**
     * Tính tier dựa trên tổng chi tiêu
     */
    public function calculateTier($totalSpent)
    {
        if ($totalSpent >= self::PLATINUM_THRESHOLD) return self::TIER_PLATINUM;
        if ($totalSpent >= self::GOLD_THRESHOLD) return self::TIER_GOLD;
        if ($totalSpent >= self::SILVER_THRESHOLD) return self::TIER_SILVER;
        return self::TIER_BRONZE;
    }

    /**
     * Cập nhật tier cho tất cả users
     */
    public function updateAllTiers()
    {
        $allLoyalty = DB::table($this->table)->get();
        foreach ($allLoyalty as $loyalty) {
            $newTier = $this->calculateTier($loyalty->total_spent);
            if ($newTier !== $loyalty->loyalty_tier) {
                DB::table($this->table)
                    ->where('loyaltyId', $loyalty->loyaltyId)
                    ->update([
                        'loyalty_tier' => $newTier,
                        'updated_at' => now()
                    ]);
            }
        }
    }

    /**
     * Lấy khách hàng cũ cần gửi ưu đãi (booking hoàn thành > 30 ngày, chưa gửi promotion gần đây)
     */
    public function getCustomersForPromotion()
    {
        return DB::table($this->table)
            ->join('tbl_users', 'tbl_customer_loyalty.userId', '=', 'tbl_users.userId')
            ->where('tbl_customer_loyalty.total_bookings', '>', 0)
            ->where('tbl_customer_loyalty.last_booking_at', '<=', now()->subDays(30))
            ->where(function ($q) {
                $q->whereNull('tbl_customer_loyalty.last_promotion_sent_at')
                    ->orWhere('tbl_customer_loyalty.last_promotion_sent_at', '<=', now()->subDays(14));
            })
            ->select('tbl_customer_loyalty.*', 'tbl_users.email', 'tbl_users.username')
            ->get();
    }

    /**
     * Đánh dấu đã gửi promotion
     */
    public function markPromotionSent($userId)
    {
        return DB::table($this->table)
            ->where('userId', $userId)
            ->update([
                'last_promotion_sent_at' => now(),
                'updated_at' => now()
            ]);
    }

    /**
     * Lấy top khách hàng theo chi tiêu
     */
    public function getTopCustomers($limit = 20)
    {
        return DB::table($this->table)
            ->join('tbl_users', 'tbl_customer_loyalty.userId', '=', 'tbl_users.userId')
            ->orderByDesc('tbl_customer_loyalty.total_spent')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy lịch sử booking của user
     */
    public function getBookingHistory($userId)
    {
        return DB::table('tbl_booking')
            ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->where('tbl_booking.userId', $userId)
            ->orderByDesc('tbl_booking.bookingDate')
            ->get();
    }

    /**
     * Lấy thống kê theo tier
     */
    public function getTierStats()
    {
        return DB::table($this->table)
            ->select('loyalty_tier', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_spent) as total_revenue'))
            ->groupBy('loyalty_tier')
            ->get();
    }

    /**
     * Thông tin tier tiếp theo
     */
    public function getNextTierInfo($currentTier, $totalSpent)
    {
        $tiers = [
            self::TIER_BRONZE => ['next' => self::TIER_SILVER, 'threshold' => self::SILVER_THRESHOLD],
            self::TIER_SILVER => ['next' => self::TIER_GOLD, 'threshold' => self::GOLD_THRESHOLD],
            self::TIER_GOLD => ['next' => self::TIER_PLATINUM, 'threshold' => self::PLATINUM_THRESHOLD],
            self::TIER_PLATINUM => ['next' => null, 'threshold' => 0],
        ];

        $info = $tiers[$currentTier] ?? $tiers[self::TIER_BRONZE];
        if ($info['next']) {
            $info['remaining'] = max(0, $info['threshold'] - $totalSpent);
            $info['progress'] = $info['threshold'] > 0 ? min(100, round(($totalSpent / $info['threshold']) * 100)) : 100;
        } else {
            $info['remaining'] = 0;
            $info['progress'] = 100;
        }

        return $info;
    }
}
