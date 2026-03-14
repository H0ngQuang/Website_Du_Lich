<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function build()
    {
        return $this->subject('Chào mừng bạn đến với Travela! 🌍')
            ->view('clients.mail.welcome');
    }
}
