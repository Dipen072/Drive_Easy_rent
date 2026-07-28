/**
 * DriveEase — Admin Bookings Logic with Quick Actions & Details Modal
 * admin-bookings.js
 */

let currentStatusFilter = 'all';

$(document).ready(function() {
  Storage.seed();

  // Read status from URL if present
  const urlParams = new URLSearchParams(window.location.search);
  const statusParam = urlParams.get('status');

  if (statusParam) {
    currentStatusFilter = statusParam;
    $(`#adminBookingTabs button`).removeClass('active');
    $(`#adminBookingTabs button[data-status="${statusParam}"]`).addClass('active');
  }

  renderAdminBookingsTable();

  $('#adminBookingTabs button').on('click', function() {
    $('#adminBookingTabs button').removeClass('active');
    $(this).addClass('active');
    currentStatusFilter = $(this).data('status');
    renderAdminBookingsTable();
  });

  $('#bookingSearchInput').on('input', renderAdminBookingsTable);

  $('#toggleSidebarBtn').on('click', () => $('#adminSidebar').toggleClass('collapsed'));
  $('#themeToggleBtn').on('click', () => Storage.toggleTheme());
});

function renderAdminBookingsTable() {
  let bookings = Storage.getBookings();
  const q = $('#bookingSearchInput').val().toLowerCase().trim();

  if (currentStatusFilter !== 'all') {
    bookings = bookings.filter(b => b.status.toLowerCase() === currentStatusFilter.toLowerCase());
  }

  if (q) {
    bookings = bookings.filter(b => b.id.toLowerCase().includes(q) || b.customerName.toLowerCase().includes(q) || b.carName.toLowerCase().includes(q));
  }

  if (!bookings.length) {
    $('#adminBookingsTableBody').html('<tr><td colspan="9" class="text-center p-4 text-muted">No reservations found matching criteria.</td></tr>');
    return;
  }

  const html = bookings.map(b => {
    let actionButtons = '';
    if (b.status === 'Pending') {
      actionButtons = `
        <button class="btn btn-sm btn-success" onclick="changeStatus('${b.id}', 'Confirmed')" title="Approve Reservation"><i class="fas fa-check"></i> Approve</button>
        <button class="btn btn-sm btn-outline-danger" onclick="changeStatus('${b.id}', 'Cancelled')" title="Reject Reservation"><i class="fas fa-xmark"></i> Reject</button>
      `;
    } else if (b.status === 'Confirmed') {
      actionButtons = `
        <button class="btn btn-sm btn-primary" onclick="changeStatus('${b.id}', 'Active')" title="Mark Trip Active / Key Handover"><i class="fas fa-key"></i> Handover (Active)</button>
        <button class="btn btn-sm btn-outline-danger" onclick="changeStatus('${b.id}', 'Cancelled')"><i class="fas fa-ban"></i> Cancel</button>
      `;
    } else if (b.status === 'Active') {
      actionButtons = `
        <button class="btn btn-sm btn-info text-white" onclick="changeStatus('${b.id}', 'Completed')" title="Mark Vehicle Returned / Completed"><i class="fas fa-flag-checkered"></i> Complete Trip</button>
      `;
    } else {
      actionButtons = `<span class="text-muted font-xs">No Actions Needed</span>`;
    }

    return `
      <tr>
        <td class="fw-700 font-mono">${b.id}</td>
        <td class="fw-600">${b.customerName}</td>
        <td>${b.carName}</td>
        <td class="font-sm">${b.pickupDate}</td>
        <td class="font-sm">${b.returnDate}</td>
        <td class="fw-700 text-primary">₹${b.total.toLocaleString()}</td>
        <td><span class="badge-${b.status.toLowerCase()}">${b.status}</span></td>
        <td><div class="d-flex gap-1">${actionButtons}</div></td>
        <td>
          <button class="btn btn-sm btn-outline-secondary" onclick="viewBookingDetails('${b.id}')"><i class="fas fa-eye"></i> Details</button>
        </td>
      </tr>
    `;
  }).join('');

  $('#adminBookingsTableBody').html(html);
}

function changeStatus(id, newStatus) {
  Storage.updateBookingStatus(id, newStatus);
  Toast.success('Status Updated', `Booking ${id} status set to ${newStatus}.`);
  renderAdminBookingsTable();
}

function viewBookingDetails(id) {
  const b = Storage.getBookingById(id);
  if (!b) return;

  $('#bookingModalBody').html(`
    <div class="row align-items-center g-3 mb-4 p-3 bg-surface-2 rounded-card">
      <div class="col-md-6">
        <div class="font-xs text-muted">BOOKING REFERENCE</div>
        <h4 class="fw-800 font-mono text-primary mb-0">${b.id}</h4>
        <span class="badge-${b.status.toLowerCase()} mt-1">${b.status}</span>
      </div>
      <div class="col-md-6 text-md-end">
        <div class="font-xs text-muted">TOTAL RESERVATION COST</div>
        <h3 class="fw-800 text-dark mb-0">₹${b.total.toLocaleString()}</h3>
        <span class="badge bg-light text-dark font-xs border">${b.payment} (${b.paymentStatus})</span>
      </div>
    </div>

    <div class="row g-3 mb-4 font-sm">
      <div class="col-md-6">
        <div class="p-3 border rounded">
          <div class="fw-700 text-primary mb-2"><i class="fas fa-user me-1"></i> Customer Information</div>
          <div><strong>Name:</strong> ${b.customerName}</div>
          <div><strong>Email:</strong> ${b.email || 'customer@email.com'}</div>
          <div><strong>Phone:</strong> ${b.phone || '+91 98765 43210'}</div>
          <div><strong>License:</strong> ${b.license || 'MH-0120230012345'}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="p-3 border rounded">
          <div class="fw-700 text-primary mb-2"><i class="fas fa-car me-1"></i> Vehicle & Rental Terms</div>
          <div><strong>Car Rented:</strong> ${b.carName}</div>
          <div><strong>Pickup Location:</strong> ${b.pickupLoc}</div>
          <div><strong>Dates:</strong> ${b.pickupDate} → ${b.returnDate} (${b.days} Days)</div>
        </div>
      </div>
    </div>

    <div class="bg-surface-2 p-3 rounded font-sm">
      <div class="fw-700 mb-2 border-bottom pb-1">Price Breakdown</div>
      <div class="d-flex justify-content-between py-1"><span>Daily Tariff (${b.days} days):</span><strong>₹${b.basePrice.toLocaleString()}</strong></div>
      <div class="d-flex justify-content-between py-1"><span>Optional Add-ons:</span><strong>₹${b.extras.toLocaleString()}</strong></div>
      <div class="d-flex justify-content-between py-1"><span>GST (18%):</span><strong>₹${b.tax.toLocaleString()}</strong></div>
      <div class="d-flex justify-content-between py-1 text-danger"><span>Promo Discount:</span><strong>-₹${b.discount.toLocaleString()}</strong></div>
      <div class="d-flex justify-content-between py-2 border-top fw-800 fs-5 text-primary"><span>Total Paid:</span><span>₹${b.total.toLocaleString()}</span></div>
    </div>
  `);

  const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
  modal.show();
}
