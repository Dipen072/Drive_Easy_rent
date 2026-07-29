@extends('emails.layouts.app')

@section('title', 'Admin Notice: Booking Cancelled #' . $booking->booking_number)

@section('content')
<h1 class="h1-title" style="color:#991b1b;">⚠️ Reservation Cancelled by Customer</h1>
<span class="badge-status badge-danger">Cancelled</span>

<p style="margin-top:16px;">Reservation <strong>#{{ $booking->booking_number }}</strong> has been cancelled by the customer.</p>

<div class="info-card">
  <div style="font-weight:700; color:#1e293b; margin-bottom:12px;">Cancellation Overview</div>
  <table class="table-details">
    <tr><td class="label">Booking Number:</td><td class="value">{{ $booking->booking_number }}</td></tr>
    <tr><td class="label">Customer Name:</td><td class="value">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</td></tr>
    <tr><td class="label">Customer Contact:</td><td class="value">{{ $booking->customer->email }} • {{ $booking->customer->phone }}</td></tr>
    <tr><td class="label">Car Rented:</td><td class="value">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</td></tr>
    <tr><td class="label">Cancellation Reason:</td><td class="value">{{ $booking->cancellation_reason ?? 'Cancelled by customer' }}</td></tr>
    <tr><td class="label">Total Amount:</td><td class="value">₹{{ number_format($booking->total_amount, 2) }}</td></tr>
    <tr><td class="label">Refund Status:</td><td class="value">{{ $booking->payment && $booking->payment->payment_status === 'Refunded' ? 'Refund Processed' : 'N/A' }}</td></tr>
  </table>
</div>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/admin/bookings') }}" class="btn-action">Manage Reservations in Admin Panel &rarr;</a>
</div>
@endsection
