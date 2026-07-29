<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS Notification to Customer Mobile Number
     */
    public static function sendBookingSms(Booking $booking): SmsLog
    {
        $customer = $booking->customer;
        $car      = $booking->car;
        $phone    = $customer->phone ?? 'Mobile Number';
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        $carName  = $car ? ($car->brand_name . ' ' . $car->model_name) : 'Vehicle';
        $pickupLoc= $booking->pickupLocation->name ?? 'Pickup Branch';

        $message = sprintf(
            "Dear %s, your DriveEase Car Rental booking #%s for %s (%s to %s) is confirmed! Total: ₹%s. Pickup: %s. Support: +91 1800-123-4567.",
            $customer ? $customer->first_name : 'Customer',
            $booking->booking_number,
            $carName,
            \Carbon\Carbon::parse($booking->pickup_date)->format('d M'),
            \Carbon\Carbon::parse($booking->return_date)->format('d M Y'),
            number_format($booking->total_amount, 2),
            $pickupLoc
        );

        $status = 'Sent';
        $errorMsg = null;

        Log::info("[DriveEase SMS Dispatch] To: {$cleanPhone} | Msg: {$message}");

        $apiKey = env('SMS_GATEWAY_API_KEY');
        if ($apiKey) {
            try {
                // Fast2SMS Quick Transactional API Dispatch
                $response = Http::withHeaders([
                    'authorization' => $apiKey,
                ])->get('https://www.fast2sms.com/dev/bulkV2', [
                    'route'     => 'q',
                    'message'   => $message,
                    'language'  => 'english',
                    'flash'     => 0,
                    'numbers'   => $cleanPhone,
                ]);

                Log::info("[Fast2SMS API Response] " . $response->body());

                if (!$response->successful()) {
                    $errorMsg = $response->body();
                }
            } catch (\Throwable $e) {
                $errorMsg = $e->getMessage();
                Log::error("[Fast2SMS API Exception] " . $e->getMessage());
            }
        }

        return SmsLog::create([
            'customer_id'   => $booking->customer_id,
            'booking_id'    => $booking->id,
            'phone_number'  => $cleanPhone,
            'message'       => $message,
            'status'        => $status,
            'sent_at'       => now(),
            'error_message' => $errorMsg,
        ]);
    }

    /**
     * Send Cancellation SMS to Mobile Number
     */
    public static function sendCancellationSms(Booking $booking): SmsLog
    {
        $customer = $booking->customer;
        $car      = $booking->car;
        $phone    = $customer->phone ?? '';
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 12 && str_starts_with($cleanPhone, '91')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        $carName  = $car ? ($car->brand_name . ' ' . $car->model_name) : 'Vehicle';

        $message = sprintf(
            "Dear %s, your DriveEase reservation #%s for %s has been cancelled. Refund Status: %s. Support: +91 1800-123-4567.",
            $customer ? $customer->first_name : 'Customer',
            $booking->booking_number,
            $carName,
            $booking->payment && $booking->payment->payment_status === 'Refunded' ? 'Refund Processed' : 'N/A'
        );

        Log::info("[DriveEase SMS Cancellation] To: {$cleanPhone} | Msg: {$message}");

        $apiKey = env('SMS_GATEWAY_API_KEY');
        if ($apiKey) {
            try {
                Http::withHeaders([
                    'authorization' => $apiKey,
                ])->get('https://www.fast2sms.com/dev/bulkV2', [
                    'route'     => 'q',
                    'message'   => $message,
                    'language'  => 'english',
                    'flash'     => 0,
                    'numbers'   => $cleanPhone,
                ]);
            } catch (\Throwable $e) {
                Log::error("[Fast2SMS Cancellation Exception] " . $e->getMessage());
            }
        }

        return SmsLog::create([
            'customer_id'   => $booking->customer_id,
            'booking_id'    => $booking->id,
            'phone_number'  => $cleanPhone,
            'message'       => $message,
            'status'        => 'Sent',
            'sent_at'       => now(),
        ]);
    }
}
