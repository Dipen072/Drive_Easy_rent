@extends('emails.layouts.app')

@section('title', 'New Booking Received #' . $booking->booking_number)

@section('content')
<h1 class="h1-title" style="color:#0d6efd;">🚨 New Car Rental Booking Received</h1>
<span class="badge-status badge-primary">Status: {{ $booking->booking_status }}</span>

<p style="margin-top:16px;">A new reservation <strong>#{{ $booking->booking_number }}</strong> has been placed on the DriveEase platform.</p>

<div class="info-card">
  <div style="font-weight:700; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-bottom:12px;">👤 Customer Information</div>
  <table class="table-details">
    <tr><td class="label">Customer Name:</td><td class="value">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</td></tr>
    <tr><td class="label">Email Address:</td><td class="value">{{ $booking->customer->email }}</td></tr>
    <tr><td class="label">Phone Number:</td><td class="value">{{ $booking->customer->phone }}</td></tr>
    <tr><td class="label">Driving License:</td><td class="value">{{ $booking->customer->dl_number ?? 'N/A' }}</td></tr>
  </table>

  <div style="font-weight:700; color:#1e293b; border-bottom:1px solid #e2e8f0; padding-bottom:8px; margin-top:16px; margin-bottom:12px;">🚗 Reservation Details</div>
  <table class="table-details">
    <tr><td class="label">Car Rented:</td><td class="value">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</td></tr>
    <tr><td class="label">Pickup Location:</td><td class="value">{{ $booking->pickupLocation->name ?? 'Branch Pickup' }}</td></tr>
    <tr><td class="label">Drop-off Location:</td><td class="value">{{ $booking->dropoffLocation->name ?? 'Branch Dropoff' }}</td></tr>
    <tr><td class="label">Rental Dates:</td><td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} &rarr; {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }} ({{ $booking->rental_days }} Days)</td></tr>
    <tr><td class="label">Total Amount:</td><td class="value" style="color:#0d6efd; font-size:16px;">₹{{ number_format($booking->total_amount, 2) }}</td></tr>
    <tr><td class="label">Payment Status:</td><td class="value">{{ $booking->payment_status }} ({{ $booking->payment->payment_method ?? 'Offline' }})</td></tr>
  </table>
</div>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/admin/bookings') }}" class="btn-action">View Booking in Admin Panel &rarr;</a>
</div>
@endsection
