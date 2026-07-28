@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Manage Reservations & Dispatch</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Bookings</span></div>
    </div>
  </div>

  <!-- FILTER TABS -->
  <ul class="nav nav-pills tabs-brand mb-4" id="adminBookingTabs">
    <li class="nav-item"><button class="nav-link active" data-status="all">All Bookings</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Pending">Pending</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Confirmed">Confirmed</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Active">Active</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Completed">Completed</button></li>
    <li class="nav-item"><button class="nav-link" data-status="Cancelled">Cancelled</button></li>
  </ul>

  <div class="admin-table-card">
    <div class="admin-table-header">
      <div class="admin-search-bar">
        <div class="admin-search-input">
          <i class="fas fa-search"></i>
          <input type="text" class="form-control" id="bookingSearchInput" placeholder="Search ID, customer, car...">
        </div>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>Booking ID</th><th>Customer</th><th>Car</th><th>Pickup</th><th>Return</th><th>Total</th><th>Status</th><th>Quick Actions</th><th>Details</th></tr>
        </thead>
        <tbody id="adminBookingsTableBody">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- BOOKING DETAILS MODAL -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700"><i class="fas fa-calendar-check text-primary me-2"></i>Reservation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="bookingModalBody">
        <!-- Rendered by JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{url('admin/js/admin-bookings.js')}}"></script>
@endsection
