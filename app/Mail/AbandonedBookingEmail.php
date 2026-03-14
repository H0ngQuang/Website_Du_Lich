<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonedBookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $reminderCount;

    public function __construct($booking, $reminderCount = 1)
    {
        $this->booking = $booking;
        $this->reminderCount = $reminderCount;
    }

    public function build()
    {
        return $this->subject('Bạn có đơn đặt tour chưa hoàn tất tại Travela! ⏰')
            ->view('clients.mail.abandoned_booking');
    }
}
