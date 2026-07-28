/**
 * DriveEase — Home Page Logic
 * home.js
 */

$(document).ready(function() {
  // Ensure data is seeded
  Storage.seed();

  // 1. Initialize Navbar Auth State
  initNavbarAuth();

  // 2. Populate Location Options in Hero Booking Widget
  populateLocations();

  // 3. Initialize Date Pickers in Hero Booking Widget
  initDatePickers();

  // 4. Render Popular Categories
  renderCategories();

  // 5. Render Featured Cars with Skeleton & Filter Pills
  renderFeaturedCars('all');

  // 6. Render Special Offers
  renderOffers();

  // 7. Render Customer Reviews Carousel
  renderReviews();

  // 8. Render FAQ Accordion
  renderFAQ();

  // 9. Event Listeners
  initEventListeners();
});

// ============================================================
// 1. NAVBAR AUTH STATE
// ============================================================
function initNavbarAuth() {
  const user = Storage.getAuthUser();
  if (user) {
    $('#loginBtn, #registerBtn').addClass('d-none');
    $('#userMenu').removeClass('d-none');
    $('#navUserName').text(user.name.split(' ')[0]);
  } else {
    $('#loginBtn, #registerBtn').removeClass('d-none');
    $('#userMenu').addClass('d-none');
  }

  $('#logoutBtn').on('click', function(e) {
    e.preventDefault();
    Storage.logout();
    Toast.success('Logged Out', 'You have been successfully logged out.');
    setTimeout(() => window.location.reload(), 1000);
  });
}

// ============================================================
// 2. POPULATE LOCATIONS
// ============================================================
function populateLocations() {
  const locations = Storage.getLocations();
  const $pickup   = $('#pickupLocation');
  const $dropoff  = $('#dropoffLocation');

  locations.forEach(loc => {
    const opt = `<option value="${loc.id}">${loc.name} (${loc.city})</option>`;
    $pickup.append(opt);
    $dropoff.append(opt);
  });
}

function setPickupLocation(name) {
  const locations = Storage.getLocations();
  const found = locations.find(l => l.name.toLowerCase().includes(name.toLowerCase()) || l.city.toLowerCase().includes(name.toLowerCase()));
  if (found) {
    $('#pickupLocation').val(found.id).addClass('is-valid');
    Toast.info('Location Selected', `Set pickup location to ${found.name}`);
  }
}

// ============================================================
// 3. DATE PICKERS
// ============================================================
function initDatePickers() {
  const today = DateHelper.todayStr();
  const defaultReturn = DateHelper.addDays(today, 3);

  const pickupPicker = flatpickr('#pickupDate', {
    minDate: 'today',
    defaultDate: today,
    dateFormat: 'Y-m-d',
    onChange: function(selectedDates, dateStr) {
      returnPicker.set('minDate', dateStr);
      if (flatpickr.parseDate($('#returnDate').val()) < selectedDates[0]) {
        returnPicker.setDate(DateHelper.addDays(dateStr, 1));
      }
    }
  });

  const returnPicker = flatpickr('#returnDate', {
    minDate: today,
    defaultDate: defaultReturn,
    dateFormat: 'Y-m-d'
  });
}

// ============================================================
// 4. CATEGORIES
// ============================================================
function renderCategories() {
  const categories = Storage.getCategories();
  const html = categories.map(cat => `
    <a href="cars.html?category=${encodeURIComponent(cat.name)}" class="category-card" data-aos>
      <div class="category-icon"><i class="fas ${cat.icon}"></i></div>
      <div class="category-name">${cat.name}</div>
      <div class="category-count">${cat.cars} Cars</div>
    </a>
  `).join('');
  $('#categoriesGrid').html(html);
}

// ============================================================
// 5. FEATURED CARS
// ============================================================
function renderFeaturedCars(filter = 'all') {
  const $grid = $('#featuredCarsGrid');
  Skeleton.renderSkeletons('#featuredCarsGrid', 4);

  setTimeout(() => {
    const cars = Storage.getCars().filter(c => c.status === 'Active');
    let filtered = cars;
    if (filter !== 'all') {
      filtered = cars.filter(c => c.category.toLowerCase() === filter.toLowerCase());
    }

    if (!filtered.length) {
      $grid.html(`
        <div class="col-12">
          <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-car-rear"></i></div>
            <h5>No Cars Found</h5>
            <p>No vehicles found in this category right now.</p>
          </div>
        </div>
      `);
      return;
    }

    // Limit to 8 featured cars max
    const displayCars = filtered.slice(0, 8);
    $grid.html(displayCars.map(car => renderCarCard(car)).join(''));
  }, 400);
}

// ============================================================
// 6. SPECIAL OFFERS
// ============================================================
function renderOffers() {
  const coupons = Storage.getCoupons().filter(c => c.status === 'Active').slice(0, 3);
  const html = coupons.map(c => `
    <div class="col-md-6 col-lg-4" data-aos>
      <div class="offer-card" style="background:${c.bgColor || 'var(--primary-gradient)'}">
        <span class="badge bg-white text-dark fw-700 px-3 py-2 rounded-pill mb-3">${c.description}</span>
        <div class="offer-discount">${c.type === 'percentage' ? c.value + '% OFF' : '₹' + c.value + ' OFF'}</div>
        <p class="mb-3 opacity-75">Min booking: ₹${c.minAmount.toLocaleString()}</p>
        <div class="d-flex align-items-center justify-content-between">
          <div class="offer-code" onclick="copyToClipboard('${c.code}', 'Coupon Copied!')">
            <i class="far fa-copy me-1"></i> ${c.code}
          </div>
          <a href="cars.html" class="btn btn-sm btn-light fw-700 rounded-pill px-3">Use Code</a>
        </div>
      </div>
    </div>
  `).join('');
  $('#offersGrid').html(html);
}

// ============================================================
// 7. CUSTOMER REVIEWS CAROUSEL
// ============================================================
function renderReviews() {
  const reviews = Storage.getReviews().filter(r => r.status === 'Approved');
  const $inner  = $('#reviewsCarouselInner');
  const $dots   = $('#reviewsDots');

  let itemsHtml = '';
  let dotsHtml  = '';

  reviews.forEach((r, idx) => {
    const active = idx === 0 ? 'active' : '';
    itemsHtml += `
      <div class="carousel-item ${active}">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="review-card text-center p-4">
              <img src="${r.avatar}" alt="${r.customerName}" class="review-avatar mx-auto mb-3">
              <div class="star-rating fs-5 mb-2">${renderStars(r.rating, false)}</div>
              <p class="review-text">"${r.review}"</p>
              <h6 class="fw-700 mb-0">${r.customerName}</h6>
              <small class="text-muted">${r.carName} Renter</small>
            </div>
          </div>
        </div>
      </div>`;

    dotsHtml += `<button type="button" data-bs-target="#reviewsCarousel" data-bs-slide-to="${idx}" class="${active}" aria-label="Slide ${idx+1}"></button>`;
  });

  $inner.html(itemsHtml);
  $dots.html(dotsHtml);
}

// ============================================================
// 8. FAQ ACCORDION
// ============================================================
function renderFAQ() {
  const faqs = FAQ_DATA.slice(0, 6);
  const html = faqs.map((f, idx) => `
    <div class="accordion-item border-0 mb-3 rounded-card shadow-sm overflow-hidden">
      <h2 class="accordion-header" id="faqHeading${f.id}">
        <button class="accordion-button fw-700 fs-6 ${idx === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse${f.id}">
          <i class="fas fa-circle-question text-primary me-2"></i> ${f.q}
        </button>
      </h2>
      <div id="faqCollapse${f.id}" class="accordion-collapse collapse ${idx === 0 ? 'show' : ''}" data-bs-parent="#faqAccordion">
        <div class="accordion-body text-muted leading-relaxed">
          ${f.a}
        </div>
      </div>
    </div>
  `).join('');
  $('#faqAccordion').html(html);
}

// ============================================================
// 9. EVENT LISTENERS
// ============================================================
function initEventListeners() {
  // Booking Widget Tabs (Self Drive / Driver)
  $('.bw-tab').on('click', function() {
    $('.bw-tab').removeClass('active');
    $(this).addClass('active');
    const mode = $(this).data('tab');
    if (mode === 'driver') {
      Toast.info('With Driver Option', 'Chauffeur services added at ₹800/day during checkout.');
    }
  });

  // Filter Pills for Featured Cars
  $('#carFilterPills').on('click', '.filter-pill', function() {
    $('.filter-pill').removeClass('active');
    $(this).addClass('active');
    const filter = $(this).data('filter');
    renderFeaturedCars(filter);
  });

  // Search Cars Button Click
  $('#searchCarsBtn').on('click', function() {
    const pickup = $('#pickupLocation').val();
    const pDate  = $('#pickupDate').val();
    const rDate  = $('#returnDate').val();

    if (!pickup) {
      Toast.warning('Select Location', 'Please select a pickup location.');
      $('#pickupLocation').focus();
      return;
    }
    if (!pDate || !rDate) {
      Toast.warning('Select Dates', 'Please select pickup and return dates.');
      return;
    }

    const searchParams = {
      pickupLocation: pickup,
      dropoffLocation: $('#dropoffLocation').val() || pickup,
      pickupDate: pDate,
      returnDate: rDate
    };

    Storage.saveSearch(searchParams);
    Toast.success('Searching Cars', 'Redirecting to available cars...');
    setTimeout(() => {
      window.location.href = `cars.html?pickup=${pickup}&pdate=${pDate}&rdate=${rDate}`;
    }, 600);
  });

  // Newsletter Submit
  $('#newsletterForm').on('submit', function(e) {
    e.preventDefault();
    const email = $('#newsletterEmail').val().trim();
    if (email) {
      Toast.success('Subscribed!', 'Thank you for subscribing to DriveEase deals.');
      $('#newsletterEmail').val('');
    }
  });
}
