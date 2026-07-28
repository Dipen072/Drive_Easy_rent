@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  
  <div class="admin-page-header">
    <div>
      <h4>Administrator Profile</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Profile</span></div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-4">
      <div class="bg-surface p-4 rounded-card border shadow-xs text-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 fw-800 fs-2" style="width:100px;height:100px;">
          {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
        </div>
        <h5 class="fw-800 font-heading mb-1" id="profileAdminName">{{ $admin->name ?? 'Administrator' }}</h5>
        <span class="badge bg-primary-lighter text-primary font-xs mb-3">System Administrator</span>
        <div class="text-muted font-xs">Account Created: {{ $admin->created_at ? $admin->created_at->format('d M Y') : 'N/A' }}</div>
      </div>
    </div>
    
    <div class="col-lg-8">
      <div class="bg-surface p-4 rounded-card border shadow-xs mb-4">
        <h6 class="fw-700 font-heading border-bottom pb-3 mb-3">Account Details</h6>
        <form id="adminProfileForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label font-sm">Admin Name</label>
              <input type="text" class="form-control" id="admName" value="{{ $admin->name ?? '' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label font-sm">Email Address</label>
              <input type="email" class="form-control" id="admEmail" value="{{ $admin->email ?? '' }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label font-sm">System Role</label>
              <input type="text" class="form-control" value="Super Administrator" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label font-sm">Status</label>
              <input type="text" class="form-control text-success fw-700" value="Active" readonly>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>

</div>
@endsection
