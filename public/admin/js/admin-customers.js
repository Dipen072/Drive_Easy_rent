/**
 * DriveEase — Admin Customers Logic
 * admin-customers.js
 */

$(document).ready(function() {
  Storage.seed();
  renderCustTable();

  $('#custSearchInput, #custStatusFilter').on('input change', renderCustTable);
  $('#toggleSidebarBtn').on('click', () => $('#adminSidebar').toggleClass('collapsed'));
  $('#themeToggleBtn').on('click', () => Storage.toggleTheme());
});

function renderCustTable() {
  let custs = Storage.getCustomers();
  const q = $('#custSearchInput').val().toLowerCase().trim();
  const status = $('#custStatusFilter').val();

  if (q) {
    custs = custs.filter(c => c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q) || c.city.toLowerCase().includes(q));
  }

  if (status !== 'all') {
    if (status === 'Active') {
      custs = custs.filter(c => c.status === 'Active');
    } else {
      custs = custs.filter(c => c.status !== 'Active');
    }
  }

  if (!custs.length) {
    $('#adminCustomersTableBody').html('<tr><td colspan="9" class="text-center p-4 text-muted">No customers found matching filter.</td></tr>');
    return;
  }

  const html = custs.map(c => `
    <tr>
      <td><img src="${c.avatar || EXTRA_IMAGES.avatar1}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;"></td>
      <td class="fw-700 font-mono">${c.id}</td>
      <td class="fw-700">${c.name}</td>
      <td>${c.email}</td>
      <td class="font-sm text-secondary">${c.phone}</td>
      <td class="font-sm">${c.city}</td>
      <td class="fw-700 text-primary">${c.bookings || 0} Trips</td>
      <td><span class="badge-${c.status === 'Active' ? 'active' : 'unavailable'}">${c.status}</span></td>
      <td>
        <div class="d-flex gap-1">
          <button class="btn btn-sm btn-outline-primary" onclick="viewCustomerDetails('${c.id}')" title="Customer Details & History"><i class="fas fa-eye"></i> Info</button>
          <button class="btn btn-sm btn-outline-${c.status === 'Active' ? 'warning' : 'success'}" onclick="toggleBlockCustomer('${c.id}')" title="${c.status === 'Active' ? 'Block Customer' : 'Unblock Customer'}">
            <i class="fas fa-${c.status === 'Active' ? 'user-slash' : 'user-check'}"></i> ${c.status === 'Active' ? 'Block' : 'Unblock'}
          </button>
        </div>
      </td>
    </tr>
  `).join('');

  $('#adminCustomersTableBody').html(html);
}

function toggleBlockCustomer(id) {
  const custs = Storage.getCustomers();
  const c = custs.find(x => x.id === id);
  if (c) {
    c.status = c.status === 'Active' ? 'Blocked' : 'Active';
    Storage.saveCustomer(c);
    Toast.success('Status Updated', `${c.name} status updated to ${c.status}.`);
    renderCustTable();
  }
}

function viewCustomerDetails(id) {
  const c = Storage.getCustomerById(id);
  if (!c) return;

  const userBookings = Storage.getBookingsByCustomer(c.id);

  const bookingsHtml = userBookings.length ? userBookings.map(b => `
    <tr>
      <td class="font-mono fw-700">${b.id}</td>
      <td>${b.carName}</td>
      <td class="font-sm">${b.pickupDate} → ${b.returnDate}</td>
      <td class="fw-700 text-primary">₹${b.total.toLocaleString()}</td>
      <td><span class="badge-${b.status.toLowerCase()}">${b.status}</span></td>
    </tr>
  `).join('') : '<tr><td colspan="5" class="text-center text-muted font-sm">No bookings recorded.</td></tr>';

  const paymentsHtml = userBookings.length ? userBookings.map((b, idx) => `
    <tr>
      <td class="font-mono fw-700">TXN${1000 + idx}</td>
      <td class="font-mono">${b.id}</td>
      <td class="fw-700 text-dark">₹${b.total.toLocaleString()}</td>
      <td>${b.payment}</td>
      <td><span class="badge-${b.paymentStatus === 'Paid' ? 'completed' : 'pending'}">${b.paymentStatus}</span></td>
    </tr>
  `).join('') : '<tr><td colspan="5" class="text-center text-muted font-sm">No payment transactions recorded.</td></tr>';

  $('#customerModalBody').html(`
    <div class="row align-items-center g-3 mb-4 p-3 bg-surface-2 rounded-card">
      <div class="col-auto">
        <img src="${c.avatar || EXTRA_IMAGES.avatar1}" class="rounded-circle" style="width:70px;height:70px;object-fit:cover;">
      </div>
      <div class="col">
        <h5 class="fw-800 font-heading mb-1">${c.name} <span class="badge-${c.status === 'Active' ? 'active' : 'unavailable'} ms-2">${c.status}</span></h5>
        <div class="font-sm text-muted mb-1"><i class="fas fa-envelope text-primary me-1"></i> ${c.email} · <i class="fas fa-phone text-success me-1"></i> ${c.phone}</div>
        <div class="font-sm text-secondary"><i class="fas fa-map-marker-alt text-danger me-1"></i> ${c.city}, ${c.state} · Registered: ${c.registered}</div>
      </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-tabs tabs-brand mb-3" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#custTabBookings">Booking History (${userBookings.length})</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#custTabPayments">Payment History</button></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="custTabBookings">
        <div class="table-responsive">
          <table class="table table-bordered table-sm font-sm">
            <thead class="table-light">
              <tr><th>Booking ID</th><th>Car</th><th>Dates</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>${bookingsHtml}</tbody>
          </table>
        </div>
      </div>

      <div class="tab-pane fade" id="custTabPayments">
        <div class="table-responsive">
          <table class="table table-bordered table-sm font-sm">
            <thead class="table-light">
              <tr><th>Txn ID</th><th>Booking ID</th><th>Amount</th><th>Method</th><th>Status</th></tr>
            </thead>
            <tbody>${paymentsHtml}</tbody>
          </table>
        </div>
      </div>
    </div>
  `);

  const modal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
  modal.show();
}
