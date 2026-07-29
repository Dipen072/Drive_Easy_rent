<?php

namespace App\Mail;

use App\Models\Customer;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function envelope(): Envelope
    {
        try {
            EmailLoggerService::log(
                emailType: 'WelcomeCustomer',
                recipientEmail: $this->customer->email,
                subject: 'Welcome to DriveEase — Your Account is Ready!',
                customerId: $this->customer->id
            );
        } catch (\Throwable $e) {
            // Ignore logging exception
        }

        return new Envelope(
            subject: 'Welcome to DriveEase — Your Account is Ready!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-customer',
        );
    }
}
