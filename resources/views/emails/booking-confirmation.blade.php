@extends('emails.layouts.app')

@section('title', 'Reservation Confirmed #' . $booking->booking_number)

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center;">
  <h1 class="h1-title" style="margin:0;">Reservation Confirmed! 🎉</h1>
</div>
<div style="margin-top:8px;">
  <span class="badge-status badge-success">{{ $booking->booking_status }}</span>
</div>

<p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>

<p>Thank you for choosing DriveEase! Your car rental reservation <strong>#{{ $booking->booking_number }}</strong> has been successfully confirmed.</p>

<div class="info-card">
  <div style="font-weight:700; color:#0d6efd; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">🚗 Vehicle Details</div>
  <table class="table-details">
    <tr><td class="label">Vehicle Rented:</td><td class="value">{{ $booking->car->brand_name }} {{ $booking->car->model_name }} ({{ $booking->car->year }})</td></tr>
    <tr><td class="label">Category:</td><td class="value">{{ $booking->car->category->category_name ?? 'Vehicle' }}</td></tr>
    <tr><td class="label">Fuel / Transmission:</td><td class="value">{{ $booking->car->fuel_type }} • {{ $booking->car->transmission }}</td></tr>
  </table>

  <div style="font-weight:700; color:#0d6efd; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-top:16px; margin-bottom:12px;">📍 Rental Schedule & Locations</div>
  <table class="table-details">
    <tr><td class="label">Pickup Branch:</td><td class="value">{{ $booking->pickupLocation->name ?? 'Branch Pickup' }}</td></tr>
    <tr><td class="label">Pickup Date & Time:</td><td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} at {{ $booking->pickup_time }}</td></tr>
    <tr><td class="label">Drop-off Branch:</td><td class="value">{{ $booking->dropoffLocation->name ?? 'Branch Dropoff' }}</td></tr>
    <tr><td class="label">Return Date & Time:</td><td class="value">{{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }} at {{ $booking->return_time }}</td></tr>
    <tr><td class="label">Rental Duration:</td><td class="value">{{ $booking->rental_days }} Days</td></tr>
  </table>

  <div style="font-weight:700; color:#0d6efd; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-top:16px; margin-bottom:12px;">💳 Financial Summary</div>
  <table class="table-details">
    <tr><td class="label">Base Vehicle Price:</td><td class="value">₹{{ number_format($booking->base_price, 2) }}</td></tr>
    <tr><td class="label">Extra Services:</td><td class="value">₹{{ number_format($booking->extras_amount, 2) }}</td></tr>
    @if($booking->discount_amount > 0)
    <tr><td class="label" style="color:#dc2626;">Promo Discount:</td><td class="value" style="color:#dc2626;">-₹{{ number_format($booking->discount_amount, 2) }}</td></tr>
    @endif
    <tr><td class="label">GST Tax (18%):</td><td class="value">₹{{ number_format($booking->tax_amount, 2) }}</td></tr>
    <tr><td class="label" style="font-size:16px;">Grand Total:</td><td class="value" style="font-size:16px; color:#0d6efd;">₹{{ number_format($booking->total_amount, 2) }}</td></tr>
    <tr><td class="label">Payment Method:</td><td class="value">{{ $booking->payment->payment_method ?? 'Razorpay' }}</td></tr>
    <tr><td class="label">Payment Status:</td><td class="value">{{ $booking->payment_status }}</td></tr>
  </table>
</div>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/my-bookings/' . $booking->id) }}" class="btn-action">View Booking Details &rarr;</a>
  <a href="{{ url('/booking/' . $booking->booking_number . '/success') }}" class="btn-action btn-secondary">Download Invoice</a>
</div>
@endsection
