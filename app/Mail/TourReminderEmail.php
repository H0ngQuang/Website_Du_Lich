<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TourReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $tour;

    public function __construct($username, $tour)
    {
        $this->username = $username;
        $this->tour = $tour;
    }

    public function build()
    {
        return $this->subject('Đừng bỏ lỡ tour ' . $this->tour->title . '! 🏖️')
            ->view('clients.mail.tour_reminder');
    }
}
