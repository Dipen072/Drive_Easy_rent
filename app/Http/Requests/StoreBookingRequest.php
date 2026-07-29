<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id'              => 'required|exists:cars,id',
            'full_name'           => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'phone'               => 'required|string|max:20',
            'driving_license'     => 'required|string|max:100',
            'address'             => 'required|string|max:500',
            'city'                => 'required|string|max:100',
            'state'               => 'required|string|max:100',
            'zip'                 => 'required|string|max:20',
            'pickup_location_id'  => 'required|exists:locations,id',
            'dropoff_location_id' => 'required|exists:locations,id',
            'pickup_date'         => 'required|date|after_or_equal:today',
            'return_date'         => 'required|date|after:pickup_date',
            'pickup_time'         => 'required|string',
            'return_time'         => 'required|string',
            'extra_services'      => 'nullable|array',
            'extra_services.*'    => 'exists:extra_services,id',
            'coupon_code'         => 'nullable|string',
            'payment_method'      => 'required|in:Razorpay,Credit Card,UPI,PayPal,Cash',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_order_id'   => 'nullable|string',
            'razorpay_signature'  => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required'              => 'Please select a car for booking.',
            'pickup_date.after_or_equal'   => 'Pickup date cannot be in the past.',
            'return_date.after'            => 'Return date must be after pickup date.',
            'pickup_location_id.required'  => 'Please select a pickup branch.',
            'dropoff_location_id.required' => 'Please select a drop-off branch.',
            'payment_method.in'            => 'Invalid payment method selected.',
        ];
    }
}
