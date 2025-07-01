<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Mã xác nhận đặt lại mật khẩu')
            ->view('auth.reset-code')
            ->with([
                'code' => $this->code,
                'link' => route('password.reset.form'),
            ]);
    }
}

