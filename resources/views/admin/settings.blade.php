@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>System & Company Settings</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Settings</span></div>
    </div>
  </div>

  <div class="bg-surface p-4 rounded-card border shadow-xs max-w-800">
    <form id="settingsForm">
      <h6 class="fw-700 font-heading mb-3 border-bottom pb-2">Company Details</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label font-sm">Platform Name</label>
          <input type="text" class="form-control" id="settCompName" value="DriveEase">
        </div>
        <div class="col-md-6">
          <label class="form-label font-sm">Support Email</label>
          <input type="email" class="form-control" id="settCompEmail" value="info@driveease.in">
        </div>
        <div class="col-md-6">
          <label class="form-label font-sm">Helpline Phone</label>
          <input type="text" class="form-control" id="settCompPhone" value="+91 1800-123-4567">
        </div>
        <div class="col-md-6">
          <label class="form-label font-sm">Currency Symbol</label>
          <input type="text" class="form-control" id="settCurrency" value="₹ (INR)" readonly>
        </div>
      </div>

      <h6 class="fw-700 font-heading mb-3 border-bottom pb-2">Rental Business Rules</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label font-sm">GST Tax Rate (%)</label>
          <input type="number" class="form-control" value="18" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label font-sm">Standard Daily Distance Limit (km)</label>
          <input type="number" class="form-control" value="300">
        </div>
      </div>

      <button type="submit" class="btn btn-primary-brand">Save System Settings</button>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    Storage.seed();
    $('#settingsForm').on('submit', function(e) {
      e.preventDefault();
      Toast.success('Settings Saved', 'System configuration saved to local state.');
    });
  });
</script>
@endsection
