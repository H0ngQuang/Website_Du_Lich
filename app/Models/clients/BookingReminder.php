<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BookingReminder extends Model
{
    use HasFactory;

    protected $table = 'tbl_booking_reminders';
    protected $primaryKey = 'reminderId';
    public $timestamps = false;

    const MAX_REMINDERS = 3;

    /**
     * Lấy bookings chưa thanh toán cần nhắc nhở (> 2 giờ, chưa gửi đủ 3 lần)
     */
    public function getAbandonedBookings()
    {
        return DB::table('tbl_booking')
            ->join('tbl_checkout', 'tbl_booking.bookingId', '=', 'tbl_checkout.bookingId')
            ->join('tbl_tours', 'tbl_booking.tourId', '=', 'tbl_tours.tourId')
            ->leftJoin('tbl_booking_reminders', function ($join) {
                $join->on('tbl_booking.bookingId', '=', 'tbl_booking_reminders.bookingId');
            })
            ->where('tbl_checkout.paymentStatus', 'n')
            ->where('tbl_booking.bookingStatus', '!=', 'c') // Không nhắc booking đã hủy
            ->where('tbl_booking.bookingDate', '<=', now()->subHours(2))
            ->select(
                'tbl_booking.*',
                'tbl_checkout.paymentMethod',
                'tbl_checkout.amount',
                'tbl_tours.title as tourTitle',
                'tbl_tours.destination',
                'tbl_tours.time',
                DB::raw('COALESCE(MAX(tbl_booking_reminders.reminder_count), 0) as total_reminders')
            )
            ->groupBy(
                'tbl_booking.bookingId',
                'tbl_booking.tourId',
                'tbl_booking.userId',
                'tbl_booking.address',
                'tbl_booking.fullName',
                'tbl_booking.email',
                'tbl_booking.numAdults',
                'tbl_booking.numChildren',
                'tbl_booking.phoneNumber',
                'tbl_booking.totalPrice',
                'tbl_booking.bookingDate',
                'tbl_booking.bookingStatus',
                'tbl_checkout.paymentMethod',
                'tbl_checkout.amount',
                'tbl_tours.title',
                'tbl_tours.destination',
                'tbl_tours.time'
            )
            ->havingRaw('total_reminders < ?', [self::MAX_REMINDERS])
            ->get();
    }

    /**
     * Ghi nhận đã gửi reminder
     */
    public function recordReminder($bookingId, $type = 'email')
    {
        // Lấy reminder count hiện tại
        $currentCount = DB::table($this->table)
            ->where('bookingId', $bookingId)
            ->max('reminder_count') ?? 0;

        return DB::table($this->table)->insert([
            'bookingId' => $bookingId,
            'reminder_type' => $type,
            'sent_at' => now(),
            'reminder_count' => $currentCount + 1
        ]);
    }

    /**
     * Lấy số lần đã nhắc cho booking
     */
    public function getReminderCount($bookingId)
    {
        return DB::table($this->table)
            ->where('bookingId', $bookingId)
            ->max('reminder_count') ?? 0;
    }
}
