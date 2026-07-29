<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing(['car.category', 'customer', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment']);
    }

    public function envelope(): Envelope
    {
        $recipient = $this->booking->customer->email ?? null;
        if ($recipient) {
            try {
                EmailLoggerService::log(
                    emailType: 'BookingConfirmation',
                    recipientEmail: $recipient,
                    subject: 'Reservation Confirmed #' . $this->booking->booking_number . ' — DriveEase',
                    customerId: $this->booking->customer_id,
                    bookingId: $this->booking->id
                );
            } catch (\Throwable $e) {
                // Ignore logging exception
            }
        }

        return new Envelope(
            subject: 'Reservation Confirmed #' . $this->booking->booking_number . ' — DriveEase',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }
}
