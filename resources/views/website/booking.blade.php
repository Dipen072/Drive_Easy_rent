@extends('website.layout.structure')

@section('content')
<!-- STEP WIZARD HEADER -->
<div class="bg-surface border-bottom py-4">
  <div class="container">
    <div class="step-wizard" id="stepWizard">
      <div class="step-item active" data-step="1">
        <div class="step-circle">1</div>
        <div class="step-label">Personal Info</div>
      </div>
      <div class="step-item" data-step="2">
        <div class="step-circle">2</div>
        <div class="step-label">Rental Details</div>
      </div>
      <div class="step-item" data-step="3">
        <div class="step-circle">3</div>
        <div class="step-label">Extra Services</div>
      </div>
      <div class="step-item" data-step="4">
        <div class="step-circle">4</div>
        <div class="step-label">Payment</div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CHECKOUT SECTION -->
<section class="section-py bg-surface-2">
  <div class="container">
    <div class="row g-4">
      
      <!-- LEFT: MULTI-STEP FORM -->
      <div class="col-lg-8">
        <form id="checkoutForm" action="{{ url('/booking') }}" method="POST" novalidate>
          @csrf
          <input type="hidden" name="car_id" id="carId" value="{{ $car->id }}">
          <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="Razorpay">
          <input type="hidden" name="coupon_code" id="appliedCouponCode" value="">
          <input type="hidden" name="razorpay_payment_id" id="razorpayPaymentId" value="">
          <input type="hidden" name="razorpay_order_id" id="razorpayOrderId" value="">
          <input type="hidden" name="razorpay_signature" id="razorpaySignature" value="">
          
          <!-- STEP 1: CUSTOMER INFORMATION -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs" id="step1">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-user-circle text-primary me-2"></i>Step 1: Driver Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" class="form-control" name="full_name" id="custName" required placeholder="e.g. Arjun Sharma" value="{{ old('full_name', $customer ? ($customer->first_name . ' ' . $customer->last_name) : '') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" class="form-control" name="email" id="custEmail" required placeholder="arjun@example.com" value="{{ old('email', $customer ? $customer->email : '') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number *</label>
                <input type="tel" class="form-control" name="phone" id="custPhone" required placeholder="+91 98765 43210" value="{{ old('phone', $customer ? $customer->phone : '') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Driving License Number *</label>
                <input type="text" class="form-control" name="driving_license" id="custLicense" required placeholder="MH-0120230012345" value="{{ old('driving_license', $customer ? $customer->dl_number : '') }}">
              </div>
              <div class="col-12">
                <label class="form-label">Street Address *</label>
                <input type="text" class="form-control" name="address" id="custAddress" required placeholder="Apartment / House / Street" value="{{ old('address', $customer ? $customer->address : '') }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">City *</label>
                <input type="text" class="form-control" name="city" id="custCity" required placeholder="Mumbai" value="{{ old('city', $customer ? $customer->city : '') }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">State *</label>
                <input type="text" class="form-control" name="state" id="custState" required placeholder="Maharashtra" value="{{ old('state', $customer ? $customer->state : '') }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">ZIP / Postal Code *</label>
                <input type="text" class="form-control" name="zip" id="custZip" required placeholder="400001" value="{{ old('zip', $customer ? $customer->zip_code : '') }}">
              </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn btn-primary-brand" onclick="nextStep(1)">
                Next: Rental Details <i class="fas fa-arrow-right ms-1"></i>
              </button>
            </div>
          </div>

          <!-- STEP 2: RENTAL DETAILS -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs d-none" id="step2">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i>Step 2: Pickup & Return Setup</h5>
            
            <!-- LOCATION TYPE SELECTOR -->
            <div class="mb-4 p-2 bg-light rounded d-flex gap-2">
              <button type="button" class="btn btn-sm flex-fill fw-600 active-loc-type btn-primary" id="modeDoorstepBtn" onclick="switchLocationMode('doorstep')">
                <i class="fas fa-map-marker-alt me-1"></i> Doorstep Pickup / Any Address (Uber/Ola Style)
              </button>
              <button type="button" class="btn btn-sm flex-fill fw-600 btn-outline-secondary" id="modeBranchBtn" onclick="switchLocationMode('branch')">
                <i class="fas fa-building me-1"></i> Pickup from Branch Hub
              </button>
            </div>

            <!-- DOORSTEP PICKUP SECTION (REAL LOCATION SEARCH) -->
            <div id="doorstepLocationSection">
              <div class="row g-3">
                <!-- Pickup Address -->
                <div class="col-12">
                  <label class="form-label fw-600">Pickup Location / Address *</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white text-primary"><i class="fas fa-search-location"></i></span>
                    <input type="text" class="form-control" name="pickup_address" id="rentPickupAddress" required placeholder="Type any landmark, street, city or address (e.g. Bandra West, Mumbai)">
                    <button type="button" class="btn btn-outline-primary" id="btnGpsPickup" title="Use current GPS location">
                      <i class="fas fa-crosshairs me-1"></i> GPS
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnMapPickup" title="Select on map">
                      <i class="fas fa-map-marked-alt me-1"></i> Pin Map
                    </button>
                  </div>
                  <input type="hidden" name="pickup_lat" id="rentPickupLat">
                  <input type="hidden" name="pickup_lng" id="rentPickupLng">
                </div>

                <!-- Checkbox for Different Dropoff Address -->
                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="diffDropoffCheck" onchange="toggleDiffDropoff(this.checked)">
                    <label class="form-check-label text-muted font-sm" for="diffDropoffCheck">
                      Drop-off at a different location
                    </label>
                  </div>
                </div>

                <!-- Drop-off Address (Conditional) -->
                <div class="col-12 d-none" id="dropoffAddressContainer">
                  <label class="form-label fw-600">Drop-off Location / Address *</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white text-success"><i class="fas fa-flag-checkered"></i></span>
                    <input type="text" class="form-control" name="dropoff_address" id="rentDropAddress" placeholder="Type drop-off landmark, street or city">
                    <button type="button" class="btn btn-outline-success" id="btnGpsDrop" title="Use current GPS location">
                      <i class="fas fa-crosshairs me-1"></i> GPS
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnMapDrop" title="Select on map">
                      <i class="fas fa-map-marked-alt me-1"></i> Pin Map
                    </button>
                  </div>
                  <input type="hidden" name="dropoff_lat" id="rentDropLat">
                  <input type="hidden" name="dropoff_lng" id="rentDropLng">
                </div>
              </div>
            </div>

            <!-- BRANCH HUB SECTION (HIDDEN BY DEFAULT WHEN DOORSTEP ACTIVE) -->
            <div id="branchLocationSection" class="d-none">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Pickup Branch</label>
                  <select id="rentPickupLoc" name="pickup_location_id" class="form-select">
                    @foreach($locations as $loc)
                      <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Drop-off Branch</label>
                  <select id="rentDropLoc" name="dropoff_location_id" class="form-select">
                    @foreach($locations as $loc)
                      <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <!-- DATES & TIMES -->
            <div class="row g-3 mt-2">
              <div class="col-md-6">
                <label class="form-label">Pickup Date *</label>
                <input type="text" class="form-control" name="pickup_date" id="rentPickupDate" required readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Return Date *</label>
                <input type="text" class="form-control" name="return_date" id="rentReturnDate" required readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Pickup Time *</label>
                <select class="form-select" name="pickup_time" id="rentPickupTime">
                  <option value="09:00">09:00 AM</option>
                  <option value="10:00" selected>10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="14:00">02:00 PM</option>
                  <option value="18:00">06:00 PM</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Return Time *</label>
                <select class="form-select" name="return_time" id="rentReturnTime">
                  <option value="09:00">09:00 AM</option>
                  <option value="10:00" selected>10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="14:00">02:00 PM</option>
                  <option value="18:00">06:00 PM</option>
                </select>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)"><i class="fas fa-arrow-left me-1"></i> Back</button>
              <button type="button" class="btn btn-primary-brand" onclick="nextStep(2)">Next: Extra Services <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
          </div>

          <!-- STEP 3: EXTRAS -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs d-none" id="step3">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-plus-circle text-primary me-2"></i>Step 3: Select Optional Add-ons</h5>
            
            <div class="d-flex flex-column gap-3">
              @foreach($extraServices as $extra)
              <div class="p-3 border rounded d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="{{ $extra->icon_class ?? 'fas fa-shield-halved' }} text-primary fs-3"></i>
                  <div>
                    <div class="fw-700 font-sm">{{ $extra->name }}</div>
                    <div class="text-muted font-xs">{{ $extra->description }}</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="fw-700 text-primary">₹{{ number_format($extra->price_per_day, 0) }} / day</span>
                  <div class="form-check form-switch">
                    <input class="form-check-input extra-toggle" type="checkbox" name="extra_services[]" value="{{ $extra->id }}" data-name="{{ $extra->name }}" data-price="{{ $extra->price_per_day }}" id="extra_{{ $extra->id }}">
                  </div>
                </div>
              </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)"><i class="fas fa-arrow-left me-1"></i> Back</button>
              <button type="button" class="btn btn-primary-brand" onclick="nextStep(3)">Next: Payment <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
          </div>

          <!-- STEP 4: PAYMENT -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs d-none" id="step4">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-credit-card text-primary me-2"></i>Step 4: Select Payment Method</h5>
            
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="payment-tab-btn active p-3 border rounded-3 text-center cursor-pointer h-100" data-method="Razorpay">
                  <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="fas fa-bolt text-primary fs-4"></i>
                    <h6 class="fw-700 mb-0">Razorpay Online Payment</h6>
                  </div>
                  <p class="font-xs text-muted mb-0">Instant Payment via UPI, GPay, Credit/Debit Cards, NetBanking & Wallets</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="payment-tab-btn p-3 border rounded-3 text-center cursor-pointer h-100" data-method="Cash">
                  <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="fas fa-money-bill-wave text-warning fs-4"></i>
                    <h6 class="fw-700 mb-0">Pay Cash at Pickup</h6>
                  </div>
                  <p class="font-xs text-muted mb-0">Pay cash directly at the pickup branch upon key handover (Subject to Admin Approval)</p>
                </div>
              </div>
            </div>

            <!-- Razorpay Notice -->
            <div id="payFormRazorpay" class="p-3 border rounded-3 bg-primary-lighter border-primary mb-4">
              <div class="d-flex align-items-center gap-3">
                <i class="fas fa-shield-cat fs-2 text-primary"></i>
                <div>
                  <div class="fw-700 text-primary font-sm">Secure Razorpay Gateway Activated</div>
                  <div class="font-xs text-secondary">Clicking <strong>Confirm & Pay with Razorpay</strong> will open the secure payment checkout window to complete your reservation.</div>
                </div>
              </div>
            </div>

            <!-- Cash Notice -->
            <div id="payFormCash" class="p-3 border rounded-3 bg-warning-light border-warning mb-4 d-none">
              <div class="d-flex align-items-center gap-3">
                <i class="fas fa-hand-holding-dollar fs-2 text-warning"></i>
                <div>
                  <div class="fw-700 text-dark font-sm">Offline Cash Reservation</div>
                  <div class="font-xs text-secondary">Your booking request will be submitted as <strong>Pending</strong> for admin verification. You can pay cash at pickup.</div>
                </div>
              </div>
            </div>

            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="termsCheck" required>
              <label class="form-check-label font-sm text-muted" for="termsCheck">
                I agree to the <a href="{{url('/terms')}}" target="_blank">Terms & Conditions</a> and <a href="{{url('/cancellation-policy')}}" target="_blank">Cancellation Policy</a>.
              </label>
            </div>

            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-outline-secondary" onclick="prevStep(4)"><i class="fas fa-arrow-left me-1"></i> Back</button>
              <button type="submit" class="btn btn-success btn-lg-brand fw-700" id="confirmBookingBtn">
                <i class="fas fa-lock me-2"></i> Confirm & Pay with Razorpay <span id="btnPayTotal">₹0</span>
              </button>
            </div>
          </div>

        </form>
      </div>

      <!-- RIGHT: STICKY BOOKING SUMMARY -->
      <div class="col-lg-4">
        <div class="price-summary-box sticky-top" style="top:90px;">
          <h6 class="fw-700 font-heading mb-3 pb-2 border-bottom"><i class="fas fa-file-invoice me-2 text-primary"></i>Booking Summary</h6>
          
          <!-- Car Selected Preview -->
          <div class="d-flex gap-3 align-items-center mb-3 pb-3 border-bottom">
            <img src="{{ asset($car->image) }}" id="sumCarImg" class="rounded" style="width:90px;height:60px;object-fit:cover;" alt="{{ $car->brand_name }} {{ $car->model_name }}">
            <div>
              <h6 class="fw-700 font-sm mb-1" id="sumCarTitle">{{ $car->brand_name }} {{ $car->model_name }}</h6>
              <span class="badge bg-primary-lighter text-primary font-xs" id="sumCarCat">{{ $car->category->category_name ?? 'Vehicle' }}</span>
            </div>
          </div>

          <div class="price-row">
            <span class="label">Rental Duration</span>
            <span class="fw-600" id="sumDays">1 Day</span>
          </div>
          <div class="price-row">
            <span class="label">Base Vehicle Rate</span>
            <span class="fw-600" id="sumBasePrice">₹{{ number_format($car->rate_per_day) }}</span>
          </div>
          <div class="price-row">
            <span class="label">Extra Services</span>
            <span class="fw-600" id="sumExtras">₹0</span>
          </div>
          <div class="price-row">
            <span class="label">GST Tax (18%)</span>
            <span class="fw-600" id="sumTax">₹0</span>
          </div>
          <div class="price-row text-danger d-none" id="sumDiscountRow">
            <span class="label text-danger">Promo Discount</span>
            <span class="fw-600" id="sumDiscount">-₹0</span>
          </div>
          
          <!-- PROMO CODE INPUT -->
          <div class="mt-3 pt-3 border-top">
            <label class="form-label font-xs fw-700 text-muted">HAVE A PROMO CODE?</label>
            <div class="input-group">
              <input type="text" class="form-control form-control-sm text-uppercase" id="promoInput" placeholder="FIRSTRIDE">
              <button class="btn btn-outline-brand btn-sm" type="button" id="applyPromoBtn">Apply</button>
            </div>
            <div class="font-xs text-success mt-1 d-none" id="promoMsg"></div>
          </div>

          <div class="price-row total mt-3 pt-3 border-top">
            <span>Total Payable</span>
            <span class="text-primary fs-4" id="sumGrandTotal">₹0</span>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  window.carData = {
    id: {{ $car->id }},
    rate_per_day: {{ $car->rate_per_day }},
    brand_name: "{{ $car->brand_name }}",
    model_name: "{{ $car->model_name }}"
  };
</script>
<script src="{{url('website/js/booking.js')}}"></script>
@endsection
