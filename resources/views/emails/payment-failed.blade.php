@extends('emails.layouts.app')

@section('title', 'Payment Failed #' . $booking->booking_number)

@section('content')
<h1 class="h1-title" style="color:#dc2626;">⚠️ Payment Unsuccessful</h1>
<span class="badge-status badge-danger">Payment Failed</span>

<p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>

<p>We were unable to process your payment of <strong>₹{{ number_format($booking->total_amount, 2) }}</strong> for booking <strong>#{{ $booking->booking_number }}</strong>.</p>

<div class="info-card" style="background-color:#fff5f5; border-color:#fecaca;">
  <div style="font-weight:700; color:#991b1b; margin-bottom:12px;">Payment Failure Information</div>
  <table class="table-details">
    <tr><td class="label">Booking Number:</td><td class="value">{{ $booking->booking_number }}</td></tr>
    <tr><td class="label">Attempted Amount:</td><td class="value">₹{{ number_format($booking->total_amount, 2) }}</td></tr>
    <tr><td class="label">Failure Reason:</td><td class="value" style="color:#dc2626;">{{ $failureReason }}</td></tr>
  </table>
</div>

<p>Your vehicle reservation is currently pending. Please click below to retry the payment to confirm your booking before the reservation expires.</p>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/booking?id=' . $booking->car_id) }}" class="btn-action">Retry Payment Now &rarr;</a>
</div>
@endsection
