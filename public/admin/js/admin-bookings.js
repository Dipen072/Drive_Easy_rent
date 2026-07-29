/**
 * DriveEase — Admin Bookings Controller JS with SweetAlert & Backend AJAX
 * admin-bookings.js
 */

let currentStatusFilter = 'all';

$(document).ready(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const statusParam = urlParams.get('status');

  if (statusParam) {
    currentStatusFilter = statusParam;
    $(`#adminBookingTabs button`).removeClass('active');
    $(`#adminBookingTabs button[data-status="${statusParam}"]`).addClass('active');
    filterTable();
  }

  $('#adminBookingTabs button').on('click', function() {
    $('#adminBookingTabs button').removeClass('active');
    $(this).addClass('active');
    currentStatusFilter = $(this).data('status');
    filterTable();
  });

  $('#bookingSearchInput').on('input', filterTable);
});

function filterTable() {
  const q = $('#bookingSearchInput').val().toLowerCase().trim();

  $('#adminBookingsTableBody tr').each(function() {
    const status = $(this).data('status');
    const searchData = $(this).data('search') ? $(this).data('search').toString() : '';

    let matchStatus = (currentStatusFilter === 'all' || (status && status.toLowerCase() === currentStatusFilter.toLowerCase()));
    let matchSearch = (!q || searchData.includes(q));

    if (matchStatus && matchSearch) {
      $(this).removeClass('d-none');
    } else {
      $(this).addClass('d-none');
    }
  });
}

function updateAdminStatus(bookingId, newStatus) {
  let confirmTitle = `Set status to ${newStatus}?`;
  let confirmText = `Are you sure you want to change this reservation status to ${newStatus}?`;

  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: confirmTitle,
      text: confirmText,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Update Status',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        sendAdminStatusChange(bookingId, newStatus);
      }
    });
  } else {
    if (confirm(confirmText)) {
      sendAdminStatusChange(bookingId, newStatus);
    }
  }
}

function sendAdminStatusChange(bookingId, newStatus) {
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
    url: `/admin/bookings/${bookingId}/status`,
    method: 'POST',
    data: {
      _token: csrfToken,
      status: newStatus
    },
    success: function(res) {
      if (res.success) {
        if (typeof Swal !== 'undefined') {
          Swal.fire('Updated!', res.message, 'success').then(() => window.location.reload());
        } else {
          alert(res.message);
          window.location.reload();
        }
      }
    },
    error: function(xhr) {
      const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update status.';
      if (typeof Swal !== 'undefined') {
        Swal.fire('Error', msg, 'error');
      } else {
        alert(msg);
      }
    }
  });
}

function approveCashPayment(bookingId) {
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  if (confirm('Approve cash payment for this booking?')) {
    $.ajax({
      url: `/admin/bookings/${bookingId}/approve-cash`,
      method: 'POST',
      data: { _token: csrfToken },
      success: function(res) {
        alert(res.message);
        window.location.reload();
      },
      error: function(xhr) {
        alert(xhr.responseJSON ? xhr.responseJSON.message : 'Error approving cash payment.');
      }
    });
  }
}

function viewBookingDetails(bookingId) {
  $.ajax({
    url: `/admin/bookings/${bookingId}`,
    method: 'GET',
    success: function(res) {
      if (!res.success || !res.booking) return;

      const b = res.booking;
      const c = b.customer || {};
      const car = b.car || {};
      const pLoc = b.pickup_location || {};
      const dLoc = b.dropoff_location || {};
      const p = b.payment || {};

      const modalHtml = `
        <div class="row align-items-center g-3 mb-4 p-3 bg-surface-2 rounded-card">
          <div class="col-md-6">
            <div class="font-xs text-muted">BOOKING REFERENCE</div>
            <h4 class="fw-800 font-mono text-primary mb-0">${b.booking_number}</h4>
            <span class="badge bg-primary-lighter text-primary mt-1">${b.booking_status}</span>
          </div>
          <div class="col-md-6 text-md-end">
            <div class="font-xs text-muted">TOTAL RESERVATION COST</div>
            <h3 class="fw-800 text-dark mb-0">₹${parseFloat(b.total_amount).toLocaleString()}</h3>
            <span class="badge bg-light text-dark font-xs border">${p.payment_method || 'N/A'} (${b.payment_status})</span>
          </div>
        </div>

        <div class="row g-3 mb-4 font-sm">
          <div class="col-md-6">
            <div class="p-3 border rounded">
              <div class="fw-700 text-primary mb-2"><i class="fas fa-user me-1"></i> Customer Information</div>
              <div><strong>Name:</strong> ${c.first_name || ''} ${c.last_name || ''}</div>
              <div><strong>Email:</strong> ${c.email || 'N/A'}</div>
              <div><strong>Phone:</strong> ${c.phone || 'N/A'}</div>
              <div><strong>License:</strong> ${c.dl_number || 'N/A'}</div>
              <div><strong>Address:</strong> ${c.address || ''}, ${c.city || ''}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 border rounded">
              <div class="fw-700 text-primary mb-2"><i class="fas fa-car me-1"></i> Vehicle & Rental Terms</div>
              <div><strong>Car Rented:</strong> ${car.brand_name || ''} ${car.model_name || ''}</div>
              <div><strong>Pickup Location:</strong> ${pLoc.name || 'Branch Pickup'}</div>
              <div><strong>Drop-off Location:</strong> ${dLoc.name || 'Branch Dropoff'}</div>
              <div><strong>Dates:</strong> ${b.pickup_date} → ${b.return_date} (${b.rental_days} Days)</div>
            </div>
          </div>
        </div>

        <div class="bg-surface-2 p-3 rounded font-sm">
          <div class="fw-700 mb-2 border-bottom pb-1">Price Breakdown</div>
          <div class="d-flex justify-content-between py-1"><span>Daily Tariff (${b.rental_days} days):</span><strong>₹${parseFloat(b.base_price).toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>Optional Add-ons:</span><strong>₹${parseFloat(b.extras_amount).toLocaleString()}</strong></div>
          <div class="d-flex justify-content-between py-1"><span>GST (18%):</span><strong>₹${parseFloat(b.tax_amount).toLocaleString()}</strong></div>
          ${b.discount_amount > 0 ? `<div class="d-flex justify-content-between py-1 text-danger"><span>Promo Discount:</span><strong>-₹${parseFloat(b.discount_amount).toLocaleString()}</strong></div>` : ''}
          <div class="d-flex justify-content-between py-2 border-top fw-800 fs-5 text-primary"><span>Total Amount:</span><span>₹${parseFloat(b.total_amount).toLocaleString()}</span></div>
        </div>
      `;

      $('#bookingModalBody').html(modalHtml);
      const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
      modal.show();
    },
    error: function() {
      alert('Error fetching booking details.');
    }
  });
}
