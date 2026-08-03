<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingExtra;
use App\Models\Car;
use App\Models\Coupon;
use App\Models\ExtraService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    protected PaymentService $paymentService;
    protected CouponService $couponService;

    public function __construct(PaymentService $paymentService, CouponService $couponService)
    {
        $this->paymentService = $paymentService;
        $this->couponService  = $couponService;
    }

    /**
     * Calculate booking costs on backend
     */
    public function calculatePricing(
        int $carId,
        string $pickupDate,
        string $returnDate,
        array $extraServiceIds = [],
        ?string $couponCode = null
    ): array {
        $car = Car::findOrFail($carId);

        $start = Carbon::parse($pickupDate);
        $end   = Carbon::parse($returnDate);
        $days  = max(1, $start->diffInDays($end));

        $basePrice = $car->rate_per_day * $days;

        $extrasAmount = 0;
        $selectedExtras = [];

        if (!empty($extraServiceIds)) {
            $services = ExtraService::whereIn('id', $extraServiceIds)
                ->where('status', 'Active')
                ->get();

            foreach ($services as $service) {
                $serviceTotal = $service->price_per_day * $days;
                $extrasAmount += $serviceTotal;
                $selectedExtras[] = [
                    'id'            => $service->id,
                    'name'          => $service->name,
                    'price_per_day' => (float) $service->price_per_day,
                    'total'         => (float) $serviceTotal,
                ];
            }
        }

        $subtotal = $basePrice + $extrasAmount;

        $discountAmount = 0;
        $couponId = null;
        $couponData = null;

        if ($couponCode) {
            $couponResult = $this->couponService->validateAndCalculate($couponCode, $subtotal);
            if ($couponResult['valid']) {
                $discountAmount = $couponResult['discount'];
                $couponId       = $couponResult['coupon']->id;
                $couponData     = $couponResult['coupon'];
            }
        }

        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount     = round($taxableAmount * 0.18, 2);
        $totalAmount   = round($subtotal + $taxAmount - $discountAmount, 2);

        return [
            'rental_days'     => $days,
            'rate_per_day'    => (float) $car->rate_per_day,
            'base_price'      => (float) $basePrice,
            'extras_amount'   => (float) $extrasAmount,
            'subtotal'        => (float) $subtotal,
            'discount_amount' => (float) $discountAmount,
            'tax_amount'      => (float) $taxAmount,
            'total_amount'    => (float) $totalAmount,
            'coupon_id'       => $couponId,
            'coupon'          => $couponData,
            'selected_extras' => $selectedExtras,
        ];
    }

    /**
     * Create booking record inside DB transaction
     */
    public function createBooking(int $customerId, array $data): Booking
    {
        return DB::transaction(function () use ($customerId, $data) {
            $car = Car::findOrFail($data['car_id']);

            // Re-verify car availability inside transaction
            if (!$car->isAvailableForDates($data['pickup_date'], $data['return_date'])) {
                throw new Exception('Selected car is not available for the chosen dates.');
            }

            $extraServiceIds = $data['extra_services'] ?? [];
            $couponCode      = $data['coupon_code'] ?? null;

            $pricing = $this->calculatePricing(
                $car->id,
                $data['pickup_date'],
                $data['return_date'],
                $extraServiceIds,
                $couponCode
            );

            // Generate Booking Number CAR-2026-000001
            $bookingNumber = $this->generateBookingNumber();

            $bookingStatus = ($data['payment_method'] === 'Cash') ? 'Pending' : 'Confirmed';
            $paymentStatus = ($data['payment_method'] === 'Cash') ? 'Pending' : 'Paid';

            $booking = Booking::create([
                'booking_number'      => $bookingNumber,
                'customer_id'         => $customerId,
                'car_id'              => $car->id,
                'pickup_location_id'  => $data['pickup_location_id'] ?? null,
                'dropoff_location_id' => $data['dropoff_location_id'] ?? null,
                'pickup_address'      => $data['pickup_address'] ?? null,
                'pickup_lat'          => $data['pickup_lat'] ?? null,
                'pickup_lng'          => $data['pickup_lng'] ?? null,
                'dropoff_address'     => $data['dropoff_address'] ?? null,
                'dropoff_lat'         => $data['dropoff_lat'] ?? null,
                'dropoff_lng'         => $data['dropoff_lng'] ?? null,
                'pickup_date'         => $data['pickup_date'],
                'return_date'         => $data['return_date'],
                'pickup_time'         => $data['pickup_time'] ?? '10:00',
                'return_time'         => $data['return_time'] ?? '10:00',
                'rental_days'         => $pricing['rental_days'],
                'base_price'          => $pricing['base_price'],
                'extras_amount'       => $pricing['extras_amount'],
                'discount_amount'     => $pricing['discount_amount'],
                'tax_amount'          => $pricing['tax_amount'],
                'total_amount'        => $pricing['total_amount'],
                'coupon_id'           => $pricing['coupon_id'],
                'payment_status'      => $paymentStatus,
                'booking_status'      => $bookingStatus,
                'confirmed_at'        => $bookingStatus === 'Confirmed' ? now() : null,
            ]);

            // Save booking extras with locked prices
            if (!empty($extraServiceIds)) {
                $services = ExtraService::whereIn('id', $extraServiceIds)->get();
                foreach ($services as $service) {
                    $total = $service->price_per_day * $pricing['rental_days'];
                    BookingExtra::create([
                        'booking_id'       => $booking->id,
                        'extra_service_id' => $service->id,
                        'quantity'         => 1,
                        'price'            => $service->price_per_day,
                        'total'            => $total,
                    ]);
                }
            }

            // Update coupon usage count
            if ($pricing['coupon_id']) {
                Coupon::where('id', $pricing['coupon_id'])->increment('used_count');
            }

            // Process Payment with Razorpay payment ID if provided
            $txnId = $data['razorpay_payment_id'] ?? null;
            $this->paymentService->processPayment($booking, $data['payment_method'], $pricing['total_amount'], $txnId);

            return $booking;
        });
    }

    /**
     * Generate sequential Booking Number
     */
    protected function generateBookingNumber(): string
    {
        $year = now()->format('Y');
        $lastBooking = Booking::orderBy('id', 'desc')->first();
        $nextId = $lastBooking ? ($lastBooking->id + 1) : 1;

        return sprintf('CAR-%s-%06d', $year, $nextId);
    }
}
