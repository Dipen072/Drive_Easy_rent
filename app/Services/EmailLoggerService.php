<?php

namespace App\Services;

use App\Models\EmailLog;
use Throwable;

class EmailLoggerService
{
    /**
     * Log email event to email_logs table
     */
    public static function log(
        string $emailType,
        string $recipientEmail,
        string $subject,
        ?int $customerId = null,
        ?int $bookingId = null,
        string $status = 'Sent',
        ?string $errorMessage = null
    ): EmailLog {
        return EmailLog::create([
            'customer_id'     => $customerId,
            'booking_id'      => $bookingId,
            'email_type'      => $emailType,
            'recipient_email' => $recipientEmail,
            'subject'         => $subject,
            'status'          => $status,
            'sent_at'         => $status === 'Sent' ? now() : null,
            'error_message'   => $errorMessage,
        ]);
    }
}
