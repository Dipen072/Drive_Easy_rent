@extends('website.layout.structure')

@section('content')
<section class="section-py">
  <div class="container max-w-800">
    <div class="bg-surface p-4 p-sm-5 rounded-card border shadow-xs">
      <h2 class="fw-800 font-heading mb-3">Cancellation & Refund Policy</h2>
      <p class="text-muted font-sm mb-4">Last Updated: July 2025</p>
      
      <div class="table-responsive mb-4">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr><th>Cancellation Timing</th><th>Refund Percentage</th></tr>
          </thead>
          <tbody>
            <tr><td>48+ Hours Before Pickup</td><td class="text-success fw-700">100% Full Refund</td></tr>
            <tr><td>24 to 48 Hours Before Pickup</td><td class="text-warning fw-700">50% Refund</td></tr>
            <tr><td>Less than 24 Hours Before Pickup</td><td class="text-danger fw-700">Non-Refundable</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
