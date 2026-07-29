<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $failureReason;

    public function __construct(Booking $booking, string $failureReason = 'Payment authorization failed.')
    {
        $this->booking = $booking->loadMissing(['customer', 'car']);
        $this->failureReason = $failureReason;
    }

    public function envelope(): Envelope
    {
        $recipient = $this->booking->customer->email ?? null;
        if ($recipient) {
            try {
                EmailLoggerService::log(
                    emailType: 'PaymentFailed',
                    recipientEmail: $recipient,
                    subject: '⚠️ Payment Unsuccessful for Booking #' . $this->booking->booking_number,
                    customerId: $this->booking->customer_id,
                    bookingId: $this->booking->id,
                    status: 'Failed',
                    errorMessage: $this->failureReason
                );
            } catch (\Throwable $e) {
                // Ignore logging exception
            }
        }

        return new Envelope(
            subject: '⚠️ Payment Unsuccessful for Booking #' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failed',
        );
    }
}
