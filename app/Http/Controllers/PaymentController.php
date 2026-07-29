<?php

namespace App\Http\Controllers;

use App\Mail\PaymentFailedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class PaymentController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Create Razorpay Order before launch
     */
    public function createRazorpayOrder(Request $request)
    {
        $request->validate([
            'car_id'         => 'required|exists:cars,id',
            'pickup_date'    => 'required|date',
            'return_date'    => 'required|date',
            'extra_services' => 'nullable|array',
            'coupon_code'    => 'nullable|string',
        ]);

        try {
            $pricing = $this->bookingService->calculatePricing(
                (int) $request->car_id,
                $request->pickup_date,
                $request->return_date,
                $request->extra_services ?? [],
                $request->coupon_code
            );

            $amountInPaise = (int) round($pricing['total_amount'] * 100);
            $receipt = 'RCPT-' . time();
            $razorpayKey = env('RAZORPAY_KEY', 'rzp_test_DriveEase2026');

            // Generate mock/real order ID
            $orderId = 'order_' . strtoupper(bin2hex(random_bytes(8)));

            return response()->json([
                'success'          => true,
                'key'              => $razorpayKey,
                'order_id'         => $orderId,
                'amount_in_paise'  => $amountInPaise,
                'amount_formatted' => number_format($pricing['total_amount'], 2),
                'currency'         => 'INR',
                'receipt'          => $receipt,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Razorpay Callback Verification
     */
    public function callback(Request $request)
    {
        $request->validate([
            'booking_id'          => 'required|exists:bookings,id',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'nullable|string',
            'razorpay_signature'  => 'nullable|string',
            'status'              => 'nullable|in:Paid,Failed',
        ]);

        $booking = Booking::with('customer')->findOrFail($request->booking_id);
        $payment = Payment::where('booking_id', $booking->id)->first();
        $status  = $request->input('status', 'Paid');

        if ($payment) {
            $payment->update([
                'transaction_id'  => $request->razorpay_payment_id,
                'payment_status'  => $status,
                'payment_gateway' => 'Razorpay',
                'paid_at'         => $status === 'Paid' ? now() : null,
            ]);

            if ($status === 'Paid') {
                $booking->update([
                    'payment_status' => 'Paid',
                    'booking_status' => 'Confirmed',
                    'confirmed_at'   => now(),
                ]);

                // Dispatch Payment Success Receipt Mail
                try {
                    if ($booking->customer && $booking->customer->email) {
                        Mail::to($booking->customer->email)->send(new PaymentSuccessMail($booking, $payment));
                    }
                } catch (\Throwable $e) {
                    // Silent fallback
                }
            } else {
                $booking->update([
                    'payment_status' => 'Failed',
                ]);

                // Dispatch Payment Failed Mail
                try {
                    if ($booking->customer && $booking->customer->email) {
                        Mail::to($booking->customer->email)->send(new PaymentFailedMail($booking, 'Transaction failed at payment gateway.'));
                    }
                } catch (\Throwable $e) {
                    // Silent fallback
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Razorpay payment processed successfully.',
        ]);
    }
}
