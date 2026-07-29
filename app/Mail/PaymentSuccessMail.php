<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public Payment $payment;

    public function __construct(Booking $booking, Payment $payment)
    {
        $this->booking = $booking->loadMissing(['customer', 'car']);
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        $recipient = $this->booking->customer->email ?? null;
        if ($recipient) {
            try {
                EmailLoggerService::log(
                    emailType: 'PaymentSuccess',
                    recipientEmail: $recipient,
                    subject: 'Payment Receipt #' . $this->payment->transaction_id . ' — DriveEase',
                    customerId: $this->booking->customer_id,
                    bookingId: $this->booking->id
                );
            } catch (\Throwable $e) {
                // Ignore logging exception
            }
        }

        return new Envelope(
            subject: 'Payment Receipt #' . $this->payment->transaction_id . ' — DriveEase',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success',
        );
    }
}
