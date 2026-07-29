<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function validateAndCalculate(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return [
                'valid'    => false,
                'discount' => 0,
                'message'  => 'Invalid coupon code.',
            ];
        }

        return $coupon->isValidForSubtotal($subtotal);
    }
}
