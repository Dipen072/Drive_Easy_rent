@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  
  <div class="admin-page-header">
    <div>
      <h4>System Notifications & Alerts</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Notifications</span></div>
    </div>
    <button class="btn btn-outline-brand btn-sm-brand" id="adminMarkReadBtn"><i class="fas fa-check-double me-1"></i> Mark All as Read</button>
  </div>

  <!-- NOTIFICATION CATEGORY TABS -->
  <ul class="nav nav-pills tabs-brand mb-4" id="notifTypeTabs">
    <li class="nav-item"><button class="nav-link active" data-type="all">All Notifications</button></li>
    <li class="nav-item"><button class="nav-link" data-type="booking">Booking Notifications</button></li>
    <li class="nav-item"><button class="nav-link" data-type="payment">Payment Notifications</button></li>
    <li class="nav-item"><button class="nav-link" data-type="customer">Customer Notifications</button></li>
    <li class="nav-item"><button class="nav-link" data-type="system">System Notifications</button></li>
  </ul>

  <div class="bg-surface p-4 rounded-card border shadow-xs max-w-800" id="adminNotifContainer">
    <!-- Rendered by JS -->
  </div>

</div>
@endsection

@section('scripts')
<script>
  let currentNotifType = 'all';

  const ALL_ADMIN_NOTIFS = [
    { id: 1, title: 'New Reservation Placed', desc: 'Customer Arjun Sharma booked BMW 3 Series (BK001)', time: '10 mins ago', cat: 'booking', icon: 'fa-calendar-plus' },
    { id: 2, title: 'Payment Recieved', desc: 'Payment of ₹18,290 received via UPI for BK001', time: '12 mins ago', cat: 'payment', icon: 'fa-credit-card' },
    { id: 3, title: 'New Customer Registered', desc: 'Divya Menon created an account from Chennai', time: '1 hour ago', cat: 'customer', icon: 'fa-user-plus' },
    { id: 4, title: 'Vehicle Availability Warning', desc: 'Audi A4 (C006) was marked unavailable for maintenance', time: '3 hours ago', cat: 'system', icon: 'fa-triangle-exclamation' },
    { id: 5, title: 'Trip Completed', desc: 'Customer Priya Patel returned Tata Harrier (BK004)', time: '5 hours ago', cat: 'booking', icon: 'fa-flag-checkered' },
    { id: 6, title: 'Coupon Redemption Limit Alert', desc: 'Promo code WEEKEND20 reached 128 usages', time: '1 day ago', cat: 'system', icon: 'fa-ticket' }
  ];

  $(document).ready(function() {
    Storage.seed();
    renderAdminNotifs();

    $('#notifTypeTabs button').on('click', function() {
      $('#notifTypeTabs button').removeClass('active');
      $(this).addClass('active');
      currentNotifType = $(this).data('type');
      renderAdminNotifs();
    });

    $('#adminMarkReadBtn').on('click', function() {
      Storage.markAllRead();
      Toast.info('Notifications Read', 'All system alerts marked as read.');
    });
  });

  function renderAdminNotifs() {
    let list = ALL_ADMIN_NOTIFS;
    if (currentNotifType !== 'all') {
      list = list.filter(n => n.cat === currentNotifType);
    }

    if (!list.length) {
      $('#adminNotifContainer').html('<div class="text-center p-4 text-muted font-sm">No notifications in this category.</div>');
      return;
    }

    const html = list.map(n => `
      <div class="d-flex align-items-start gap-3 p-3 border-bottom">
        <div class="notif-icon bg-primary-lighter text-primary" style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="fas ${n.icon}"></i>
        </div>
        <div class="flex-fill">
          <div class="fw-700 font-sm mb-1">${n.title} <span class="badge bg-light text-dark font-xs ms-2 uppercase">${n.cat}</span></div>
          <p class="font-sm text-secondary mb-1">${n.desc}</p>
          <span class="font-xs text-muted">${n.time}</span>
        </div>
      </div>
    `).join('');

    $('#adminNotifContainer').html(html);
  }
</script>
@endsection
