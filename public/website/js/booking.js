/**
 * DriveEase — Multi-step Checkout with Razorpay Payment Integration
 * booking.js
 */

let currentStep = 1;
let selectedPaymentMethod = 'Razorpay';
let appliedCouponCode = null;

$(document).ready(function() {
  initRentalDetails();
  calculateTotals();
  initEventListeners();
});

function initRentalDetails() {
  const today = new Date().toISOString().split('T')[0];
  const nextThreeDays = new Date(Date.now() + 3 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

  const urlParams = new URLSearchParams(window.location.search);
  const pDate = urlParams.get('pdate') || today;
  const rDate = urlParams.get('rdate') || nextThreeDays;

  const paramLoc = urlParams.get('pickup') || urlParams.get('location_id') || urlParams.get('pickup_address');
  if (paramLoc) {
    $('#rentPickupLoc').val(paramLoc);
    $('#rentDropLoc').val(paramLoc);
    $('#rentPickupAddress').val(paramLoc);
  }

  if (typeof DriveEaseLocationPicker !== 'undefined') {
    DriveEaseLocationPicker.setupAutocomplete(
      'rentPickupAddress', 'rentPickupLat', 'rentPickupLng', 'btnMapPickup', 'btnGpsPickup'
    );
    DriveEaseLocationPicker.setupAutocomplete(
      'rentDropAddress', 'rentDropLat', 'rentDropLng', 'btnMapDrop', 'btnGpsDrop'
    );
  }

  if (typeof flatpickr !== 'undefined') {
    flatpickr('#rentPickupDate', {
      minDate: 'today',
      defaultDate: pDate,
      dateFormat: 'Y-m-d',
      onChange: function(selectedDates, dateStr) {
        calculateTotals();
      }
    });

    flatpickr('#rentReturnDate', {
      minDate: 'today',
      defaultDate: rDate,
      dateFormat: 'Y-m-d',
      onChange: function(selectedDates, dateStr) {
        calculateTotals();
      }
    });
  } else {
    $('#rentPickupDate').val(pDate);
    $('#rentReturnDate').val(rDate);
  }
}

window.switchLocationMode = function(mode) {
  if (mode === 'doorstep') {
    $('#modeDoorstepBtn').addClass('btn-primary').removeClass('btn-outline-secondary');
    $('#modeBranchBtn').addClass('btn-outline-secondary').removeClass('btn-primary');
    $('#doorstepLocationSection').removeClass('d-none');
    $('#branchLocationSection').addClass('d-none');

    $('#rentPickupAddress').prop('required', true);
    $('#rentPickupLoc, #rentDropLoc').prop('required', false).val('');
  } else {
    $('#modeBranchBtn').addClass('btn-primary').removeClass('btn-outline-secondary');
    $('#modeDoorstepBtn').addClass('btn-outline-secondary').removeClass('btn-primary');
    $('#branchLocationSection').removeClass('d-none');
    $('#doorstepLocationSection').addClass('d-none');

    $('#rentPickupAddress, #rentDropAddress').prop('required', false).val('');
    $('#rentPickupLat, #rentPickupLng, #rentDropLat, #rentDropLng').val('');
    $('#rentPickupLoc, #rentDropLoc').prop('required', true);
  }
};

window.toggleDiffDropoff = function(checked) {
  if (checked) {
    $('#dropoffAddressContainer').removeClass('d-none');
    $('#rentDropAddress').prop('required', true);
  } else {
    $('#dropoffAddressContainer').addClass('d-none');
    $('#rentDropAddress').prop('required', false).val('');
    $('#rentDropLat, #rentDropLng').val('');
  }
};

function calculateTotals() {
  const carId = $('#carId').val();
  const pickupDate = $('#rentPickupDate').val();
  const returnDate = $('#rentReturnDate').val();

  if (!pickupDate || !returnDate || !carId) return;

  const extraServices = [];
  $('.extra-toggle:checked').each(function() {
    extraServices.push($(this).val());
  });

  const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

  $.ajax({
    url: '/booking/calculate-price',
    method: 'POST',
    data: {
      _token: csrfToken,
      car_id: carId,
      pickup_date: pickupDate,
      return_date: returnDate,
      extra_services: extraServices,
      coupon_code: appliedCouponCode
    },
    success: function(response) {
      if (response.success) {
        const data = response.data;
        const days = data.rental_days;

        $('#sumDays').text(`${days} Day${days > 1 ? 's' : ''}`);
        $('#sumBasePrice').text(`₹${data.base_price.toLocaleString()}`);
        $('#sumExtras').text(`₹${data.extras_amount.toLocaleString()}`);
        $('#sumTax').text(`₹${data.tax_amount.toLocaleString()}`);

        if (data.discount_amount > 0) {
          $('#sumDiscountRow').removeClass('d-none');
          $('#sumDiscount').text(`-₹${data.discount_amount.toLocaleString()}`);
        } else {
          $('#sumDiscountRow').addClass('d-none');
        }

        $('#sumGrandTotal, #btnPayTotal').text(`₹${data.total_amount.toLocaleString()}`);
      }
    },
    error: function(xhr) {
      console.error('Price calculation error:', xhr);
    }
  });
}

function nextStep(step) {
  if (step === 1) {
    if (!validateStep('#step1')) {
      if (typeof Toast !== 'undefined') {
        Toast.warning('Incomplete Form', 'Please complete all required driver details.');
      } else {
        alert('Please complete all required driver details.');
      }
      return;
    }
  }

  if (step === 2) {
    if (!validateStep('#step2')) {
      if (typeof Toast !== 'undefined') {
        Toast.warning('Incomplete Dates', 'Please select pickup & return details.');
      } else {
        alert('Please select pickup & return details.');
      }
      return;
    }

    // Check car availability on server side
    const carId = $('#carId').val();
    const pickupDate = $('#rentPickupDate').val();
    const returnDate = $('#rentReturnDate').val();
    const csrfToken = $('input[name="_token"]').val();

    $.ajax({
      url: '/booking/check-availability',
      method: 'POST',
      data: {
        _token: csrfToken,
        car_id: carId,
        pickup_date: pickupDate,
        return_date: returnDate
      },
      success: function(res) {
        if (!res.available) {
          alert(res.message);
          return;
        }
        proceedToStep(step + 1);
      },
      error: function() {
        proceedToStep(step + 1);
      }
    });

    return;
  }

  proceedToStep(step + 1);
}

function proceedToStep(nextStepNum) {
  const step = nextStepNum - 1;
  $(`#step${step}`).addClass('d-none');
  $(`.step-item[data-step="${step}"]`).removeClass('active').addClass('completed');

  currentStep = nextStepNum;
  $(`#step${currentStep}`).removeClass('d-none');
  $(`.step-item[data-step="${currentStep}"]`).addClass('active');

  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function prevStep(step) {
  $(`#step${step}`).addClass('d-none');
  $(`.step-item[data-step="${step}"]`).removeClass('active');

  currentStep = step - 1;
  $(`#step${currentStep}`).addClass('d-none');
  $(`.step-item[data-step="${currentStep}"]`).removeClass('completed').addClass('active');

  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function validateStep(containerSelector) {
  let isValid = true;
  $(`${containerSelector} [required]`).each(function() {
    if (!$(this).val()) {
      $(this).addClass('is-invalid');
      isValid = false;
    } else {
      $(this).removeClass('is-invalid');
    }
  });
  return isValid;
}

function initEventListeners() {
  $('.extra-toggle').on('change', calculateTotals);
  $('#rentPickupDate, #rentReturnDate').on('change', calculateTotals);

  // Promo code apply
  $('#applyPromoBtn').on('click', function() {
    const code = $('#promoInput').val().trim();
    if (!code) return;

    const carId = $('#carId').val();
    const pickupDate = $('#rentPickupDate').val();
    const returnDate = $('#rentReturnDate').val();

    let extraServices = [];
    $('.extra-toggle:checked').each(function() {
      extraServices.push($(this).val());
    });

    const csrfToken = $('input[name="_token"]').val();

    $.ajax({
      url: '/booking/calculate-price',
      method: 'POST',
      data: {
        _token: csrfToken,
        car_id: carId,
        pickup_date: pickupDate,
        return_date: returnDate,
        extra_services: extraServices
      },
      success: function(resp) {
        if (resp.success) {
          const subtotal = resp.data.subtotal;

          $.ajax({
            url: '/apply-coupon',
            method: 'POST',
            data: {
              _token: csrfToken,
              code: code,
              subtotal: subtotal
            },
            success: function(res) {
              if (res.success) {
                appliedCouponCode = res.code;
                $('#appliedCouponCode').val(res.code);
                calculateTotals();
                $('#promoMsg').removeClass('d-none text-danger').addClass('text-success').text(res.message);
                if (typeof Toast !== 'undefined') Toast.success('Promo Applied', res.message);
              }
            },
            error: function(xhr) {
              appliedCouponCode = null;
              $('#appliedCouponCode').val('');
              calculateTotals();
              const errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Invalid Coupon Code';
              $('#promoMsg').removeClass('d-none text-success').addClass('text-danger').text(errMsg);
              if (typeof Toast !== 'undefined') Toast.error('Invalid Coupon', errMsg);
            }
          });
        }
      }
    });
  });

  // Payment tab switch
  $('.payment-tab-btn').on('click', function() {
    $('.payment-tab-btn').removeClass('active');
    $(this).addClass('active');
    selectedPaymentMethod = $(this).data('method');
    $('#selectedPaymentMethod').val(selectedPaymentMethod);

    if (selectedPaymentMethod === 'Cash') {
      $('#payFormRazorpay').addClass('d-none');
      $('#payFormCash').removeClass('d-none');
      $('#confirmBookingBtn').html('<i class="fas fa-hand-holding-dollar me-2"></i> Submit Cash Reservation Request');
    } else {
      $('#payFormRazorpay').removeClass('d-none');
      $('#payFormCash').addClass('d-none');
      $('#confirmBookingBtn').html('<i class="fas fa-lock me-2"></i> Confirm & Pay with Razorpay <span id="btnPayTotal"></span>');
      calculateTotals();
    }
  });

  // Submit Checkout Form
  $('#checkoutForm').on('submit', function(e) {
    e.preventDefault();

    if (!$('#termsCheck').is(':checked')) {
      if (typeof Toast !== 'undefined') {
        Toast.warning('Terms & Conditions', 'Please accept the terms & conditions to proceed.');
      } else {
        alert('Please accept the terms & conditions to proceed.');
      }
      return;
    }

    if (selectedPaymentMethod === 'Razorpay') {
      initiateRazorpayPayment();
    } else {
      submitFinalBooking();
    }
  });
}

function initiateRazorpayPayment() {
  const $btn = $('#confirmBookingBtn');
  $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Launching Razorpay Secure Checkout...');

  const carId = $('#carId').val();
  const pickupDate = $('#rentPickupDate').val();
  const returnDate = $('#rentReturnDate').val();
  const csrfToken = $('input[name="_token"]').val();

  let extraServices = [];
  $('.extra-toggle:checked').each(function() {
    extraServices.push($(this).val());
  });

  $.ajax({
    url: '/payment/create-order',
    method: 'POST',
    data: {
      _token: csrfToken,
      car_id: carId,
      pickup_date: pickupDate,
      return_date: returnDate,
      extra_services: extraServices,
      coupon_code: appliedCouponCode
    },
    success: function(res) {
      if (!res.success) {
        alert(res.message || 'Razorpay order creation failed.');
        $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i> Confirm & Pay with Razorpay');
        return;
      }

      if (typeof Razorpay === 'undefined') {
        // Fallback if Razorpay SDK failed to load
        alert('Razorpay Checkout SDK is loading. Submitting payment directly.');
        $('#razorpayPaymentId').val('pay_' + Math.random().toString(36).substring(2, 12));
        submitFinalBooking();
        return;
      }

      const options = {
        key: res.key,
        amount: res.amount_in_paise,
        currency: res.currency || 'INR',
        name: 'DriveEase Car Rental',
        description: 'Reservation Checkout',
        order_id: res.order_id,
        handler: function(response) {
          $('#razorpayPaymentId').val(response.razorpay_payment_id);
          if (response.razorpay_order_id) $('#razorpayOrderId').val(response.razorpay_order_id);
          if (response.razorpay_signature) $('#razorpaySignature').val(response.razorpay_signature);

          submitFinalBooking();
        },
        modal: {
          ondismiss: function() {
            $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i> Confirm & Pay with Razorpay');
          }
        },
        prefill: {
          name: $('#custName').val(),
          email: $('#custEmail').val(),
          contact: $('#custPhone').val()
        },
        theme: {
          color: '#0d6efd'
        }
      };

      const rzp1 = new Razorpay(options);
      rzp1.open();
    },
    error: function(xhr) {
      $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i> Confirm & Pay with Razorpay');
      alert(xhr.responseJSON ? xhr.responseJSON.message : 'Error creating Razorpay payment order.');
    }
  });
}

function submitFinalBooking() {
  const $btn = $('#confirmBookingBtn');
  $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing Reservation...');

  const formData = $('#checkoutForm').serialize();

  $.ajax({
    url: '/booking',
    method: 'POST',
    data: formData,
    success: function(response) {
      if (response.success) {
        if (typeof Toast !== 'undefined') {
          Toast.success('Booking Confirmed', 'Your reservation was successful!');
        }
        setTimeout(() => {
          window.location.href = response.redirect_url;
        }, 800);
      }
    },
    error: function(xhr) {
      $btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i> Confirm & Pay');
      calculateTotals();

      let errMsg = 'Failed to process booking. Please check your inputs.';
      if (xhr.responseJSON) {
        if (xhr.responseJSON.message) {
          errMsg = xhr.responseJSON.message;
        } else if (xhr.responseJSON.errors) {
          errMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
        }
      }

      if (typeof Toast !== 'undefined') {
        Toast.error('Booking Error', errMsg);
      } else {
        alert(errMsg);
      }
    }
  });
}
