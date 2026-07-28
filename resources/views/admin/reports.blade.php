@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Analytics & Exportable Reports</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Reports</span></div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-brand btn-sm-brand" id="exportCsvBtn"><i class="fas fa-file-csv me-1"></i> Export CSV</button>
      <button class="btn btn-primary-brand btn-sm-brand" onclick="window.print()"><i class="fas fa-print me-1"></i> Print Report</button>
    </div>
  </div>

  <!-- CHARTS GRID -->
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="chart-card">
        <div class="chart-title mb-3">Bookings Volume Trend</div>
        <div style="height:260px;"><canvas id="bookingsTrendChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="chart-card">
        <div class="chart-title mb-3">Fleet Utilization by Category</div>
        <div style="height:260px;"><canvas id="catUtilChart"></canvas></div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{url('admin/js/admin-reports.js')}}"></script>
@endsection
