<?php

namespace App\Mail;

use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $otp;
    public string $customerName;
    public string $email;

    public function __construct(int $otp, string $customerName, string $email)
    {
        $this->otp          = $otp;
        $this->customerName = $customerName;
        $this->email        = $email;
    }

    public function envelope(): Envelope
    {
        try {
            EmailLoggerService::log(
                emailType: 'PasswordResetOtp',
                recipientEmail: $this->email,
                subject: 'DriveEase — Password Reset OTP Code: ' . $this->otp
            );
        } catch (\Throwable $e) {
            // Ignore logging exception
        }

        return new Envelope(
            subject: 'DriveEase — Password Reset OTP Code: ' . $this->otp,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-otp',
        );
    }
}
