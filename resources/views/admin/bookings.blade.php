@extends('admin.layout.structure')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

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
          <input type="text" class="form-control" id="bookingSearchInput" placeholder="Search ID, booking #, customer, car...">
        </div>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr>
            <th>Booking #</th>
            <th>Customer</th>
            <th>Car</th>
            <th>Pickup</th>
            <th>Return</th>
            <th>Rental Days</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Quick Actions</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody id="adminBookingsTableBody">
          @forelse($bookings as $b)
          <tr data-status="{{ $b->booking_status }}" data-search="{{ strtolower($b->booking_number . ' ' . $b->customer->first_name . ' ' . $b->customer->last_name . ' ' . $b->customer->phone . ' ' . $b->car->brand_name . ' ' . $b->car->model_name) }}">
            <td class="fw-700 font-mono text-primary">{{ $b->booking_number }}</td>
            <td>
              <div class="fw-600">{{ $b->customer->first_name }} {{ $b->customer->last_name }}</div>
              <div class="font-xs text-muted">{{ $b->customer->phone }}</div>
            </td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($b->car->image) }}" class="rounded" style="width:45px;height:30px;object-fit:cover;">
                <span>{{ $b->car->brand_name }} {{ $b->car->model_name }}</span>
              </div>
            </td>
            <td class="font-sm">
              <div>{{ $b->pickupLocation->name ?? 'Pickup' }}</div>
              <div class="font-xs text-muted">{{ \Carbon\Carbon::parse($b->pickup_date)->format('d M Y') }}</div>
            </td>
            <td class="font-sm">
              <div>{{ $b->dropoffLocation->name ?? 'Dropoff' }}</div>
              <div class="font-xs text-muted">{{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}</div>
            </td>
            <td class="fw-600 text-center">{{ $b->rental_days }} Days</td>
            <td class="fw-700 text-primary">₹{{ number_format($b->total_amount, 2) }}</td>
            <td>
              <span class="badge bg-{{ $b->payment_status === 'Paid' ? 'success' : 'warning' }}-light text-{{ $b->payment_status === 'Paid' ? 'success' : 'warning' }} font-xs fw-700">
                {{ $b->payment_status }} ({{ $b->payment->payment_method ?? 'N/A' }})
              </span>
            </td>
            <td>
              <span class="badge bg-{{ $b->booking_status === 'Confirmed' ? 'success' : ($b->booking_status === 'Active' ? 'primary' : ($b->booking_status === 'Completed' ? 'secondary' : ($b->booking_status === 'Cancelled' ? 'danger' : 'warning'))) }}-light text-{{ $b->booking_status === 'Confirmed' ? 'success' : ($b->booking_status === 'Active' ? 'primary' : ($b->booking_status === 'Completed' ? 'secondary' : ($b->booking_status === 'Cancelled' ? 'danger' : 'warning'))) }} font-xs fw-700">
                {{ $b->booking_status }}
              </span>
            </td>
            <td>
              <div class="d-flex gap-1">
                @if($b->booking_status === 'Pending')
                  <button class="btn btn-sm btn-success" onclick="updateAdminStatus({{ $b->id }}, 'Confirmed')" title="Approve Reservation"><i class="fas fa-check"></i> Approve</button>
                  <button class="btn btn-sm btn-outline-danger" onclick="updateAdminStatus({{ $b->id }}, 'Cancelled')" title="Reject Reservation"><i class="fas fa-xmark"></i> Reject</button>
                  @if($b->payment && $b->payment->payment_method === 'Cash')
                  <button class="btn btn-sm btn-warning text-dark" onclick="approveCashPayment({{ $b->id }})" title="Approve Cash Payment"><i class="fas fa-money-bill"></i> Cash</button>
                  @endif
                @elseif($b->booking_status === 'Confirmed')
                  <button class="btn btn-sm btn-primary" onclick="updateAdminStatus({{ $b->id }}, 'Active')" title="Mark Trip Active / Key Handover"><i class="fas fa-key"></i> Active</button>
                  <button class="btn btn-sm btn-outline-danger" onclick="updateAdminStatus({{ $b->id }}, 'Cancelled')"><i class="fas fa-ban"></i> Cancel</button>
                @elseif($b->booking_status === 'Active')
                  <button class="btn btn-sm btn-info text-white" onclick="updateAdminStatus({{ $b->id }}, 'Completed')" title="Mark Vehicle Returned / Completed"><i class="fas fa-flag-checkered"></i> Complete</button>
                @else
                  <span class="text-muted font-xs">No Actions</span>
                @endif
              </div>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-secondary" onclick="viewBookingDetails({{ $b->id }})"><i class="fas fa-eye"></i> Details</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="11" class="text-center p-4 text-muted">No reservations found in database.</td></tr>
          @endforelse
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
