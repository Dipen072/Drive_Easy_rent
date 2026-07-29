@extends('emails.layouts.app')

@section('title', 'Welcome to DriveEase')

@section('content')
<h1 class="h1-title">Welcome to DriveEase, {{ $customer->first_name }}! 👋</h1>

<p>Thank you for creating an account with <strong>DriveEase Car Rental</strong>. We are thrilled to have you on board!</p>

<p>With your new account, you can effortlessly browse our premium fleet of SUVs, sedans, luxury, and electric vehicles across 10+ major Indian cities, book in minutes, and manage your reservations online.</p>

<div class="info-card">
  <div style="font-weight:700; color:#0d6efd; margin-bottom:8px;">Your Profile Details</div>
  <table class="table-details">
    <tr><td class="label">Full Name:</td><td class="value">{{ $customer->first_name }} {{ $customer->last_name }}</td></tr>
    <tr><td class="label">Registered Email:</td><td class="value">{{ $customer->email }}</td></tr>
    <tr><td class="label">Mobile Number:</td><td class="value">{{ $customer->phone }}</td></tr>
    <tr><td class="label">Driving License:</td><td class="value">{{ $customer->dl_number ?? 'Not Provided Yet' }}</td></tr>
  </table>
</div>

<p style="margin-top:24px;">Click below to log in and start exploring available cars for your next trip:</p>

<div style="text-align:center;">
  <a href="{{ url('/login') }}" class="btn-action">Log In to Your Account &rarr;</a>
</div>

<p style="margin-top:24px; font-size:13px; color:#64748b;">If you have any questions or need help with your registration, feel free to contact our customer support at <a href="mailto:support@driveease.in">support@driveease.in</a>.</p>
@endsection
