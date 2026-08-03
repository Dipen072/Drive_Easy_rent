<?php

namespace App\Mail;

use App\Models\Contact;
use App\Services\EmailLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        try {
            EmailLoggerService::log(
                emailType: 'ContactInquiry',
                recipientEmail: $this->contact->email,
                subject: 'We Have Received Your Inquiry — DriveEase (' . $this->contact->subject . ')'
            );
        } catch (\Throwable $e) {
            // Ignore logging exception
        }

        return new Envelope(
            subject: 'We Have Received Your Inquiry — DriveEase (' . $this->contact->subject . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry',
        );
    }
}
