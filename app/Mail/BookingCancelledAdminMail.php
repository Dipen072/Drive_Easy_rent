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

class BookingCancelledAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing(['customer', 'car', 'payment']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Reservation Cancelled by Customer: ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-cancelled-admin',
        );
    }

    public function __destruct()
    {
        try {
            $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@driveease.in'));
            EmailLoggerService::log(
                emailType: 'BookingCancelledAdmin',
                recipientEmail: $adminEmail,
                subject: '⚠️ Reservation Cancelled by Customer: ' . $this->booking->booking_number,
                customerId: $this->booking->customer_id,
                bookingId: $this->booking->id
            );
        } catch (\Throwable $e) {
            // Ignore destruct logging error
        }
    }
}
