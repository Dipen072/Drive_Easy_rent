@extends('emails.layouts.app')

@section('title', 'Payment Receipt #' . $payment->transaction_id)

@section('content')
<h1 class="h1-title">Payment Receipt 🧾</h1>
<span class="badge-status badge-success">Payment Received</span>

<p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>

<p>Thank you! Your payment of <strong>₹{{ number_format($payment->amount, 2) }}</strong> for booking <strong>#{{ $booking->booking_number }}</strong> has been processed successfully.</p>

<div class="info-card">
  <div style="font-weight:700; color:#065f46; margin-bottom:12px;">Transaction Details</div>
  <table class="table-details">
    <tr><td class="label">Transaction ID:</td><td class="value">{{ $payment->transaction_id }}</td></tr>
    <tr><td class="label">Booking Number:</td><td class="value">{{ $booking->booking_number }}</td></tr>
    <tr><td class="label">Payment Gateway:</td><td class="value">{{ $payment->payment_gateway }}</td></tr>
    <tr><td class="label">Payment Method:</td><td class="value">{{ $payment->payment_method }}</td></tr>
    <tr><td class="label">Amount Paid:</td><td class="value" style="color:#065f46; font-size:16px;">₹{{ number_format($payment->amount, 2) }}</td></tr>
    <tr><td class="label">Payment Date:</td><td class="value">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i A') : now()->format('M d, Y H:i A') }}</td></tr>
  </table>
</div>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/booking/' . $booking->booking_number . '/success') }}" class="btn-action">Download Tax Invoice &rarr;</a>
</div>
@endsection
