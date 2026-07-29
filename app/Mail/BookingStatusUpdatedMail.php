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

class BookingStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(Booking $booking, string $oldStatus, string $newStatus)
    {
        $this->booking   = $booking->loadMissing(['customer', 'car', 'pickupLocation', 'dropoffLocation']);
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Status Updated to "' . $this->newStatus . '" #' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-status-updated',
        );
    }

    public function __destruct()
    {
        try {
            if ($this->booking && $this->booking->customer) {
                EmailLoggerService::log(
                    emailType: 'BookingStatusUpdated',
                    recipientEmail: $this->booking->customer->email,
                    subject: 'Reservation Status Updated to "' . $this->newStatus . '" #' . $this->booking->booking_number,
                    customerId: $this->booking->customer_id,
                    bookingId: $this->booking->id
                );
            }
        } catch (\Throwable $e) {
            // Ignore destruct logging error
        }
    }
}
