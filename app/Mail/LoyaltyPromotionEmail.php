<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoyaltyPromotionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $tier;
    public $points;
    public $promotion;

    public function __construct($username, $tier, $points, $promotion = null)
    {
        $this->username = $username;
        $this->tier = $tier;
        $this->points = $points;
        $this->promotion = $promotion;
    }

    public function build()
    {
        return $this->subject('Ưu đãi đặc biệt dành riêng cho bạn từ Travela! 🎁')
            ->view('clients.mail.loyalty_promotion');
    }
}
