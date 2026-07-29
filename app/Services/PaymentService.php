<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class PaymentService
{
    /**
     * Process payment for a booking with Razorpay or Cash support
     */
    public function processPayment(Booking $booking, string $paymentMethod, float $amount, ?string $transactionId = null): Payment
    {
        $status = 'Pending';
        $gateway = 'Razorpay';

        if ($paymentMethod === 'Cash') {
            $status = 'Pending';
            $gateway = 'Offline';
            $transactionId = $transactionId ?? ('CASH-' . strtoupper(uniqid()));
        } else {
            // Razorpay / Online Payment
            $status = 'Paid';
            $gateway = 'Razorpay';
            $transactionId = $transactionId ?? ('pay_' . strtoupper(bin2hex(random_bytes(7))));
        }

        $payment = Payment::create([
            'booking_id'      => $booking->id,
            'customer_id'     => $booking->customer_id,
            'transaction_id'  => $transactionId,
            'payment_method'  => $paymentMethod === 'Cash' ? 'Cash' : 'Credit Card',
            'amount'          => $amount,
            'currency'        => 'INR',
            'payment_status'  => $status,
            'payment_gateway' => $gateway,
            'paid_at'         => $status === 'Paid' ? now() : null,
        ]);

        return $payment;
    }
}
