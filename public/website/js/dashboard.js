/**
 * DriveEase — Customer Dashboard Logic
 * dashboard.js
 */

let authUser = null;

$(document).ready(function() {
  Storage.seed();
  authUser = Storage.getAuthUser() || { id: 'CU001', name: 'Arjun Sharma', email: 'arjun.sharma@email.com', phone: '+91 98765 43210' };

  $('#dashCustName').text(authUser.name.split(' ')[0]);

  // Handle Logout
  $('#dashLogout').on('click', function(e) {
    e.preventDefault();
    Storage.logout();
    Toast.success('Logged Out', 'You have logged out.');
    setTimeout(() => window.location.href = '../index.html', 1000);
  });

  // Page specific logic
  if (window.location.pathname.includes('index.html') || window.location.pathname.endsWith('/dashboard/')) {
    initDashboardHome();
  } else if (window.location.pathname.includes('bookings.html')) {
    initMyBookingsPage();
  } else if (window.location.pathname.includes('profile.html')) {
    initProfilePage();
  } else if (window.location.pathname.includes('wishlist.html')) {
    initWishlistPage();
  } else if (window.location.pathname.includes('notifications.html')) {
    initNotificationsPage();
  }
});

// ============================================================
// 1. DASHBOARD HOME
// ============================================================
function initDashboardHome() {
  const userBookings = Storage.getBookingsByCustomer(authUser.id);

  const upcoming  = userBookings.filter(b => b.status === 'Confirmed');
  const completed = userBookings.filter(b => b.status === 'Completed');
  const cancelled = userBookings.filter(b => b.status === 'Cancelled');

  $('#statTotalBookings').text(userBookings.length);
  $('#statUpcoming').text(upcoming.length);
  $('#statCompleted').text(completed.length);
  $('#statCancelled').text(cancelled.length);

  // Render Upcoming Booking Card
  const nextTrip = upcoming[0] || userBookings.find(b => b.status === 'Active');
  if (nextTrip) {
    const car = Storage.getCarById(nextTrip.carId);
    $('#upcomingCardContainer').html(`
      <div class="bg-surface border rounded-card shadow-xs p-4 border-start border-4 border-primary">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
          <span class="badge bg-primary-lighter text-primary fw-700 px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> ${nextTrip.status === 'Active' ? 'Active Trip In Progress' : 'Next Upcoming Trip'}</span>
          <span class="font-mono fw-700 text-muted font-sm">${nextTrip.id}</span>
        </div>
        <div class="row align-items-center g-3">
          <div class="col-md-3">
            <img src="${car ? car.image : ''}" class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;">
          </div>
          <div class="col-md-6">
            <h5 class="fw-800 font-heading mb-2">${nextTrip.carName}</h5>
            <div class="d-flex flex-wrap gap-3 font-sm text-secondary">
              <div><i class="fas fa-map-marker-alt text-primary me-1"></i> Pickup: <strong>${nextTrip.pickupName}</strong></div>
              <div><i class="fas fa-calendar-alt text-success me-1"></i> Dates: <strong>${nextTrip.pickupDate} → ${nextTrip.returnDate}</strong></div>
            </div>
          </div>
          <div class="col-md-3 text-md-end">
            <div class="fs-4 fw-800 text-primary mb-2">₹${nextTrip.total.toLocaleString()}</div>
            <button class="btn btn-outline-brand btn-sm" onclick="showBookingDetail('${nextTrip.id}')">View Trip Details</button>
          </div>
        </div>
      </div>
    `);
  } else {
    $('#upcomingCardContainer').html(`
      <div class="bg-surface border rounded-card p-4 text-center">
        <p class="text-muted mb-2">You don't have any active or upcoming trips scheduled right now.</p>
        <a href="../cars.html" class="btn btn-outline-brand btn-sm">Browse & Rent a Car</a>
      </div>
    `);
  }

  // Recent Bookings Table
  const recent = userBookings.slice(0, 5);
  if (recent.length) {
    const html = recent.map(b => `
      <tr>
        <td class="fw-700 font-mono">${b.id}</td>
        <td class="fw-600">${b.carName}</td>
        <td>${b.pickupDate}</td>
        <td>${b.returnDate}</td>
        <td class="fw-700 text-primary">₹${b.total.toLocaleString()}</td>
        <td><span class="badge-${b.status.toLowerCase()}">${b.status}</span></td>
        <td><button class="btn btn-sm btn-outline-secondary" onclick="showBookingDetail('${b.id}')"><i class="fas fa-eye"></i></button></td>
      </tr>
    `).join('');
    $('#recentBookingsTable').html(html);
  }
}

// ============================================================
// 2. MY BOOKINGS PAGE
// ============================================================
function initMyBookingsPage() {
  renderBookingsFilter('all');

  $('#bookingStatusTabs button').on('click', function() {
    $('#bookingStatusTabs button').removeClass('active');
    $(this).addClass('active');
    const status = $(this).data('status');
    renderBookingsFilter(status);
  });
}

function renderBookingsFilter(status = 'all') {
  let list = Storage.getBookingsByCustomer(authUser.id);
  if (status !== 'all') {
    list = list.filter(b => b.status.toLowerCase() === status.toLowerCase());
  }

  const $cont = $('#bookingsListContainer');
  if (!list.length) {
    $cont.html(`
      <div class="empty-state bg-surface border rounded-card">
        <div class="empty-state-icon"><i class="fas fa-calendar-xmark"></i></div>
        <h5>No ${status !== 'all' ? status : ''} Bookings Found</h5>
        <p>No reservations found under this status.</p>
      </div>
    `);
    return;
  }

  const html = list.map(b => `
    <div class="bg-surface border rounded-card p-4 shadow-xs">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
        <div>
          <span class="font-mono fw-700 text-muted me-2">${b.id}</span>
          <span class="badge-${b.status.toLowerCase()}">${b.status}</span>
        </div>
        <div class="font-sm text-muted">Booked on: ${b.createdAt || 'Recent'}</div>
      </div>
      <div class="row align-items-center g-3">
        <div class="col-md-7">
          <h5 class="fw-700 font-heading mb-2">${b.carName}</h5>
          <div class="row g-2 font-sm text-secondary">
            <div class="col-6"><i class="fas fa-map-marker-alt text-primary me-1"></i> Pickup: ${b.pickupName}</div>
            <div class="col-6"><i class="fas fa-map-marker-check text-success me-1"></i> Dropoff: ${b.dropoffName}</div>
            <div class="col-6"><i class="fas fa-calendar-alt text-primary me-1"></i> From: ${b.pickupDate} (${b.pickupTime})</div>
            <div class="col-6"><i class="fas fa-calendar-check text-success me-1"></i> To: ${b.returnDate} (${b.returnTime})</div>
          </div>
        </div>
        <div class="col-md-5 text-md-end border-start-md">
          <div class="fs-5 fw-800 text-primary mb-2">₹${b.total.toLocaleString()}</div>
          <div class="d-flex gap-2 justify-content-md-end">
            <button class="btn btn-outline-brand btn-sm" onclick="showBookingDetail('${b.id}')">View Info</button>
            ${b.status === 'Confirmed' ? `<button class="btn btn-outline-danger btn-sm" onclick="cancelBooking('${b.id}')">Cancel Trip</button>` : ''}
          </div>
        </div>
      </div>
    </div>
  `).join('');

  $cont.html(html);
}

function showBookingDetail(id) {
  const b = Storage.getBookingById(id);
  if (!b) return;

  $('#modalBookingBody').html(`
    <div class="p-2">
      <div class="d-flex justify-content-between mb-3">
        <strong class="font-mono text-primary">${b.id}</strong>
        <span class="badge-${b.status.toLowerCase()}">${b.status}</span>
      </div>
      <h6 class="fw-700">${b.carName}</h6>
      <p class="font-sm text-muted mb-3">${b.days} Days Rental (${b.pickupDate} to ${b.returnDate})</p>
      
      <div class="border-top border-bottom py-2 mb-3">
        <div class="d-flex justify-content-between font-sm py-1"><span>Base Tariff:</span><strong>₹${b.basePrice.toLocaleString()}</strong></div>
        <div class="d-flex justify-content-between font-sm py-1"><span>Add-ons:</span><strong>₹${b.extras.toLocaleString()}</strong></div>
        <div class="d-flex justify-content-between font-sm py-1"><span>GST Tax (18%):</span><strong>₹${b.tax.toLocaleString()}</strong></div>
        <div class="d-flex justify-content-between font-sm py-1 text-danger"><span>Discount:</span><strong>-₹${b.discount.toLocaleString()}</strong></div>
        <div class="d-flex justify-content-between font-md py-1 border-top fw-700 text-primary"><span>Total Amount:</span><span>₹${b.total.toLocaleString()}</span></div>
      </div>

      <div class="font-xs text-muted">Payment Method: <strong>${b.payment}</strong> (${b.paymentStatus})</div>
    </div>
  `);

  const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
  modal.show();
}

function cancelBooking(id) {
  Confirm.show({
    title: 'Cancel Booking?',
    message: 'Are you sure you want to cancel this trip reservation? Full refund applies if canceled 48h prior.',
    okText: 'Cancel Trip',
    type: 'danger',
    onConfirm: () => {
      Storage.updateBookingStatus(id, 'Cancelled');
      Toast.success('Trip Cancelled', 'Your booking status has been updated to Cancelled.');
      renderBookingsFilter('all');
    }
  });
}

// ============================================================
// 3. PROFILE PAGE
// ============================================================
function initProfilePage() {
  $('#profName').val(authUser.name);
  $('#profEmail').val(authUser.email);
  $('#profPhone').val(authUser.phone || '');
  $('#profLicense').val(authUser.license || '');
  $('#profCity').val(authUser.city || 'Mumbai');
  $('#profState').val(authUser.state || 'Maharashtra');

  $('#profileForm').on('submit', function(e) {
    e.preventDefault();
    authUser.name = $('#profName').val();
    authUser.phone = $('#profPhone').val();
    authUser.license = $('#profLicense').val();
    authUser.city = $('#profCity').val();
    authUser.state = $('#profState').val();

    Storage.saveCustomer(authUser);
    Storage.setAuthUser(authUser);
    Toast.success('Profile Saved', 'Personal information updated successfully.');
  });

  $('#passForm').on('submit', function(e) {
    e.preventDefault();
    Toast.success('Security Updated', 'Password changed successfully.');
    this.reset();
  });
}

// ============================================================
// 4. WISHLIST PAGE
// ============================================================
function initWishlistPage() {
  const cars = Storage.getWishlistedCars();
  const $grid = $('#wishlistGrid');

  if (!cars.length) {
    $grid.html(`
      <div class="col-12">
        <div class="empty-state bg-surface border rounded-card">
          <div class="empty-state-icon"><i class="fas fa-heart"></i></div>
          <h5>Your Wishlist is Empty</h5>
          <p>Explore our car catalog and tap the heart icon to save your favorite rides.</p>
          <a href="../cars.html" class="btn btn-outline-brand">Browse Vehicles</a>
        </div>
      </div>
    `);
    return;
  }

  $grid.html(cars.map(c => renderCarCard(c, '../')).join(''));
}

// ============================================================
// 5. NOTIFICATIONS PAGE
// ============================================================
function initNotificationsPage() {
  renderNotifs();

  $('#markAllReadBtn').on('click', function() {
    Storage.markAllRead();
    renderNotifs();
    Toast.info('Notifications Read', 'All notifications marked as read.');
  });
}

function renderNotifs() {
  const notifs = Storage.getNotifications();
  const $cont = $('#notificationsListContainer');

  if (!notifs.length) {
    $cont.html('<p class="text-muted mb-0">No notifications.</p>');
    return;
  }

  const html = notifs.map(n => `
    <div class="d-flex align-items-start gap-3 p-3 border-bottom ${n.read ? '' : 'bg-primary-lighter rounded'}">
      <div class="notif-icon bg-surface border" style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);">
        <i class="fas ${n.icon || 'fa-bell'}"></i>
      </div>
      <div class="flex-fill">
        <div class="fw-700 font-sm mb-1">${n.title}</div>
        <p class="font-sm text-secondary mb-1">${n.message}</p>
        <span class="font-xs text-muted">${n.time}</span>
      </div>
    </div>
  `).join('');

  $cont.html(html);
}
