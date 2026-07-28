/**
 * DriveEase — Car Search Results Logic
 * cars.js
 */

let allCars = [];
let filteredCars = [];
let currentPage = 1;
const perPage = 6;
let viewMode = 'grid'; // 'grid' or 'list'

$(document).ready(function() {
  Storage.seed();
  initNavbarAuth();

  // Load URL search parameters if any
  const urlParams = new URLSearchParams(window.location.search);
  const selectedCat = urlParams.get('category');
  const selectedCity = urlParams.get('city');

  allCars = Storage.getCars().filter(c => c.status === 'Active');

  populateSidebarFilters();
  initModifySearchModal();

  if (selectedCat) {
    $(`#cat_${selectedCat.replace(/\s+/g,'')}`).prop('checked', true);
  }

  if (selectedCity) {
    $('#sumLocation').text(`City: ${selectedCity}`);
  }

  applyFilters();
  initEventListeners();
});

function initNavbarAuth() {
  const user = Storage.getAuthUser();
  if (user) {
    $('#loginBtn, #registerBtn').addClass('d-none');
    $('#userMenu').removeClass('d-none');
    $('#navUserName').text(user.name.split(' ')[0]);
  }
  $('#logoutBtn').on('click', function(e) {
    e.preventDefault();
    Storage.logout();
    window.location.reload();
  });
}

function populateSidebarFilters() {
  const categories = Storage.getCategories();
  const brands = Storage.getBrands();

  // Categories
  const catHtml = categories.map(c => `
    <div class="form-check filter-check">
      <label class="form-check-label" for="cat_${c.name.replace(/\s+/g,'')}">${c.name}</label>
      <input class="form-check-input filter-cat" type="checkbox" value="${c.name}" id="cat_${c.name.replace(/\s+/g,'')}">
    </div>
  `).join('');
  $('#categoryFilters').html(catHtml);

  // Brands
  const brandHtml = brands.map(b => `
    <div class="form-check filter-check">
      <label class="form-check-label" for="brand_${b.name}">${b.name}</label>
      <input class="form-check-input filter-brand" type="checkbox" value="${b.name}" id="brand_${b.name}">
    </div>
  `).join('');
  $('#brandFilters').html(brandHtml);
}

function initModifySearchModal() {
  const locations = Storage.getLocations();
  const $modLoc   = $('#modPickup');
  locations.forEach(l => $modLoc.append(`<option value="${l.id}">${l.name} (${l.city})</option>`));

  flatpickr('#modPDate', { minDate: 'today', defaultDate: DateHelper.todayStr() });
  flatpickr('#modRDate', { minDate: 'today', defaultDate: DateHelper.addDays(DateHelper.todayStr(), 3) });
}

function applyFilters() {
  const keyword   = $('#searchKeyword').val().toLowerCase().trim();
  const maxPrice  = parseInt($('#priceRange').val());

  const selectedCats   = $('.filter-cat:checked').map((_, el) => $(el).val()).get();
  const selectedBrands = $('.filter-brand:checked').map((_, el) => $(el).val()).get();
  const selectedTrans  = $('.filter-trans:checked').map((_, el) => $(el).val()).get();
  const selectedFuels  = $('.filter-fuel:checked').map((_, el) => $(el).val()).get();
  const selectedSeats  = $('.filter-seats:checked').map((_, el) => parseInt($(el).val())).get();

  filteredCars = allCars.filter(car => {
    // Keyword match
    if (keyword) {
      const match = car.brand.toLowerCase().includes(keyword) ||
                    car.model.toLowerCase().includes(keyword) ||
                    car.category.toLowerCase().includes(keyword);
      if (!match) return false;
    }

    // Price
    if (car.price > maxPrice) return false;

    // Categories
    if (selectedCats.length && !selectedCats.includes(car.category)) return false;

    // Brands
    if (selectedBrands.length && !selectedBrands.includes(car.brand)) return false;

    // Transmission
    if (selectedTrans.length && !selectedTrans.includes(car.transmission)) return false;

    // Fuel
    if (selectedFuels.length && !selectedFuels.includes(car.fuel)) return false;

    // Seats
    if (selectedSeats.length) {
      const is5 = selectedSeats.includes(5) && car.seats <= 5;
      const is7 = selectedSeats.includes(7) && car.seats >= 7;
      if (!is5 && !is7) return false;
    }

    return true;
  });

  // Sort
  const sortBy = $('#sortBy').val();
  if (sortBy === 'price-low')  filteredCars.sort((a, b) => a.price - b.price);
  if (sortBy === 'price-high') filteredCars.sort((a, b) => b.price - a.price);
  if (sortBy === 'rating')     filteredCars.sort((a, b) => b.rating - a.rating);
  if (sortBy === 'newest')     filteredCars.sort((a, b) => b.year - a.year);

  $('#resultCount').text(filteredCars.length);
  currentPage = 1;
  renderPage();
}

function renderPage() {
  const $grid = $('#carsGrid');
  Skeleton.renderSkeletons('#carsGrid', 4);

  setTimeout(() => {
    if (!filteredCars.length) {
      $grid.removeClass('row-cols-1 row-cols-md-2 row-cols-xl-3').html(`
        <div class="col-12">
          <div class="empty-state bg-surface border rounded-card">
            <div class="empty-state-icon"><i class="fas fa-car-side"></i></div>
            <h5>No Cars Matched Your Filter</h5>
            <p>Try resetting filters or expanding your price range.</p>
            <button class="btn btn-outline-brand" onclick="resetFilters()">Reset All Filters</button>
          </div>
        </div>
      `);
      $('#carsPagination').html('');
      return;
    }

    $grid.addClass('row-cols-1 row-cols-md-2 row-cols-xl-3');

    const totalPages = Math.ceil(filteredCars.length / perPage);
    const start = (currentPage - 1) * perPage;
    const pageCars = filteredCars.slice(start, start + perPage);

    if (viewMode === 'list') {
      $grid.removeClass('row-cols-1 row-cols-md-2 row-cols-xl-3');
      $grid.html(pageCars.map(car => renderListCarCard(car)).join(''));
    } else {
      $grid.html(pageCars.map(car => renderCarCard(car)).join(''));
    }

    Pagination.renderPaginator('#carsPagination', currentPage, totalPages, (p) => {
      currentPage = p;
      renderPage();
      window.scrollTo({ top: 300, behavior: 'smooth' });
    });
  }, 250);
}

function renderListCarCard(car) {
  const wishlisted = Storage.isWishlisted(car.id);
  return `
    <div class="col-12 mb-3">
      <div class="car-card car-card-list">
        <div class="car-img-wrapper" style="width:260px; height:auto; flex-shrink:0;">
          <img src="${car.image}" alt="${car.brand} ${car.model}" style="height:100%; object-fit:cover;">
          <button class="wishlist-btn ${wishlisted ? 'active' : ''}" onclick="handleWishlist('${car.id}', this)">
            <i class="${wishlisted ? 'fas' : 'far'} fa-heart"></i>
          </button>
        </div>
        <div class="car-body flex-fill d-flex flex-column justify-content-between p-3">
          <div>
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="car-category mb-1">${car.category} · ${car.year}</p>
                <h5 class="car-title fs-5 mb-1">${car.brand} ${car.model}</h5>
              </div>
              <div class="text-end">
                <div class="car-price">₹${car.price.toLocaleString()} <span class="fs-6 text-muted">/ day</span></div>
              </div>
            </div>
            <div class="d-flex align-items-center gap-1 mb-2">
              ${renderStars(car.rating)}
              <span class="rating-text">(${car.reviews} reviews)</span>
            </div>
            <div class="car-specs mb-3">
              <span class="spec-item"><i class="fas fa-users me-1"></i>${car.seats} Seats</span>
              <span class="spec-item"><i class="fas fa-cog me-1"></i>${car.transmission}</span>
              <span class="spec-item"><i class="fas fa-gas-pump me-1"></i>${car.fuel}</span>
              <span class="spec-item"><i class="fas fa-tachometer-alt me-1"></i>${car.mileage}</span>
            </div>
          </div>
          <div class="d-flex gap-2">
            <a href="car-details.html?id=${car.id}" class="btn btn-outline-brand btn-sm-brand">View Details</a>
            <a href="booking.html?id=${car.id}" class="btn btn-primary-brand btn-sm-brand ${car.available ? '' : 'disabled'}">Book Now</a>
          </div>
        </div>
      </div>
    </div>`;
}

function resetFilters() {
  $('#searchKeyword').val('');
  $('#priceRange').val(15000);
  $('#priceDisplay').text('₹15,000');
  $('input[type="checkbox"]').prop('checked', false);
  $('#sortBy').val('recommended');
  applyFilters();
  Toast.info('Filters Reset', 'All filter options have been cleared.');
}

function initEventListeners() {
  $('#searchKeyword').on('input', applyFilters);
  $('#priceRange').on('input', function() {
    $('#priceDisplay').text('₹' + parseInt($(this).val()).toLocaleString());
    applyFilters();
  });
  $('.filter-cat, .filter-brand, .filter-trans, .filter-fuel, .filter-seats').on('change', applyFilters);
  $('#sortBy').on('change', applyFilters);
  $('#resetFiltersBtn').on('click', resetFilters);

  // View toggle
  $('#gridViewBtn').on('click', function() {
    $(this).addClass('active').siblings().removeClass('active');
    viewMode = 'grid';
    renderPage();
  });
  $('#listViewBtn').on('click', function() {
    $(this).addClass('active').siblings().removeClass('active');
    viewMode = 'list';
    renderPage();
  });

  // Apply modify search modal
  $('#applyModifySearchBtn').on('click', function() {
    const locId = $('#modPickup').val();
    const locObj = Storage.getLocationById(locId);
    if (locObj) {
      $('#sumLocation').text(locObj.name);
    }
    const pDate = $('#modPDate').val();
    const rDate = $('#modRDate').val();
    $('#sumDates').text(`${pDate} to ${rDate}`);

    const modal = bootstrap.Modal.getInstance(document.getElementById('modifySearchModal'));
    modal.hide();
    Toast.success('Search Updated', 'Listing results updated for selected criteria.');
  });
}
