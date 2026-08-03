@extends('website.layout.structure')

@section('content')

<!-- HERO SECTION -->
<section class="hero-section" id="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <div class="hero-badge mb-3"><i class="fas fa-shield-check me-2"></i>Trusted by 50,000+ customers across India</div>
          <h1 class="hero-title">Find Your <span>Perfect Ride</span></h1>
          <p class="hero-subtitle">Rent quality cars at the best prices with a simple and secure booking experience. 20+ car options. 10 cities. No hidden charges.</p>
        </div>
      </div>
      <!-- Booking Widget -->
      <div class="row justify-content-center mt-4">
        <div class="col-xl-10">
          <div class="booking-widget-hero">
            <div class="bw-tabs">
              <button class="bw-tab active" data-tab="self">
                <i class="fas fa-user me-2"></i>Self Drive
              </button>
              <button class="bw-tab" data-tab="driver">
                <i class="fas fa-id-card me-2"></i>With Driver
              </button>
            </div>
            <div class="bw-fields">
              <div class="bw-field flex-grow-1" id="pickupField">
                <label><i class="fas fa-map-marker-alt text-primary me-1"></i>Pickup Address / City</label>
                <div class="d-flex align-items-center">
                  <input type="text" id="heroPickupAddress" class="form-control border-0 ps-0 fw-500" placeholder="Enter any city, airport or address (e.g. Bandra, Mumbai)">
                  <button type="button" class="btn btn-link text-primary p-0 ms-1 border-0" id="heroGpsBtn" title="Detect Current GPS Location">
                    <i class="fas fa-crosshairs fs-6"></i>
                  </button>
                </div>
                <input type="hidden" id="heroPickupLat">
                <input type="hidden" id="heroPickupLng">
              </div>
              <div class="bw-divider"></div>
              <div class="bw-field">
                <label><i class="fas fa-calendar-alt text-primary me-1"></i>Pickup Date & Time</label>
                <input type="text" id="pickupDate" class="form-control border-0 ps-0 fw-500" placeholder="Select date" readonly>
              </div>
              <div class="bw-divider"></div>
              <div class="bw-field">
                <label><i class="fas fa-calendar-check text-success me-1"></i>Return Date & Time</label>
                <input type="text" id="returnDate" class="form-control border-0 ps-0 fw-500" placeholder="Select date" readonly>
              </div>
              <div class="bw-search">
                <button class="btn-search-cars" id="searchCarsBtn">
                  <i class="fas fa-search me-2"></i>Search Cars
                </button>
              </div>
            </div>
            <!-- Quick Location Suggestions -->
            <div class="bw-popular">
              <span class="bw-popular-label">Popular:</span>
              <button class="bw-pill" onclick="setPickupLocation('Mumbai Airport')">Mumbai Airport</button>
              <button class="bw-pill" onclick="setPickupLocation('Delhi IGI Airport')">Delhi Airport</button>
              <button class="bw-pill" onclick="setPickupLocation('Bangalore Koramangala')">Bangalore</button>
              <button class="bw-pill" onclick="setPickupLocation('Goa Airport Branch')">Goa</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Hero Stats Bar -->
  <div class="hero-stats-bar">
    <div class="container">
      <div class="row g-0">
        <div class="col-6 col-md-3">
          <div class="hero-stat">
            <span class="hero-stat-value" data-counter="20" data-suffix="+">0</span>
            <span class="hero-stat-label">Car Models</span>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="hero-stat">
            <span class="hero-stat-value" data-counter="10" data-suffix="+">0</span>
            <span class="hero-stat-label">Cities</span>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="hero-stat">
            <span class="hero-stat-value" data-counter="50000" data-suffix="+">0</span>
            <span class="hero-stat-label">Happy Customers</span>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="hero-stat">
            <span class="hero-stat-value" data-counter="4" data-suffix=".8★">0</span>
            <span class="hero-stat-label">Avg. Rating</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- POPULAR CATEGORIES -->
<section class="section-py" id="categories">
  <div class="container">
    <div class="text-center mb-5" data-aos>
      <span class="section-label">Browse by Type</span>
      <h2 class="section-title">Popular <span class="text-primary-brand">Categories</span></h2>
      <p class="section-subtitle mx-auto mt-2">From budget-friendly economy cars to luxury sedans and adventure-ready SUVs.</p>
    </div>
    <div class="row g-4 row-cols-2 row-cols-md-3 row-cols-lg-6" id="categoriesGrid">
      @forelse($categories as $cat)
      <div class="col">
        <a href="{{ url('/cars?category=' . $cat->id) }}" class="card text-center p-4 h-100 border shadow-xs rounded-card text-decoration-none bg-surface text-dark">
          <div class="category-icon mx-auto mb-3 fs-3 text-primary"><i class="fas {{ $cat->icon ?? 'fa-car' }}"></i></div>
          <h6 class="fw-700 font-heading mb-1 text-dark">{{ $cat->name }}</h6>
          <span class="font-xs text-muted">{{ $cat->cars_count ?? 0 }} Vehicles</span>
        </a>
      </div>
      @empty
      <div class="col-12 text-center text-muted">No categories available.</div>
      @endforelse
    </div>
  </div>
</section>

<!-- FEATURED CARS -->
<section class="section-py bg-surface-2" id="featuredCars">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-5">
      <div>
        <span class="section-label">Top Picks</span>
        <h2 class="section-title">Featured <span class="text-primary-brand">Cars</span></h2>
        <p class="section-subtitle mt-2">Handpicked selection of our most popular and highly-rated vehicles.</p>
      </div>
      <a href="{{url('/cars')}}" class="btn btn-outline-brand">View All Cars <i class="fas fa-arrow-right ms-2"></i></a>
    </div>

    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4" id="featuredCarsGrid">
      @forelse($featuredCars as $car)
      <div class="col">
        <div class="card car-card h-100 border shadow-xs rounded-card overflow-hidden bg-surface">
          <div class="position-relative">
            <img src="{{ str_starts_with($car->image, 'http') ? $car->image : url($car->image) }}" class="card-img-top car-card-img" alt="{{ $car->brand_name }} {{ $car->model_name }}" style="height:190px; object-fit:cover;">
            <span class="position-absolute top-0 end-0 m-3 badge bg-primary font-xs">{{ $car->category->name ?? 'Car' }}</span>
          </div>
          <div class="card-body p-3 d-flex flex-column justify-content-between">
            <div>
              <h6 class="fw-800 font-heading mb-1 text-dark">{{ $car->brand_name }} {{ $car->model_name }}</h6>
              <div class="d-flex align-items-center gap-2 font-xs text-muted mb-2">
                <span><i class="fas fa-gas-pump me-1 text-primary"></i>{{ $car->fuel_type ?? 'Petrol' }}</span>
                <span>•</span>
                <span><i class="fas fa-cog me-1 text-primary"></i>{{ $car->transmission ?? 'Auto' }}</span>
              </div>
            </div>
            <div class="pt-2 border-top d-flex align-items-center justify-content-between">
              <div>
                <span class="font-xs text-muted">Daily Rate</span>
                <div class="fw-800 text-primary">₹{{ number_format($car->rate_per_day) }}/d</div>
              </div>
              <a href="{{ url('/booking?car=' . $car->id) }}" class="btn btn-sm btn-primary-brand">Book Now</a>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-4 text-muted">No cars added to database yet.</div>
      @endforelse
    </div>
      <!-- Rendered by JS -->
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section-py" id="whyUs">
  <div class="container">
    <div class="text-center mb-5" data-aos>
      <span class="section-label">Our Advantages</span>
      <h2 class="section-title">Why Choose <span class="text-primary-brand">DriveEase?</span></h2>
      <p class="section-subtitle mx-auto mt-2">We go beyond just car rentals to deliver a complete, worry-free experience.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-car-side"></i></div>
          <h5 class="fw-700 mb-2">Wide Range of Cars</h5>
          <p class="text-muted mb-0">From compact economy cars to premium luxury SUVs and electric vehicles — over 20 models to choose from across all categories.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon" style="background:var(--success-light);color:var(--success);"><i class="fas fa-tag"></i></div>
          <h5 class="fw-700 mb-2">Affordable Pricing</h5>
          <p class="text-muted mb-0">Transparent pricing with no hidden charges. Starting from just ₹1,600/day. Exclusive deals, seasonal discounts and loyalty rewards.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon" style="background:var(--info-light);color:var(--info);"><i class="fas fa-mobile-alt"></i></div>
          <h5 class="fw-700 mb-2">Easy Online Booking</h5>
          <p class="text-muted mb-0">Book your car in under 3 minutes. Simple search, instant confirmation, and digital documents — no paperwork required.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon" style="background:var(--warning-light);color:var(--warning);"><i class="fas fa-headset"></i></div>
          <h5 class="fw-700 mb-2">24/7 Customer Support</h5>
          <p class="text-muted mb-0">Round-the-clock customer support via phone, email and chat. Roadside assistance included with every rental.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon" style="background:var(--accent-light);color:var(--accent);"><i class="fas fa-shield-alt"></i></div>
          <h5 class="fw-700 mb-2">Secure Payment</h5>
          <p class="text-muted mb-0">Bank-level encryption on all transactions. Accept UPI, credit/debit cards, PayPal and cash pickup. Fully secure and trusted.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4" data-aos>
        <div class="feature-card">
          <div class="feature-icon" style="background:var(--danger-light);color:var(--danger);"><i class="fas fa-undo-alt"></i></div>
          <h5 class="fw-700 mb-2">Free Cancellation</h5>
          <p class="text-muted mb-0">Plans change — we understand. Free cancellation up to 48 hours before pickup. Full refund, no questions asked.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section-py bg-surface-2" id="howItWorks">
  <div class="container">
    <div class="text-center mb-5" data-aos>
      <span class="section-label">Simple Process</span>
      <h2 class="section-title">How It <span class="text-primary-brand">Works</span></h2>
      <p class="section-subtitle mx-auto mt-2">Rent a car in 4 simple steps. Fast, easy and completely online.</p>
    </div>
    <div class="row g-4 how-it-works-row">
      <div class="col-md-6 col-lg-3" data-aos>
        <div class="how-step">
          <div class="how-step-number">1</div>
          <div class="how-step-icon-wrap"><i class="fas fa-search"></i></div>
          <h5 class="fw-700 mt-3 mb-2">Search Your Car</h5>
          <p class="text-muted">Enter your pickup location, dates and preferred car type to see real-time availability.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos>
        <div class="how-step">
          <div class="how-step-number">2</div>
          <div class="how-step-icon-wrap"><i class="fas fa-car"></i></div>
          <h5 class="fw-700 mt-3 mb-2">Choose Your Ride</h5>
          <p class="text-muted">Browse detailed car listings with specs, photos and customer reviews to pick the perfect match.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos>
        <div class="how-step">
          <div class="how-step-number">3</div>
          <div class="how-step-icon-wrap"><i class="fas fa-credit-card"></i></div>
          <h5 class="fw-700 mt-3 mb-2">Book Online</h5>
          <p class="text-muted">Complete the secure checkout, add extras, pay online and get instant booking confirmation.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3" data-aos>
        <div class="how-step">
          <div class="how-step-number">4</div>
          <div class="how-step-icon-wrap"><i class="fas fa-key"></i></div>
          <h5 class="fw-700 mt-3 mb-2">Pick Up & Drive</h5>
          <p class="text-muted">Show your booking confirmation at the branch, collect your keys and hit the road!</p>
        </div>
      </div>
    </div>
    <div class="text-center mt-5">
      <a href="{{url('/cars')}}" class="btn btn-primary-brand btn-lg-brand">
        <i class="fas fa-car me-2"></i>Browse All Cars
      </a>
    </div>
  </div>
</section>

<!-- SPECIAL OFFERS -->
<section class="section-py" id="offers">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-5" data-aos>
      <div>
        <span class="section-label">Hot Deals</span>
        <h2 class="section-title">Special <span class="text-primary-brand">Offers</span></h2>
        <p class="section-subtitle mt-2">Grab these limited-time deals before they're gone.</p>
      </div>
      <a href="{{url('/offers')}}" class="btn btn-outline-brand">All Offers <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
    <div class="row g-4" id="offersGrid">
      <!-- Rendered by JS -->
    </div>
  </div>
</section>

<!-- CUSTOMER REVIEWS -->
<section class="section-py bg-surface-2" id="reviews">
  <div class="container">
    <div class="text-center mb-5" data-aos>
      <span class="section-label">What Customers Say</span>
      <h2 class="section-title">Customer <span class="text-primary-brand">Reviews</span></h2>
      <p class="section-subtitle mx-auto mt-2">Hear from thousands of happy customers who've driven with DriveEase.</p>
    </div>
    <div class="review-summary mb-5" data-aos>
      <div class="review-summary-score">
        <div class="rs-number">4.8</div>
        <div class="star-rating fs-4">
          <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
        </div>
        <div class="text-muted mt-1 small">Based on 50,000+ reviews</div>
      </div>
      <div class="review-summary-bars">
        <div class="rating-bar"><span>5★</span><div class="bar"><div class="bar-fill" style="width:72%"></div></div><span>72%</span></div>
        <div class="rating-bar"><span>4★</span><div class="bar"><div class="bar-fill" style="width:18%"></div></div><span>18%</span></div>
        <div class="rating-bar"><span>3★</span><div class="bar"><div class="bar-fill" style="width:7%"></div></div><span>7%</span></div>
        <div class="rating-bar"><span>2★</span><div class="bar"><div class="bar-fill" style="width:2%"></div></div><span>2%</span></div>
        <div class="rating-bar"><span>1★</span><div class="bar"><div class="bar-fill" style="width:1%"></div></div><span>1%</span></div>
      </div>
    </div>
    <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner" id="reviewsCarouselInner">
        <!-- Rendered by JS -->
      </div>
      <button class="carousel-control-prev carousel-ctrl" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="carousel-control-next carousel-ctrl" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
    <div class="carousel-indicators carousel-indicators-brand mt-4" id="reviewsDots"></div>
  </div>
</section>

<!-- FAQ SECTION -->
<section class="section-py" id="faq">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-5" data-aos>
        <span class="section-label">Questions & Answers</span>
        <h2 class="section-title">Frequently Asked <span class="text-primary-brand">Questions</span></h2>
        <p class="lead text-muted mt-3">Have more questions? We're here to help 24/7.</p>
        <div class="d-flex flex-column gap-3 mt-4">
          <a href="{{url('/faq')}}" class="btn btn-primary-brand"><i class="fas fa-question-circle me-2"></i>View All FAQs</a>
          <a href="{{url('/contact')}}" class="btn btn-outline-brand"><i class="fas fa-headset me-2"></i>Contact Support</a>
        </div>
        <div class="faq-contact-card mt-4">
          <i class="fas fa-phone-alt"></i>
          <div>
            <div class="fw-700">Call Us Anytime</div>
            <a href="tel:+911800123456" class="text-primary fw-600">1800-123-4567</a>
          </div>
        </div>
      </div>
      <div class="col-lg-7" data-aos>
        <div class="accordion accordion-flush" id="faqAccordion">
          <!-- Rendered by JS -->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section" id="newsletter">
  <div class="container">
    <div class="newsletter-card">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <span class="section-label" style="background:rgba(255,255,255,.15);color:#fff;">Stay Updated</span>
          <h2 class="text-white mt-2 fw-800" style="font-family:var(--font-heading);">Get Exclusive Deals in Your Inbox</h2>
          <p style="color:rgba(255,255,255,.8);" class="mb-0">Subscribe to our newsletter and never miss a great deal. Unsubscribe anytime.</p>
        </div>
        <div class="col-lg-6">
          <form class="newsletter-form" id="newsletterForm">
            <div class="input-group newsletter-input-group">
              <input type="email" class="form-control" placeholder="Enter your email address" id="newsletterEmail" required>
              <button class="btn newsletter-btn" type="submit">
                <i class="fas fa-paper-plane me-2"></i>Subscribe
              </button>
            </div>
            <p class="newsletter-note"><i class="fas fa-lock me-1"></i>We respect your privacy. No spam, ever.</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
