@extends('emails.layouts.app')

@section('title', 'Booking Cancelled #' . $booking->booking_number)

@section('content')
<h1 class="h1-title" style="color:#991b1b;">Reservation Cancelled</h1>
<span class="badge-status badge-danger">Cancelled</span>

<p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>

<p>Your car rental reservation <strong>#{{ $booking->booking_number }}</strong> has been cancelled.</p>

<div class="info-card">
  <div style="font-weight:700; color:#991b1b; margin-bottom:12px;">Cancellation Summary</div>
  <table class="table-details">
    <tr><td class="label">Booking Number:</td><td class="value">{{ $booking->booking_number }}</td></tr>
    <tr><td class="label">Vehicle Rented:</td><td class="value">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</td></tr>
    <tr><td class="label">Pickup & Return:</td><td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} &rarr; {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</td></tr>
    <tr><td class="label">Cancellation Reason:</td><td class="value">{{ $booking->cancellation_reason ?? 'Cancelled by customer' }}</td></tr>
    <tr><td class="label">Refund Status:</td><td class="value" style="color:#059669;">{{ $booking->payment && $booking->payment->payment_status === 'Refunded' ? 'Refund Processed' : 'N/A' }}</td></tr>
  </table>
</div>

<p style="margin-top:24px;">If you cancelled by mistake or wish to choose a different car, you can browse available vehicles on our website anytime.</p>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/cars') }}" class="btn-action">Browse Available Cars &rarr;</a>
</div>
@endsection
