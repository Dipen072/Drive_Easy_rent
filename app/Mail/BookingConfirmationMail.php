<?php

namespace App\Mail;

use App\Models\Booking;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing(['car.category', 'customer', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment']);
    }

    public function envelope(): Envelope
    {
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

    public function __destruct()
    {
        try {
            if ($this->booking && $this->booking->customer) {
                EmailLoggerService::log(
                    emailType: 'BookingConfirmation',
                    recipientEmail: $this->booking->customer->email,
                    subject: 'Reservation Confirmed #' . $this->booking->booking_number . ' — DriveEase',
                    customerId: $this->booking->customer_id,
                    bookingId: $this->booking->id
                );
            }
        } catch (\Throwable $e) {
            // Ignore destruct logging error
        }
    }
}
