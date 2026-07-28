/**
 * DriveEase — Car Details Logic
 * car-details.js
 */

let currentCar = null;

$(document).ready(function() {
  Storage.seed();
  initNavbarAuth();

  const urlParams = new URLSearchParams(window.location.search);
  const carId = urlParams.get('id') || 'C001';

  currentCar = Storage.getCarById(carId);

  if (!currentCar) {
    Toast.error('Car Not Found', 'Redirecting to vehicle catalog...');
    setTimeout(() => window.location.href = 'cars.html', 1500);
    return;
  }

  renderCarDetails();
  initBookingWidget();
  renderRelatedCars();
  initEventListeners();
});

function initNavbarAuth() {
  const user = Storage.getAuthUser();
  if (user) {
    $('#loginBtn, #registerBtn').addClass('d-none');
    $('#userMenu').removeClass('d-none');
    $('#navUserName').text(user.name.split(' ')[0]);
  }
}

function renderCarDetails() {
  const c = currentCar;

  document.title = `${c.brand} ${c.model} (${c.year}) — DriveEase`;

  $('#breadCarName').text(`${c.brand} ${c.model}`);
  $('#carCategoryBadge').text(c.category);
  $('#carTitle').text(`${c.brand} ${c.model} (${c.year})`);
  $('#carLocation').text(c.location);
  $('#carRating').text(c.rating);
  $('#carReviewCount, #tabReviewCount').text(c.reviews);
  $('#carPriceDisplay').html(`₹${c.price.toLocaleString()} <span class="fs-6 text-muted font-body">/ day</span>`);

  if (!c.available) {
    $('#carAvailBadge').attr('class', 'badge-unavailable').text('Unavailable');
    $('#proceedToBookBtn').addClass('disabled').text('Vehicle Currently Booked');
  }

  // Gallery
  const imgs = c.images && c.images.length ? c.images : [c.image];
  $('#mainGalleryImg').attr('src', imgs[0]);

  const thumbHtml = imgs.map((img, idx) => `
    <div class="gallery-thumb ${idx === 0 ? 'active' : ''}" onclick="switchGalleryImg('${img}', this)">
      <img src="${img}" alt="Thumbnail ${idx+1}">
    </div>
  `).join('');
  $('#galleryThumbs').html(thumbHtml);

  // Specs grid
  const specs = [
    { icon: 'fa-users', label: 'Seats', val: `${c.seats} Persons` },
    { icon: 'fa-door-closed', label: 'Doors', val: `${c.doors} Doors` },
    { icon: 'fa-cog', label: 'Transmission', val: c.transmission },
    { icon: 'fa-gas-pump', label: 'Fuel Type', val: c.fuel },
    { icon: 'fa-tachometer-alt', label: 'Mileage', val: c.mileage },
    { icon: 'fa-snowflake', label: 'AirCon', val: c.ac ? 'Yes (Automatic)' : 'No' },
    { icon: 'fa-map-location-dot', label: 'GPS', val: c.gps ? 'Included' : 'Optional' },
    { icon: 'fa-palette', label: 'Color', val: c.color || 'Standard' }
  ];

  $('#specsGrid').html(specs.map(s => `
    <div class="spec-card">
      <i class="fas ${s.icon} spec-card-icon"></i>
      <div class="spec-card-info">
        <span class="spec-label">${s.label}</span>
        <span class="spec-value">${s.val}</span>
      </div>
    </div>
  `).join(''));

  // Description & Pricing
  $('#carDescription').text(c.description);
  $('#rateDaily').text(`₹${c.price.toLocaleString()}`);
  $('#rateWeekly').text(`₹${Math.round(c.weeklyPrice / 7).toLocaleString()}`);
  $('#rateMonthly').text(`₹${Math.round(c.monthlyPrice / 30).toLocaleString()}`);
  $('#rateDeposit').text(`₹${c.deposit.toLocaleString()}`);

  // Features list
  if (c.features && c.features.length) {
    $('#featuresList').html(c.features.map(f => `
      <div class="col">
        <div class="d-flex align-items-center gap-2 p-2 bg-surface-2 rounded border font-sm fw-600 text-secondary">
          <i class="fas fa-circle-check text-success"></i> ${f}
        </div>
      </div>
    `).join(''));
  }

  // Wishlist button state
  const isWL = Storage.isWishlisted(c.id);
  $('#wishlistToggleBtn').toggleClass('active', isWL);
  if (isWL) $('#wishlistToggleBtn i').attr('class', 'fas fa-heart text-danger me-1');

  // Reviews
  const reviews = Storage.getReviews().filter(r => r.carId === c.id || r.rating >= 4).slice(0, 3);
  if (reviews.length) {
    $('#reviewsContainer').html(reviews.map(r => `
      <div class="p-3 border-bottom">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <img src="${r.avatar}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
            <div>
              <div class="fw-700 font-sm">${r.customerName}</div>
              <div class="star-rating font-xs">${renderStars(r.rating, false)}</div>
            </div>
          </div>
          <small class="text-muted">${r.date}</small>
        </div>
        <p class="font-sm text-muted mb-0">"${r.review}"</p>
      </div>
    `).join(''));
  } else {
    $('#reviewsContainer').html('<p class="text-muted p-3 mb-0">No customer reviews yet for this vehicle.</p>');
  }
}

function switchGalleryImg(url, el) {
  $('#mainGalleryImg').attr('src', url);
  $('.gallery-thumb').removeClass('active');
  $(el).addClass('active');
}

function initBookingWidget() {
  const locations = Storage.getLocations();
  const $loc = $('#bookPickup');
  locations.forEach(l => $loc.append(`<option value="${l.id}">${l.name}</option>`));

  if (currentCar.locationId) $loc.val(currentCar.locationId);

  const today = DateHelper.todayStr();
  const returnD = DateHelper.addDays(today, 2);

  const pPick = flatpickr('#bookPDate', {
    minDate: 'today',
    defaultDate: today,
    onChange: function(_, dStr) {
      rPick.set('minDate', dStr);
      calculateLivePrice();
    }
  });

  const rPick = flatpickr('#bookRDate', {
    minDate: today,
    defaultDate: returnD,
    onChange: calculateLivePrice
  });

  calculateLivePrice();
}

function calculateLivePrice() {
  const pDate = $('#bookPDate').val();
  const rDate = $('#bookRDate').val();

  const days = Math.max(DateHelper.daysBetween(pDate, rDate), 1);
  const dailyRate = currentCar.price;
  const base = dailyRate * days;
  const tax = PriceHelper.tax(base);
  const total = base + tax;

  $('#calcDailyRate').text(`₹${dailyRate.toLocaleString()}`);
  $('#calcDuration').text(`${days} Day${days > 1 ? 's' : ''}`);
  $('#calcTax').text(`₹${tax.toLocaleString()}`);
  $('#calcTotal').text(`₹${total.toLocaleString()}`);
}

function renderRelatedCars() {
  const related = Storage.getCars()
    .filter(c => c.id !== currentCar.id && (c.category === currentCar.category || c.brand === currentCar.brand))
    .slice(0, 4);

  if (related.length) {
    $('#relatedCarsGrid').html(related.map(c => renderCarCard(c)).join(''));
  }
}

function initEventListeners() {
  $('#wishlistToggleBtn').on('click', function() {
    const isAdded = Storage.toggleWishlist(currentCar.id);
    $(this).toggleClass('active', isAdded);
    $(this).find('i').attr('class', isAdded ? 'fas fa-heart text-danger me-1' : 'far fa-heart me-1');
    Toast.success(isAdded ? 'Added to Wishlist' : 'Removed from Wishlist', `${currentCar.brand} ${currentCar.model} wishlist status updated.`);
  });

  $('#shareBtn').on('click', function() {
    copyToClipboard(window.location.href, 'Link Copied!');
  });

  $('#proceedToBookBtn').on('click', function() {
    if (!currentCar.available) return;
    const pLoc = $('#bookPickup').val();
    const pDate = $('#bookPDate').val();
    const rDate = $('#bookRDate').val();

    window.location.href = `booking.html?id=${currentCar.id}&pickup=${pLoc}&pdate=${pDate}&rdate=${rDate}`;
  });
}
