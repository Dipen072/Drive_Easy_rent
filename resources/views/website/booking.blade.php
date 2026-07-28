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
        <form id="checkoutForm" novalidate>
          
          <!-- STEP 1: CUSTOMER INFORMATION -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs" id="step1">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-user-circle text-primary me-2"></i>Step 1: Driver Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" class="form-control" id="custName" required placeholder="e.g. Arjun Sharma">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" class="form-control" id="custEmail" required placeholder="arjun@example.com">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number *</label>
                <input type="tel" class="form-control" id="custPhone" required placeholder="+91 98765 43210">
              </div>
              <div class="col-md-6">
                <label class="form-label">Driving License Number *</label>
                <input type="text" class="form-control" id="custLicense" required placeholder="MH-0120230012345">
              </div>
              <div class="col-12">
                <label class="form-label">Street Address *</label>
                <input type="text" class="form-control" id="custAddress" required placeholder="Apartment / House / Street">
              </div>
              <div class="col-md-4">
                <label class="form-label">City *</label>
                <input type="text" class="form-control" id="custCity" required placeholder="Mumbai">
              </div>
              <div class="col-md-4">
                <label class="form-label">State *</label>
                <input type="text" class="form-control" id="custState" required placeholder="Maharashtra">
              </div>
              <div class="col-md-4">
                <label class="form-label">ZIP / Postal Code *</label>
                <input type="text" class="form-control" id="custZip" required placeholder="400001">
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
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Pickup Branch *</label>
                <select id="rentPickupLoc" class="form-select" required></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Drop-off Branch *</label>
                <select id="rentDropLoc" class="form-select" required></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Pickup Date *</label>
                <input type="text" class="form-control" id="rentPickupDate" required readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Return Date *</label>
                <input type="text" class="form-control" id="rentReturnDate" required readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label">Pickup Time *</label>
                <select class="form-select" id="rentPickupTime">
                  <option value="09:00">09:00 AM</option>
                  <option value="10:00" selected>10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="14:00">02:00 PM</option>
                  <option value="18:00">06:00 PM</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Return Time *</label>
                <select class="form-select" id="rentReturnTime">
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
              <div class="p-3 border rounded d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="fas fa-shield-halved text-success fs-3"></i>
                  <div>
                    <div class="fw-700 font-sm">Full Comprehensive Insurance</div>
                    <div class="text-muted font-xs">Zero excess coverage for accidental damages & theft protection</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="fw-700 text-primary">₹500 / day</span>
                  <div class="form-check form-switch">
                    <input class="form-check-input extra-toggle" type="checkbox" data-name="Insurance" data-price="500" id="extraIns">
                  </div>
                </div>
              </div>

              <div class="p-3 border rounded d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="fas fa-baby-carriage text-warning fs-3"></i>
                  <div>
                    <div class="fw-700 font-sm">Child Safety Seat</div>
                    <div class="text-muted font-xs">Suitable for infants and toddlers up to 4 years</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="fw-700 text-primary">₹300 / day</span>
                  <div class="form-check form-switch">
                    <input class="form-check-input extra-toggle" type="checkbox" data-name="Child Seat" data-price="300" id="extraSeat">
                  </div>
                </div>
              </div>

              <div class="p-3 border rounded d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="fas fa-user-plus text-info fs-3"></i>
                  <div>
                    <div class="fw-700 font-sm">Additional Driver Permission</div>
                    <div class="text-muted font-xs">Allow second driver to legally operate the vehicle</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="fw-700 text-primary">₹400 / day</span>
                  <div class="form-check form-switch">
                    <input class="form-check-input extra-toggle" type="checkbox" data-name="Additional Driver" data-price="400" id="extraDriver">
                  </div>
                </div>
              </div>

              <div class="p-3 border rounded d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <i class="fas fa-wifi text-primary fs-3"></i>
                  <div>
                    <div class="fw-700 font-sm">Portable Wi-Fi Hotspot</div>
                    <div class="text-muted font-xs">High-speed 4G data coverage for up to 5 devices</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <span class="fw-700 text-primary">₹250 / day</span>
                  <div class="form-check form-switch">
                    <input class="form-check-input extra-toggle" type="checkbox" data-name="Wi-Fi Hotspot" data-price="250" id="extraWifi">
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)"><i class="fas fa-arrow-left me-1"></i> Back</button>
              <button type="button" class="btn btn-primary-brand" onclick="nextStep(3)">Next: Payment <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
          </div>

          <!-- STEP 4: PAYMENT -->
          <div class="step-content bg-surface p-4 rounded-card border shadow-xs d-none" id="step4">
            <h5 class="fw-700 font-heading mb-3"><i class="fas fa-credit-card text-primary me-2"></i>Step 4: Select Payment Method</h5>
            
            <div class="row g-2 mb-4">
              <div class="col-6 col-md-3">
                <div class="payment-tab-btn active" data-method="Credit Card">
                  <i class="fas fa-credit-card fs-5 text-primary"></i> Card
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="payment-tab-btn" data-method="UPI">
                  <i class="fas fa-mobile-alt fs-5 text-success"></i> UPI / QR
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="payment-tab-btn" data-method="PayPal">
                  <i class="fab fa-paypal fs-5 text-info"></i> PayPal
                </div>
              </div>
              <div class="col-6 col-md-3">
                <div class="payment-tab-btn" data-method="Cash">
                  <i class="fas fa-money-bill-wave fs-5 text-warning"></i> Cash
                </div>
              </div>
            </div>

            <!-- Card Form UI -->
            <div id="payFormCard" class="p-3 border rounded bg-surface-2 mb-3">
              <div class="mb-3">
                <label class="form-label font-sm">Cardholder Name</label>
                <input type="text" class="form-control form-control-sm" placeholder="e.g. Arjun Sharma">
              </div>
              <div class="mb-3">
                <label class="form-label font-sm">Card Number</label>
                <input type="text" class="form-control form-control-sm" placeholder="4532 •••• •••• 8921">
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label font-sm">Expiry Date</label>
                  <input type="text" class="form-control form-control-sm" placeholder="MM / YY">
                </div>
                <div class="col-6">
                  <label class="form-label font-sm">CVV</label>
                  <input type="password" class="form-control form-control-sm" maxlength="4" placeholder="•••">
                </div>
              </div>
            </div>

            <!-- UPI UI -->
            <div id="payFormUPI" class="p-3 border rounded bg-surface-2 mb-3 d-none text-center">
              <i class="fas fa-qrcode fs-1 text-primary mb-2"></i>
              <p class="font-sm text-secondary mb-1">Scan or Enter VPA (Google Pay / PhonePe / Paytm)</p>
              <input type="text" class="form-control form-control-sm mx-auto" style="max-width:300px;" placeholder="username@upi">
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
                <i class="fas fa-lock me-2"></i> Confirm & Pay <span id="btnPayTotal">₹0</span>
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
            <img src="" id="sumCarImg" class="rounded" style="width:90px;height:60px;object-fit:cover;">
            <div>
              <h6 class="fw-700 font-sm mb-1" id="sumCarTitle">Selected Car</h6>
              <span class="badge bg-primary-lighter text-primary font-xs" id="sumCarCat">Category</span>
            </div>
          </div>

          <div class="price-row">
            <span class="label">Rental Duration</span>
            <span class="fw-600" id="sumDays">0 Days</span>
          </div>
          <div class="price-row">
            <span class="label">Base Vehicle Rate</span>
            <span class="fw-600" id="sumBasePrice">₹0</span>
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
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script src="{{url('website/js/booking.js')}}"></script>
@endsection
