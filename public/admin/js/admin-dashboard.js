/**
 * DriveEase — Admin Dashboard Logic & Charts
 * admin-dashboard.js
 */

$(document).ready(function() {
  Storage.seed();

  // Sidebar toggle
  $('#toggleSidebarBtn').on('click', function() {
    $('#adminSidebar').toggleClass('collapsed');
    $('#adminTopbar, .admin-main').toggleClass('sidebar-collapsed');
  });

  // Dark Mode Toggle
  $('#themeToggleBtn').on('click', function() {
    Storage.toggleTheme();
  });

  updateAdminKPIs();
  initDashboardCharts();
  renderRecentAdminBookings();
});

function updateAdminKPIs() {
  const stats = Storage.getDashboardStats();

  $('#kpiCars').text(stats.totalCars);
  $('#kpiAvail').text(stats.availableCars);
  $('#kpiCustomers').text(stats.totalCustomers);
  $('#kpiBookings').text(stats.total);
  $('#kpiPending, #pendingBookingBadge').text(stats.pending);
  $('#kpiRevenue').text(`₹${(stats.revenue / 100000).toFixed(1)}L`);
}

function initDashboardCharts() {
  // Line Chart: Revenue
  const ctxRev = document.getElementById('revenueChart');
  if (ctxRev) {
    new Chart(ctxRev.getContext('2d'), {
      type: 'line',
      data: {
        labels: MONTHLY_REVENUE.labels,
        datasets: [{
          label: 'Revenue (₹)',
          data: MONTHLY_REVENUE.data,
          borderColor: '#2563eb',
          backgroundColor: 'rgba(37, 99, 235, 0.1)',
          fill: true,
          tension: 0.4,
          borderWidth: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { grid: { color: '#f1f5f9' }, ticks: { callback: v => '₹' + (v/1000) + 'k' } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // Doughnut Chart: Status
  const ctxStatus = document.getElementById('statusChart');
  if (ctxStatus) {
    const stats = Storage.getBookingStats();
    new Chart(ctxStatus.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: ['Completed', 'Active', 'Confirmed', 'Pending', 'Cancelled'],
        datasets: [{
          data: [stats.completed, stats.active, stats.confirmed, stats.pending, stats.cancelled],
          backgroundColor: ['#10b981', '#3b82f6', '#06b6d4', '#f59e0b', '#ef4444']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  }
}

function renderRecentAdminBookings() {
  const bookings = Storage.getBookings().slice(0, 6);
  const html = bookings.map(b => `
    <tr>
      <td class="fw-700 font-mono">${b.id}</td>
      <td class="fw-600">${b.customerName}</td>
      <td>${b.carName}</td>
      <td>${b.pickupDate}</td>
      <td>${b.returnDate}</td>
      <td class="fw-700 text-primary">₹${b.total.toLocaleString()}</td>
      <td><span class="badge-${b.status.toLowerCase()}">${b.status}</span></td>
      <td>
        <div class="dropdown action-dropdown">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#" onclick="quickStatus('${b.id}', 'Confirmed')"><i class="fas fa-check text-success"></i> Confirm</a></li>
            <li><a class="dropdown-item" href="#" onclick="quickStatus('${b.id}', 'Active')"><i class="fas fa-key text-info"></i> Mark Active</a></li>
            <li><a class="dropdown-item" href="#" onclick="quickStatus('${b.id}', 'Completed')"><i class="fas fa-flag-checkered text-primary"></i> Complete</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item danger" href="#" onclick="quickStatus('${b.id}', 'Cancelled')"><i class="fas fa-ban text-danger"></i> Cancel</a></li>
          </ul>
        </div>
      </td>
    </tr>
  `).join('');

  $('#adminRecentBookingsTable').html(html);
}

function quickStatus(id, status) {
  Storage.updateBookingStatus(id, status);
  Toast.success('Status Updated', `Booking ${id} set to ${status}.`);
  updateAdminKPIs();
  renderRecentAdminBookings();
}
