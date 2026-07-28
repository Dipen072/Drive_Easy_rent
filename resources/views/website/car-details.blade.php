@extends('website.layout.structure')

@section('content')
<section class="section-py bg-surface-2">
  <div class="container">
    
    <!-- BREADCRUMB & HEADER -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{url('/index')}}" class="text-decoration-none text-muted">Home</a></li>
          <li class="breadcrumb-item"><a href="{{url('/cars')}}" class="text-decoration-none text-muted">Cars</a></li>
          <li class="breadcrumb-item active fw-600 text-primary" id="breadCarName">Car Details</li>
        </ol>
      </nav>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" id="shareBtn" title="Share"><i class="fas fa-share-alt me-1"></i> Share</button>
        <button class="btn btn-outline-secondary btn-sm" id="wishlistToggleBtn" title="Wishlist"><i class="far fa-heart me-1"></i> Wishlist</button>
      </div>
    </div>

    <!-- CAR HEADER TITLE -->
    <div class="bg-surface p-4 rounded-card border mb-4 shadow-xs">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <span class="badge bg-primary-lighter text-primary fw-700 px-3 py-1 rounded-pill mb-2" id="carCategoryBadge">Category</span>
          <h2 class="fw-800 font-heading mb-1" id="carTitle">Car Title</h2>
          <div class="d-flex align-items-center gap-3 text-muted font-sm">
            <span><i class="fas fa-map-marker-alt text-primary me-1"></i><span id="carLocation">City</span></span>
            <span><i class="fas fa-star text-accent me-1"></i><strong class="text-dark" id="carRating">4.8</strong> (<span id="carReviewCount">0</span> reviews)</span>
            <span class="badge-available" id="carAvailBadge">Available</span>
          </div>
        </div>
        <div class="text-lg-end">
          <div class="text-muted font-sm">Price Starts At</div>
          <div class="display-6 font-heading fw-800 text-primary" id="carPriceDisplay">₹0 <span class="fs-6 text-muted font-body">/ day</span></div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      
      <!-- LEFT COLUMN: GALLERY & SPECIFICATIONS -->
      <div class="col-lg-8">
        
        <!-- Gallery -->
        <div class="bg-surface p-3 rounded-card border shadow-xs mb-4">
          <div class="gallery-main mb-2">
            <img id="mainGalleryImg" src="" alt="Car Main View">
          </div>
          <div class="gallery-thumbs" id="galleryThumbs">
            <!-- Thumbs rendered by JS -->
          </div>
        </div>

        <!-- Spec Grid -->
        <div class="bg-surface p-4 rounded-card border shadow-xs mb-4">
          <h5 class="fw-700 font-heading mb-3"><i class="fas fa-sliders text-primary me-2"></i>Key Specifications</h5>
          <div class="specs-grid" id="specsGrid">
            <!-- Rendered by JS -->
          </div>
        </div>

        <!-- Details Tabs -->
        <div class="bg-surface p-4 rounded-card border shadow-xs mb-4">
          <ul class="nav nav-tabs tabs-brand mb-3" id="carTab" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview">Overview</button></li>
            <li class="nav-item"><button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features">Features</button></li>
            <li class="nav-item"><button class="nav-link" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy">Rental Policy</button></li>
            <li class="nav-item"><button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews">Reviews (<span id="tabReviewCount">0</span>)</button></li>
          </ul>
          <div class="tab-content" id="carTabContent">
            
            <!-- Overview -->
            <div class="tab-pane fade show active" id="overview">
              <p class="text-secondary leading-relaxed" id="carDescription">Car description...</p>
              <h6 class="fw-700 mt-4 mb-3">Rental Pricing Tier</h6>
              <div class="table-responsive">
                <table class="table table-bordered table-sm text-center">
                  <thead class="table-light">
                    <tr><th>Daily Rate</th><th>Weekly Rate (per day)</th><th>Monthly Rate (per day)</th><th>Security Deposit</th></tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="fw-700 text-primary" id="rateDaily">₹0</td>
                      <td class="fw-600" id="rateWeekly">₹0</td>
                      <td class="fw-600" id="rateMonthly">₹0</td>
                      <td class="text-muted" id="rateDeposit">₹0</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Features -->
            <div class="tab-pane fade" id="features">
              <div class="row row-cols-2 row-cols-md-3 g-3" id="featuresList">
                <!-- Rendered by JS -->
              </div>
            </div>

            <!-- Policy -->
            <div class="tab-pane fade" id="policy">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success"></i> Minimum driving age: 21 years with valid original license.</li>
                <li class="list-group-item d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success"></i> 300 km free allowance per day. Extra km at ₹15/km.</li>
                <li class="list-group-item d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success"></i> Security deposit refundable upon return within 5-7 business days.</li>
                <li class="list-group-item d-flex align-items-center gap-2"><i class="fas fa-check-circle text-success"></i> Free cancellation up to 48 hours prior to pickup.</li>
              </ul>
            </div>

            <!-- Reviews -->
            <div class="tab-pane fade" id="reviews">
              <div id="reviewsContainer">
                <!-- Rendered by JS -->
              </div>
            </div>

          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: BOOKING WIDGET -->
      <div class="col-lg-4">
        <div class="price-summary-box sticky-top" style="top:90px;">
          <h5 class="fw-700 font-heading mb-3"><i class="fas fa-calendar-check text-primary me-2"></i>Book This Vehicle</h5>
          
          <div class="mb-3">
            <label class="form-label">Pickup Location</label>
            <select id="bookPickup" class="form-select"></select>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Pickup Date</label>
              <input type="text" id="bookPDate" class="form-control" readonly>
            </div>
            <div class="col-6">
              <label class="form-label">Return Date</label>
              <input type="text" id="bookRDate" class="form-control" readonly>
            </div>
          </div>

          <!-- Price Calculation Breakdown -->
          <div class="border-top pt-3 mt-3">
            <div class="price-row">
              <span class="label">Daily Rate</span>
              <span class="fw-600" id="calcDailyRate">₹0</span>
            </div>
            <div class="price-row">
              <span class="label">Rental Duration</span>
              <span class="fw-600" id="calcDuration">1 Day</span>
            </div>
            <div class="price-row">
              <span class="label">Estimated GST (18%)</span>
              <span class="fw-600" id="calcTax">₹0</span>
            </div>
            <div class="price-row total">
              <span>Total Estimated</span>
              <span class="text-primary fs-5" id="calcTotal">₹0</span>
            </div>
          </div>

          <button class="btn btn-primary-brand w-100 btn-lg-brand mt-4" id="proceedToBookBtn">
            Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
          </button>
          
          <p class="text-center font-sm text-muted mt-3 mb-0">
            <i class="fas fa-shield-alt text-success me-1"></i> Free Cancellation up to 48 hours
          </p>
        </div>
      </div>

    </div>

    <!-- RELATED CARS -->
    <div class="mt-5">
      <h4 class="fw-700 font-heading mb-4">Similar <span class="text-primary-brand">Vehicles</span></h4>
      <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-4" id="relatedCarsGrid">
        <!-- Rendered by JS -->
      </div>
    </div>

  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script src="{{url('website/js/car-details.js')}}"></script>
@endsection
