@extends('emails.layouts.app')

@section('title', 'Inquiry Received - DriveEase')

@section('content')
<h1 class="h1-title">Thank You for Contacting Us, {{ $contact->name }}! 👋</h1>

<p>We have successfully received your inquiry. Our support team is reviewing your message and will get back to you as soon as possible.</p>

<div class="info-card">
  <div style="font-weight:700; color:#0d6efd; margin-bottom:8px;">Summary of Your Inquiry</div>
  <table class="table-details">
    <tr><td class="label">Name:</td><td class="value">{{ $contact->name }}</td></tr>
    <tr><td class="label">Email:</td><td class="value">{{ $contact->email }}</td></tr>
    <tr><td class="label">Phone:</td><td class="value">{{ $contact->phone ?? 'N/A' }}</td></tr>
    <tr><td class="label">Subject:</td><td class="value">{{ $contact->subject }}</td></tr>
    <tr>
      <td class="label">Message:</td>
      <td class="value" style="white-space: pre-line;">{{ $contact->message }}</td>
    </tr>
    <tr><td class="label">Submitted At:</td><td class="value">{{ $contact->created_at ? $contact->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</td></tr>
  </table>
</div>

<p style="margin-top:24px;">If you have any additional details or urgent questions, feel free to reply directly to this email or call our 24/7 helpline at <strong>1800-123-4567</strong>.</p>

<div style="text-align:center; margin-top:24px;">
  <a href="{{ url('/contact') }}" class="btn-action">Visit Help Center &rarr;</a>
</div>
@endsection
