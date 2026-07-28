@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  
  <div class="admin-page-header">
    <div>
      <h4>Dashboard Overview</h4>
      <div class="breadcrumb-brand">
        <span>Admin</span> <span class="sep">/</span> <span class="current">Overview</span>
      </div>
    </div>
    <a href="{{url('/admin/add-car')}}" class="btn btn-primary-brand btn-sm-brand"><i class="fas fa-plus me-1"></i> Add New Car</a>
  </div>

  <!-- 6 TOP STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card blue">
        <div class="admin-stat-icon"><i class="fas fa-car"></i></div>
        <span class="admin-stat-label">Total Vehicles</span>
        <div class="admin-stat-value" id="kpiCars">20</div>
        <div class="admin-stat-trend up"><i class="fas fa-arrow-up"></i> +12% <span class="trend-text">vs last mo</span></div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card green">
        <div class="admin-stat-icon"><i class="fas fa-check-circle"></i></div>
        <span class="admin-stat-label">Available Now</span>
        <div class="admin-stat-value" id="kpiAvail">18</div>
        <div class="admin-stat-trend up"><i class="fas fa-arrow-up"></i> 90% <span class="trend-text">fleet active</span></div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card amber">
        <div class="admin-stat-icon"><i class="fas fa-users"></i></div>
        <span class="admin-stat-label">Customers</span>
        <div class="admin-stat-value" id="kpiCustomers">15</div>
        <div class="admin-stat-trend up"><i class="fas fa-arrow-up"></i> +8% <span class="trend-text">growth</span></div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card purple">
        <div class="admin-stat-icon"><i class="fas fa-calendar-check"></i></div>
        <span class="admin-stat-label">Total Bookings</span>
        <div class="admin-stat-value" id="kpiBookings">20</div>
        <div class="admin-stat-trend up"><i class="fas fa-arrow-up"></i> +24% <span class="trend-text">vs last mo</span></div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card red">
        <div class="admin-stat-icon"><i class="fas fa-clock"></i></div>
        <span class="admin-stat-label">Pending Review</span>
        <div class="admin-stat-value" id="kpiPending">5</div>
        <div class="admin-stat-trend down"><i class="fas fa-exclamation"></i> Needs Action</div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
      <div class="admin-stat-card cyan">
        <div class="admin-stat-icon"><i class="fas fa-indian-rupee-sign"></i></div>
        <span class="admin-stat-label">Total Revenue</span>
        <div class="admin-stat-value" id="kpiRevenue">₹4.2L</div>
        <div class="admin-stat-trend up"><i class="fas fa-arrow-up"></i> +18% <span class="trend-text">target met</span></div>
      </div>
    </div>
  </div>

  <!-- CHARTS ROW -->
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="chart-card h-100">
        <div class="chart-header">
          <div>
            <div class="chart-title">Monthly Revenue Dynamics</div>
            <div class="chart-subtitle">Revenue in INR for current fiscal year</div>
          </div>
        </div>
        <div style="height:280px;">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="chart-card h-100">
        <div class="chart-header">
          <div>
            <div class="chart-title">Booking Status Distribution</div>
            <div class="chart-subtitle">Breakdown of active vs completed trips</div>
          </div>
        </div>
        <div style="height:280px;" class="d-flex align-items-center justify-content-center">
          <canvas id="statusChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- RECENT BOOKINGS TABLE -->
  <div class="admin-table-card mb-4">
    <div class="admin-table-header">
      <h6><i class="fas fa-list text-primary me-2"></i>Recent Rental Bookings</h6>
      <a href="{{url('/admin/bookings')}}" class="btn btn-outline-brand btn-sm-brand">View All Bookings</a>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand">
        <thead>
          <tr><th>Booking ID</th><th>Customer</th><th>Car</th><th>Pickup Date</th><th>Return Date</th><th>Total</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody id="adminRecentBookingsTable">
          <!-- Rendered by JS -->
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{url('admin/js/admin-dashboard.js')}}"></script>
@endsection
