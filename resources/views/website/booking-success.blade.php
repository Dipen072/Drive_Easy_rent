@extends('website.layout.structure')

@section('content')
<section class="section-py">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- SUCCESS BANNER CARD -->
        <div class="bg-surface p-5 rounded-card border shadow-md text-center mb-4">
          <div class="success-checkmark" style="width:80px;height:80px;margin:0 auto 1.5rem;border-radius:50%;background:var(--success-light);color:var(--success);display:flex;align-items:center;justify-content:center;font-size:2.5rem;"><i class="fas fa-check"></i></div>
          <h2 class="fw-800 font-heading text-dark mb-2">Booking Confirmed!</h2>
          <p class="text-muted lead mb-3">Thank you for choosing DriveEase. Your reservation has been placed successfully.</p>
          <div class="d-inline-block bg-primary-lighter text-primary fw-700 px-4 py-2 rounded-pill font-mono fs-5">
            Booking ID: <span id="confBookingId">BK000</span>
          </div>
        </div>

        <!-- DETAILS CARD -->
        <div class="bg-surface p-4 rounded-card border shadow-xs mb-4">
          <h5 class="fw-700 font-heading border-bottom pb-3 mb-4"><i class="fas fa-receipt text-primary me-2"></i>Reservation Summary</h5>
          
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Renter Details</label>
              <div class="fw-700" id="confCustName">Customer Name</div>
              <div class="text-muted font-sm" id="confCustEmail">Email</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Rented Vehicle</label>
              <div class="fw-700 text-primary" id="confCarName">Car Name</div>
              <div class="text-muted font-sm" id="confDays">0 Days</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Pickup Location & Date</label>
              <div class="fw-600" id="confPickupLoc">Pickup Branch</div>
              <div class="text-muted font-sm" id="confPickupDate">Date Time</div>
            </div>
            <div class="col-md-6">
              <label class="font-xs fw-700 text-muted uppercase display-block mb-1">Return Location & Date</label>
              <div class="fw-600" id="confDropLoc">Return Branch</div>
              <div class="text-muted font-sm" id="confReturnDate">Date Time</div>
            </div>
          </div>

          <!-- PAYMENT BREAKDOWN -->
          <div class="border-top pt-3">
            <div class="price-row"><span class="label">Base Vehicle Charges</span><span id="confBase">₹0</span></div>
            <div class="price-row"><span class="label">Add-ons & Extras</span><span id="confExtras">₹0</span></div>
            <div class="price-row"><span class="label">GST Taxes</span><span id="confTax">₹0</span></div>
            <div class="price-row total"><span class="fw-700">Total Paid</span><span class="text-primary fs-5 fw-800" id="confTotal">₹0</span></div>
          </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <button class="btn btn-outline-brand" onclick="window.print()"><i class="fas fa-print me-2"></i>Download Invoice</button>
          <div class="d-flex gap-2">
            <a href="{{url('/index')}}" class="btn btn-primary-brand">Return Home</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script>
  $(document).ready(function() {
    if (typeof Storage !== 'undefined') {
      Storage.seed();
      const urlParams = new URLSearchParams(window.location.search);
      const bkId = urlParams.get('id') || 'BK001';
      const bk = Storage.getBookingById(bkId);

      if (bk) {
        $('#confBookingId').text(bk.id);
        $('#confCustName').text(bk.customerName);
        $('#confCarName').text(bk.carName);
        $('#confDays').text(`${bk.days} Days (${bk.paymentStatus})`);
        $('#confPickupLoc').text(bk.pickupName);
        $('#confPickupDate').text(`${bk.pickupDate} at ${bk.pickupTime}`);
        $('#confDropLoc').text(bk.dropoffName);
        $('#confReturnDate').text(`${bk.returnDate} at ${bk.returnTime}`);

        $('#confBase').text(`₹${bk.basePrice.toLocaleString()}`);
        $('#confExtras').text(`₹${bk.extras.toLocaleString()}`);
        $('#confTax').text(`₹${bk.tax.toLocaleString()}`);
        $('#confTotal').text(`₹${bk.total.toLocaleString()}`);
      }
    }
  });
</script>
@endsection
