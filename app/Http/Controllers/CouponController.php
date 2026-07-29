<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyCouponRequest;
use App\Services\CouponService;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Apply Coupon endpoint via AJAX
     */
    public function apply(ApplyCouponRequest $request): JsonResponse
    {
        $result = $this->couponService->validateAndCalculate(
            $request->code,
            (float) $request->subtotal
        );

        if ($result['valid']) {
            return response()->json([
                'success'  => true,
                'discount' => $result['discount'],
                'message'  => $result['message'],
                'code'     => strtoupper($request->code),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 400);
    }
}
