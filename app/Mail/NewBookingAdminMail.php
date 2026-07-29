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

class NewBookingAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing(['car', 'customer', 'pickupLocation', 'dropoffLocation', 'payment']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 New Car Rental Booking Received: ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-booking-admin',
        );
    }

    public function __destruct()
    {
        try {
            $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@driveease.in'));
            EmailLoggerService::log(
                emailType: 'NewBookingAdmin',
                recipientEmail: $adminEmail,
                subject: '🚨 New Car Rental Booking Received: ' . $this->booking->booking_number,
                customerId: $this->booking->customer_id,
                bookingId: $this->booking->id
            );
        } catch (\Throwable $e) {
            // Ignore destruct logging error
        }
    }
}
