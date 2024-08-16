<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $userData;

    /**
     * Create a new message instance.
     *
     * @param  string  $otpCode
     * @param  array   $userData
     * @return void
     */
    public function __construct($otpCode, $userData)
    {
        $this->otpCode = $otpCode;
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kode OTP')
            ->view('emails.otp_code')
            ->with([
                'otpCode' => $this->otpCode,
                'userData' => $this->userData,
            ]);
    }
}
