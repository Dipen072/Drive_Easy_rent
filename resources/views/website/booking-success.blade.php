@extends('website.layout.structure')

@section('content')
<section class="section-py">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- SUCCESS BANNER CARD -->
        <div class="bg-surface p-5 rounded-card border shadow-md text-center mb-4">
          <div class="success-checkmark" style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:var(--success-light);color:var(--success);display:flex;align-items:center;justify-content:center;font-size:2.5rem;"><i class="fas fa-check"></i></div>
          <h2 class="fw-800 font-heading text-dark mb-2">Booking {{ $booking->booking_status === 'Confirmed' ? 'Confirmed!' : 'Submitted!' }}</h2>
          <p class="text-muted lead mb-3">Thank you, {{ $booking->customer->first_name }}! Your reservation has been placed successfully.</p>
          <div class="d-inline-block bg-primary-lighter text-primary fw-700 px-4 py-2 rounded-pill font-mono fs-5">
            Booking Number: <span id="confBookingId">{{ $booking->booking_number }}</span>
          </div>
        </div>

        <!-- DETAILS CARD TO CONVERT TO PDF -->
        <div class="bg-surface p-4 rounded-card border shadow-xs mb-4" id="successPdfCard">
          <!-- BRAND HEADER FOR PDF -->
          <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
              <h3 class="fw-800 text-primary mb-0">🚗 DriveEase Car Rental</h3>
              <span class="font-xs text-muted">Official Reservation Receipt</span>
            </div>
            <div class="text-end">
              <span class="badge bg-success-light text-success fs-6 px-3 py-1 fw-700 mb-1 d-inline-block">{{ $booking->booking_status }}</span>
              <div class="font-xs text-muted">Invoice #: <strong>INV-{{ $booking->booking_number }}</strong></div>
            </div>
          </div>

          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Renter Details</label>
              <div class="fw-700" id="confCustName">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</div>
              <div class="text-muted font-sm" id="confCustEmail">{{ $booking->customer->email }}</div>
              <div class="text-muted font-sm">{{ $booking->customer->phone }}</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Rented Vehicle</label>
              <div class="fw-700 text-primary" id="confCarName">{{ $booking->car->brand_name }} {{ $booking->car->model_name }}</div>
              <div class="text-muted font-sm" id="confDays">{{ $booking->rental_days }} Days (Payment: {{ $booking->payment_status }})</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Pickup Location & Date</label>
              <div class="fw-600" id="confPickupLoc">{{ $booking->pickupLocation->name ?? 'Branch Pickup' }}</div>
              <div class="text-muted font-sm" id="confPickupDate">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d, Y') }} at {{ $booking->pickup_time }}</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Return Location & Date</label>
              <div class="fw-600" id="confDropLoc">{{ $booking->dropoffLocation->name ?? 'Branch Dropoff' }}</div>
              <div class="text-muted font-sm" id="confReturnDate">{{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }} at {{ $booking->return_time }}</div>
            </div>
          </div>

          <!-- PAYMENT BREAKDOWN -->
          <div class="border-top pt-3">
            <div class="price-row"><span class="label">Base Vehicle Charges</span><span id="confBase">₹{{ number_format($booking->base_price, 2) }}</span></div>
            <div class="price-row"><span class="label">Add-ons & Extras</span><span id="confExtras">₹{{ number_format($booking->extras_amount, 2) }}</span></div>
            @if($booking->discount_amount > 0)
            <div class="price-row text-danger"><span class="label text-danger">Promo Discount</span><span id="confDiscount">-₹{{ number_format($booking->discount_amount, 2) }}</span></div>
            @endif
            <div class="price-row"><span class="label">GST Taxes (18%)</span><span id="confTax">₹{{ number_format($booking->tax_amount, 2) }}</span></div>
            <div class="price-row total"><span class="fw-700">Total Amount</span><span class="text-primary fs-5 fw-800" id="confTotal">₹{{ number_format($booking->total_amount, 2) }}</span></div>
          </div>

          <div class="mt-4 pt-3 border-top text-center font-xs text-muted">
            DriveEase Car Rental Technologies • Customer Support: support@driveease.in | Phone: +91 1800-123-4567
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <button class="btn btn-danger btn-sm-brand fw-700" onclick="downloadSuccessPDF()"><i class="fas fa-file-pdf me-2"></i>Download PDF</button>
          <div class="d-flex gap-2">
            <a href="{{url('/my-bookings')}}" class="btn btn-outline-primary">My Bookings</a>
            <a href="{{url('/index')}}" class="btn btn-primary-brand">Return Home</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
  function downloadSuccessPDF() {
    const element = document.getElementById('successPdfCard');
    const opt = {
      margin:       0.3,
      filename:     'Invoice_{{ $booking->booking_number }}.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2, useCORS: true },
      jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
  }
</script>
@endsection
