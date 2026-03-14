<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TourView extends Model
{
    use HasFactory;

    protected $table = 'tbl_tour_views';
    public $timestamps = false;

    /**
     * Ghi nhận lượt xem tour
     */
    public function recordView($userId, $tourId)
    {
        // Kiểm tra xem đã có record xem tour này chưa (trong 24h gần nhất)
        $exists = DB::table($this->table)
            ->where('userId', $userId)
            ->where('tourId', $tourId)
            ->where('viewed_at', '>=', now()->subHours(24))
            ->exists();

        if (!$exists) {
            return DB::table($this->table)->insert([
                'userId' => $userId,
                'tourId' => $tourId,
                'viewed_at' => now(),
                'converted' => 0,
                'reminder_sent' => 0
            ]);
        }

        return false;
    }

    /**
     * Đánh dấu đã mua tour
     */
    public function markConverted($userId, $tourId)
    {
        return DB::table($this->table)
            ->where('userId', $userId)
            ->where('tourId', $tourId)
            ->update(['converted' => 1]);
    }

    /**
     * Lấy danh sách tour đã xem nhưng chưa mua (> 24h) và chưa gửi reminder
     */
    public function getUnconvertedViews()
    {
        return DB::table($this->table)
            ->join('tbl_tours', 'tbl_tour_views.tourId', '=', 'tbl_tours.tourId')
            ->join('tbl_users', 'tbl_tour_views.userId', '=', 'tbl_users.userId')
            ->where('tbl_tour_views.converted', 0)
            ->where('tbl_tour_views.reminder_sent', 0)
            ->where('tbl_tour_views.viewed_at', '<=', now()->subHours(24))
            ->where('tbl_tours.availability', 1)
            ->select(
                'tbl_tour_views.*',
                'tbl_tours.title',
                'tbl_tours.destination',
                'tbl_tours.priceAdult',
                'tbl_tours.priceChild',
                'tbl_tours.time',
                'tbl_tours.description',
                'tbl_users.email',
                'tbl_users.username'
            )
            ->get();
    }

    /**
     * Đánh dấu đã gửi reminder
     */
    public function markReminderSent($viewId)
    {
        return DB::table($this->table)
            ->where('viewId', $viewId)
            ->update(['reminder_sent' => 1]);
    }

    /**
     * Lấy tour xem gần đây của user
     */
    public function getRecentViews($userId, $limit = 5)
    {
        return DB::table($this->table)
            ->join('tbl_tours', 'tbl_tour_views.tourId', '=', 'tbl_tours.tourId')
            ->where('tbl_tour_views.userId', $userId)
            ->where('tbl_tours.availability', 1)
            ->orderByDesc('tbl_tour_views.viewed_at')
            ->select('tbl_tours.*', 'tbl_tour_views.viewed_at', 'tbl_tour_views.converted')
            ->limit($limit)
            ->get();
    }
}
