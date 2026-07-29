@extends('emails.layouts.app')

@section('title', 'Reservation Status Update #' . $booking->booking_number)

@section('content')
<h1 class="h1-title">Booking Status Update 🔔</h1>

@if($newStatus === 'Confirmed')
  <span class="badge-status badge-success">Confirmed</span>
  <p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>
  <p>Great news! Your car rental booking <strong>#{{ $booking->booking_number }}</strong> has been <strong>confirmed</strong> by our dispatch team.</p>
@elseif($newStatus === 'Active')
  <span class="badge-status badge-primary">Trip Active</span>
  <p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>
  <p>Your car rental <strong>#{{ $booking->booking_number }}</strong> is now <strong>Active</strong>! Wish you a safe and pleasant journey with DriveEase.</p>
@elseif($newStatus === 'Completed')
  <span class="badge-status badge-success">Trip Completed</span>
  <p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>
  <p>Your rental trip <strong>#{{ $booking->booking_number }}</strong> has been successfully <strong>completed</strong>. Thank you for choosing DriveEase!</p>
@elseif($newStatus === 'Cancelled')
  <span class="badge-status badge-danger">Cancelled</span>
  <p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>
  <p>Your reservation <strong>#{{ $booking->booking_number }}</strong> has been <strong>cancelled</strong>.</p>
@else
  <span class="badge-status badge-warning">{{ $newStatus }}</span>
  <p style="margin-top:16px;">Dear <strong>{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</strong>,</p>
  <p>The status of your reservation <strong>#{{ $booking->booking_number }}</strong> has been updated to <strong>{{ $newStatus }}</strong>.</p>
@endif

<div class="info-card">
  <div style="font-weight:700; color:#0d6efd; margin-bottom:12px;">Reservation Summary</div>
  <table class="table-details">
    <tr><td class="label">Booking Number:</td><td class="value">{{ $booking->booking_number }}</td></tr>
    <tr><td class="label">Vehicle:</td><td class="value">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</td></tr>
    <tr><td class="label">Pickup Branch:</td><td class="value">{{ $booking->pickupLocation->name ?? 'Branch Pickup' }}</td></tr>
    <tr><td class="label">Drop-off Branch:</td><td class="value">{{ $booking->dropoffLocation->name ?? 'Branch Dropoff' }}</td></tr>
    <tr><td class="label">Dates:</td><td class="value">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} &rarr; {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</td></tr>
    <tr><td class="label">Updated Status:</td><td class="value">{{ $newStatus }}</td></tr>
  </table>
</div>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/my-bookings/' . $booking->id) }}" class="btn-action">View Booking Details &rarr;</a>
</div>
@endsection
