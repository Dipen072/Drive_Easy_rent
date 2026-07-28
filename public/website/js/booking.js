/**
 * DriveEase — Multi-step Checkout Logic
 * booking.js
 */

let currentCar = null;
let currentStep = 1;
let selectedPaymentMethod = 'Credit Card';
let appliedDiscount = 0;
let appliedCoupon = null;

$(document).ready(function() {
  Storage.seed();

  const urlParams = new URLSearchParams(window.location.search);
  const carId = urlParams.get('id') || 'C001';
  currentCar = Storage.getCarById(carId);

  if (!currentCar) {
    Toast.error('Error', 'Invalid car selection.');
    setTimeout(() => window.location.href = 'cars.html', 1500);
    return;
  }

  initCustomerData();
  initRentalDetails(urlParams);
  renderCarSummary();
  calculateTotals();
  initEventListeners();
});

function initCustomerData() {
  const user = Storage.getAuthUser();
  if (user) {
    $('#custName').val(user.name);
    $('#custEmail').val(user.email);
    $('#custPhone').val(user.phone || '+91 98765 43210');
    $('#custCity').val(user.city || 'Mumbai');
    $('#custState').val(user.state || 'Maharashtra');
    if (user.license) $('#custLicense').val(user.license);
  }
}

function initRentalDetails(urlParams) {
  const locations = Storage.getLocations();
  const $pLoc = $('#rentPickupLoc');
  const $dLoc = $('#rentDropLoc');

  locations.forEach(l => {
    const opt = `<option value="${l.id}">${l.name} (${l.city})</option>`;
    $pLoc.append(opt);
    $dLoc.append(opt);
  });

  const paramLoc = urlParams.get('pickup');
  if (paramLoc) {
    $pLoc.val(paramLoc);
    $dLoc.val(paramLoc);
  } else if (currentCar.locationId) {
    $pLoc.val(currentCar.locationId);
    $dLoc.val(currentCar.locationId);
  }

  const pDate = urlParams.get('pdate') || DateHelper.todayStr();
  const rDate = urlParams.get('rdate') || DateHelper.addDays(pDate, 3);

  flatpickr('#rentPickupDate', { minDate: 'today', defaultDate: pDate, onChange: calculateTotals });
  flatpickr('#rentReturnDate', { minDate: 'today', defaultDate: rDate, onChange: calculateTotals });
}

function renderCarSummary() {
  const c = currentCar;
  $('#sumCarImg').attr('src', c.image);
  $('#sumCarTitle').text(`${c.brand} ${c.model}`);
  $('#sumCarCat').text(c.category);
}

function calculateTotals() {
  const pDate = $('#rentPickupDate').val();
  const rDate = $('#rentReturnDate').val();
  const days  = Math.max(DateHelper.daysBetween(pDate, rDate), 1);

  const basePrice = currentCar.price * days;

  // Calculate extras
  let extrasTotal = 0;
  $('.extra-toggle:checked').each(function() {
    extrasTotal += (parseInt($(this).data('price')) * days);
  });

  const subtotal = basePrice + extrasTotal;
  const tax = PriceHelper.tax(subtotal);

  // Apply promo discount if any
  if (appliedCoupon) {
    const val = Storage.validateCoupon(appliedCoupon.code, subtotal);
    if (val.valid) appliedDiscount = val.discount;
  }

  const grandTotal = subtotal + tax - appliedDiscount;

  $('#sumDays').text(`${days} Day${days > 1 ? 's' : ''}`);
  $('#sumBasePrice').text(`₹${basePrice.toLocaleString()}`);
  $('#sumExtras').text(`₹${extrasTotal.toLocaleString()}`);
  $('#sumTax').text(`₹${tax.toLocaleString()}`);

  if (appliedDiscount > 0) {
    $('#sumDiscountRow').removeClass('d-none');
    $('#sumDiscount').text(`-₹${appliedDiscount.toLocaleString()}`);
  } else {
    $('#sumDiscountRow').addClass('d-none');
  }

  $('#sumGrandTotal, #btnPayTotal').text(`₹${grandTotal.toLocaleString()}`);

  return { days, basePrice, extrasTotal, tax, discount: appliedDiscount, grandTotal };
}

function nextStep(step) {
  if (step === 1) {
    if (!Validate.form($('#step1'))) {
      Toast.warning('Incomplete Form', 'Please complete all required driver details.');
      return;
    }
  }

  if (step === 2) {
    if (!Validate.form($('#step2'))) {
      Toast.warning('Incomplete Dates', 'Please select pickup & return details.');
      return;
    }
  }

  $(`#step${step}`).addClass('d-none');
  $(`.step-item[data-step="${step}"]`).removeClass('active').addClass('completed');

  currentStep = step + 1;
  $(`#step${currentStep}`).removeClass('d-none');
  $(`.step-item[data-step="${currentStep}"]`).addClass('active');

  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function prevStep(step) {
  $(`#step${step}`).addClass('d-none');
  $(`.step-item[data-step="${step}"]`).removeClass('active');

  currentStep = step - 1;
  $(`#step${currentStep}`).removeClass('d-none');
  $(`.step-item[data-step="${currentStep}"]`).removeClass('completed').addClass('active');

  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function initEventListeners() {
  $('.extra-toggle').on('change', calculateTotals);

  // Promo code apply
  $('#applyPromoBtn').on('click', function() {
    const code = $('#promoInput').val().trim();
    if (!code) return;

    const totals = calculateTotals();
    const subtotal = totals.basePrice + totals.extrasTotal;
    const res = Storage.validateCoupon(code, subtotal);

    if (res.valid) {
      appliedCoupon = res.coupon;
      appliedDiscount = res.discount;
      calculateTotals();
      $('#promoMsg').removeClass('d-none text-danger').addClass('text-success').text(res.message);
      Toast.success('Promo Applied', res.message);
    } else {
      appliedCoupon = null;
      appliedDiscount = 0;
      calculateTotals();
      $('#promoMsg').removeClass('d-none text-success').addClass('text-danger').text(res.message);
      Toast.error('Invalid Coupon', res.message);
    }
  });

  // Payment tab switch
  $('.payment-tab-btn').on('click', function() {
    $('.payment-tab-btn').removeClass('active');
    $(this).addClass('active');
    selectedPaymentMethod = $(this).data('method');

    if (selectedPaymentMethod === 'UPI') {
      $('#payFormCard').addClass('d-none');
      $('#payFormUPI').removeClass('d-none');
    } else {
      $('#payFormCard').removeClass('d-none');
      $('#payFormUPI').addClass('d-none');
    }
  });

  // Submit Checkout
  $('#checkoutForm').on('submit', function(e) {
    e.preventDefault();

    if (!$('#termsCheck').is(':checked')) {
      Toast.warning('Terms & Conditions', 'Please accept the terms & conditions to proceed.');
      return;
    }

    const totals = calculateTotals();
    const bookingId = Storage.nextBookingId();
    const pLocObj = Storage.getLocationById($('#rentPickupLoc').val());
    const dLocObj = Storage.getLocationById($('#rentDropLoc').val());

    const newBooking = {
      id: bookingId,
      customerId: 'CU001',
      customerName: $('#custName').val(),
      carId: currentCar.id,
      carName: `${currentCar.brand} ${currentCar.model}`,
      pickup: $('#rentPickupLoc').val(),
      pickupName: pLocObj ? pLocObj.name : 'Branch Pickup',
      dropoff: $('#rentDropLoc').val(),
      dropoffName: dLocObj ? dLocObj.name : 'Branch Dropoff',
      pickupDate: $('#rentPickupDate').val(),
      returnDate: $('#rentReturnDate').val(),
      pickupTime: $('#rentPickupTime').val(),
      returnTime: $('#rentReturnTime').val(),
      days: totals.days,
      basePrice: totals.basePrice,
      extras: totals.extrasTotal,
      tax: totals.tax,
      discount: totals.discount,
      total: totals.grandTotal,
      status: 'Confirmed',
      payment: selectedPaymentMethod,
      paymentStatus: 'Paid',
      createdAt: DateHelper.todayStr()
    };

    Storage.saveBooking(newBooking);

    // Disable button & show spinner simulation
    $('#confirmBookingBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing Booking...');

    setTimeout(() => {
      window.location.href = `booking-success.html?id=${bookingId}`;
    }, 1200);
  });
}
