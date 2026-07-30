@extends('website.layout.structure')

@section('content')
<style>
  .dashboard-layout { display: flex; min-height: calc(100vh - 120px); }
  .dashboard-sidebar { width: 260px; background: var(--surface); border-right: 1px solid var(--border); padding: 1.5rem 1rem; flex-shrink: 0; }
  .dashboard-content { flex: 1; padding: 2rem; background: var(--surface-2); }
  .dash-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-secondary); border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; margin-bottom: 0.25rem; transition: var(--transition); }
  .dash-nav-item:hover, .dash-nav-item.active { background: var(--primary-lighter); color: var(--primary); }
</style>

<div class="dashboard-layout">
  <!-- SIDEBAR -->
  <aside class="dashboard-sidebar">
    <div class="text-center pb-3 mb-3 border-bottom">
      <img src="{{ $customer->profile_picture ? url($customer->profile_picture) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&q=80' }}" class="rounded-circle mb-2" style="width:70px;height:70px;object-fit:cover;">
      <h6 class="fw-800 font-heading mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
      <span class="badge bg-success-light text-success font-xs fw-700">Account {{ $customer->status ?? 'Active' }}</span>
    </div>

    <a href="{{url('/index')}}" class="dash-nav-item"><i class="fas fa-th-large"></i> Home</a>
    <a href="{{url('/my-bookings')}}" class="dash-nav-item active"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="{{url('/user_profile')}}" class="dash-nav-item"><i class="fas fa-user-circle"></i> My Profile</a>
    <a href="{{url('/cars')}}" class="dash-nav-item"><i class="fas fa-car-side"></i> Browse Cars</a>
    <a href="{{url('/user_logout')}}" class="dash-nav-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dashboard-content">
    <div class="max-w-900 mx-auto">
      
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <a href="{{ url('/my-bookings') }}" class="text-secondary font-sm text-decoration-none mb-2 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to My Bookings</a>
          <h3 class="fw-800 font-heading mb-0">Booking {{ $booking->booking_number }}</h3>
        </div>
        <div>
          @if(!in_array($booking->booking_status, ['Completed', 'Cancelled']))
          <button class="btn btn-outline-danger btn-sm fw-600" id="cancelBookingBtn"><i class="fas fa-times-circle me-1"></i> Cancel Reservation</button>
          @endif
          <button class="btn btn-danger btn-sm fw-700 ms-2" onclick="downloadInvoicePDF()"><i class="fas fa-download me-1"></i> Download PDF</button>
        </div>
      </div>

      <!-- INVOICE CONTENT TO BE CONVERTED TO PDF -->
      <div class="bg-surface p-4 rounded-card border shadow-xs mb-4" id="invoicePdfCard">
        
        <!-- BRAND HEADER FOR PDF -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
          <div>
            <h3 class="fw-800 text-primary mb-0">🚗 DriveEase Car Rental</h3>
            <span class="font-xs text-muted">Official Reservation Invoice & Receipt</span>
          </div>
          <div class="text-end">
            <span class="badge bg-primary-lighter text-primary fs-6 px-3 py-1 fw-700 mb-1 d-inline-block">{{ $booking->booking_status }}</span>
            <div class="font-xs text-muted">Invoice #: <strong>INV-{{ $booking->booking_number }}</strong></div>
          </div>
        </div>

        <div class="row g-4 mb-4 pb-3 border-bottom">
          <div class="col-md-6">
            <div class="font-xs text-muted fw-700 text-uppercase mb-2">Customer & Vehicle Details</div>
            <div class="fw-700 mb-1">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</div>
            <div class="font-xs text-muted mb-2">{{ $booking->customer->email }} • {{ $booking->customer->phone }}</div>
            
            <div class="d-flex align-items-center gap-3 mt-3 p-2 bg-surface-2 rounded border">
              <img src="{{ asset($booking->car->image) }}" class="rounded" style="width:90px;height:60px;object-fit:cover;">
              <div>
                <h6 class="fw-700 mb-1">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</h6>
                <div class="font-xs text-muted">{{ $booking->car->category->category_name ?? 'Vehicle' }} • {{ $booking->car->fuel_type }} • {{ $booking->car->transmission }}</div>
              </div>
            </div>
          </div>

          <div class="col-md-6 text-md-end">
            <div class="font-xs text-muted fw-700 text-uppercase mb-2">Total Amount & Payment</div>
            <h2 class="fw-800 text-primary mb-1">₹{{ number_format($booking->total_amount, 2) }}</h2>
            <div class="font-xs text-muted mb-2">Payment Status: <strong>{{ $booking->payment_status }}</strong></div>
            <div class="font-xs text-muted">Payment Method: <strong>{{ $booking->payment->payment_method ?? 'Razorpay' }}</strong></div>
          </div>
        </div>

        <div class="row g-4 font-sm">
          <div class="col-md-6">
            <div class="p-3 bg-surface-2 rounded border">
              <div class="fw-700 text-primary mb-2"><i class="fas fa-map-marker-alt me-1"></i> Pickup Branch</div>
              <div class="fw-600">{{ $booking->pickupLocation->name ?? 'Branch Pickup' }}</div>
              <div class="text-muted font-xs">{{ $booking->pickupLocation->address ?? '' }}</div>
              <div class="mt-2 font-xs text-secondary">Date: <strong>{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} at {{ $booking->pickup_time }}</strong></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 bg-surface-2 rounded border">
              <div class="fw-700 text-primary mb-2"><i class="fas fa-flag-checkered me-1"></i> Drop-off Branch</div>
              <div class="fw-600">{{ $booking->dropoffLocation->name ?? 'Branch Dropoff' }}</div>
              <div class="text-muted font-xs">{{ $booking->dropoffLocation->address ?? '' }}</div>
              <div class="mt-2 font-xs text-secondary">Date: <strong>{{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }} at {{ $booking->return_time }}</strong></div>
            </div>
          </div>
        </div>

        @if($booking->extraServices->isNotEmpty())
        <div class="mt-4 pt-3 border-top">
          <div class="fw-700 font-sm mb-2">Selected Add-on Services:</div>
          <ul class="list-group list-group-flush font-sm">
            @foreach($booking->extraServices as $extra)
            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
              <span><i class="fas fa-check-circle text-success me-2"></i> {{ $extra->name }}</span>
              <span class="fw-600">₹{{ number_format($extra->pivot->total, 2) }}</span>
            </li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="mt-4 pt-3 border-top">
          <div class="fw-700 font-sm mb-2">Financial Breakdown</div>
          <div class="price-row font-sm py-1"><span class="text-muted">Daily Vehicle Rate ({{ $booking->rental_days }} Days):</span><span class="fw-600">₹{{ number_format($booking->base_price, 2) }}</span></div>
          <div class="price-row font-sm py-1"><span class="text-muted">Extra Services:</span><span class="fw-600">₹{{ number_format($booking->extras_amount, 2) }}</span></div>
          @if($booking->discount_amount > 0)
          <div class="price-row font-sm py-1 text-danger"><span class="text-danger">Promo Discount:</span><span class="fw-600">-₹{{ number_format($booking->discount_amount, 2) }}</span></div>
          @endif
          <div class="price-row font-sm py-1"><span class="text-muted">GST Tax (18%):</span><span class="fw-600">₹{{ number_format($booking->tax_amount, 2) }}</span></div>
          <div class="price-row py-2 border-top fw-800 text-primary fs-5"><span>Grand Total Paid:</span><span>₹{{ number_format($booking->total_amount, 2) }}</span></div>
        </div>

        <div class="mt-4 pt-3 border-top text-center font-xs text-muted">
          DriveEase Car Rental Technologies • Customer Support: support@driveease.in | Phone: +91 1800-123-4567
        </div>
      </div>
    </div>
  </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  function downloadInvoicePDF() {
    const element = document.getElementById('invoicePdfCard');
    const opt = {
      margin:       0.3,
      filename:     'Invoice_{{ $booking->booking_number }}.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
  }

  $('#cancelBookingBtn').on('click', function() {
    const reason = prompt('Please specify cancellation reason:');
    if (reason === null) return;

    const $btn = $(this);
    const originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...');

    const csrfToken = '{{ csrf_token() }}';
    $.ajax({
      url: '/booking/{{ $booking->id }}/cancel',
      method: 'POST',
      data: {
        _token: csrfToken,
        reason: reason
      },
      success: function(res) {
        if (res.success) {
          alert(res.message);
          window.location.reload();
        } else {
          alert(res.message || 'Error cancelling booking.');
          $btn.prop('disabled', false).html(originalText);
        }
      },
      error: function(xhr) {
        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Error cancelling booking.');
        $btn.prop('disabled', false).html(originalText);
      }
    });
  });
</script>
@endsection
