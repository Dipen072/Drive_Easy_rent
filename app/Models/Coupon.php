<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'minimum_booking_amount',
        'maximum_discount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'coupon_id');
    }

    public function isValidForSubtotal($subtotal): array
    {
        if ($this->status !== 'Active') {
            return ['valid' => false, 'message' => 'Coupon is inactive.'];
        }

        $today = now()->format('Y-m-d');
        if ($this->start_date && $today < $this->start_date->format('Y-m-d')) {
            return ['valid' => false, 'message' => 'Coupon is not yet active.'];
        }

        if ($this->end_date && $today > $this->end_date->format('Y-m-d')) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit has been reached.'];
        }

        if ($subtotal < $this->minimum_booking_amount) {
            return ['valid' => false, 'message' => 'Minimum booking amount of ₹' . number_format($this->minimum_booking_amount, 2) . ' required.'];
        }

        $discount = 0;
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;
            if ($this->maximum_discount !== null && $discount > $this->maximum_discount) {
                $discount = (float) $this->maximum_discount;
            }
        } else {
            $discount = (float) $this->discount_value;
        }

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return [
            'valid'    => true,
            'discount' => round($discount, 2),
            'message'  => 'Promo code applied successfully!',
            'coupon'   => $this,
        ];
    }
}
