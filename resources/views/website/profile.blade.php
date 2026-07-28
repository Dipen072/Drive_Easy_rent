@extends('website.layout.structure')

@section('content')
<style>
  .dashboard-layout { display: flex; min-height: calc(100vh - 120px); }
  .dashboard-sidebar { width: 260px; background: var(--surface); border-right: 1px solid var(--border); padding: 1.5rem 1rem; flex-shrink: 0; }
  .dashboard-content { flex: 1; padding: 2rem; background: var(--surface-2); }
  .dash-nav-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; color: var(--text-secondary); border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; margin-bottom: 0.25rem; transition: var(--transition); }
  .dash-nav-item:hover, .dash-nav-item.active { background: var(--primary-lighter); color: var(--primary); }
  .profile-avatar-xl { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-light); box-shadow: var(--shadow-sm); }
  .section-header-profile { font-family: var(--font-heading); font-weight: 700; font-size: 1.1rem; color: var(--primary); border-bottom: 2px solid var(--primary-lighter); padding-bottom: 0.5rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
  @media (max-width: 991.98px) {
    .dashboard-layout { flex-direction: column; }
    .dashboard-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border); }
  }
</style>

<!-- DASHBOARD LAYOUT -->
<div class="dashboard-layout">
  
  <!-- SIDEBAR -->
  <aside class="dashboard-sidebar">
    <div class="text-center pb-3 mb-3 border-bottom">
      <img src="{{ $customer->profile_picture ? url($customer->profile_picture) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&q=80' }}" id="dashSidebarAvatar" class="rounded-circle mb-2" style="width:70px;height:70px;object-fit:cover;">
      <h6 class="fw-800 font-heading mb-0" id="dashSidebarName">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
      <span class="badge bg-success-light text-success font-xs fw-700">Account {{ $customer->status ?? 'Active' }}</span>
    </div>

    <a href="{{url('/index')}}" class="dash-nav-item"><i class="fas fa-th-large"></i> Home & Dashboard</a>
    <a href="{{url('/booking')}}" class="dash-nav-item"><i class="fas fa-calendar-check"></i> My Bookings</a>
    <a href="{{url('/user_profile')}}" class="dash-nav-item active"><i class="fas fa-user-circle"></i> My Profile</a>
    <a href="{{url('/cars')}}" class="dash-nav-item"><i class="fas fa-car-side"></i> Browse Cars</a>
    <a href="{{url('/offers')}}" class="dash-nav-item"><i class="fas fa-tag"></i> Special Offers</a>
    <a href="{{url('/user_logout')}}" class="dash-nav-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="dashboard-content">
    <div class="max-w-900 mx-auto">
      
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
          <h3 class="fw-800 font-heading mb-1">Account & Identity Profile</h3>
          <p class="text-secondary font-sm mb-0">Welcome, {{ $customer->first_name }}! Here are your registered personal information & verification details.</p>
        </div>
      </div>

      <!-- PROFILE CARD -->
      <div class="bg-surface p-4 p-sm-5 rounded-card border shadow-xs mb-4">
        <form id="profileForm">
          
          <!-- 👤 1. BASIC DETAILS -->
          <div class="mb-5">
            <div class="section-header-profile"><i class="fas fa-user"></i> Basic Details</div>
            
            <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom">
              <img src="{{ $customer->profile_picture ? url($customer->profile_picture) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&q=80' }}" id="profileAvatarPreview" class="profile-avatar-xl">
              <div>
                <h5 class="fw-700 mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                <div class="font-xs text-muted">Registered Email: {{ $customer->email }}</div>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label font-sm fw-600">First Name</label>
                <input type="text" class="form-control" readonly value="{{ $customer->first_name }}">
              </div>
              <div class="col-md-6">
                <label class="form-label font-sm fw-600">Last Name</label>
                <input type="text" class="form-control" readonly value="{{ $customer->last_name }}">
              </div>
              <div class="col-md-6">
                <label class="form-label font-sm fw-600">Email Address</label>
                <input type="email" class="form-control" readonly value="{{ $customer->email }}">
              </div>
              <div class="col-md-6">
                <label class="form-label font-sm fw-600">Mobile Number</label>
                <input type="tel" class="form-control" readonly value="{{ $customer->phone }}">
              </div>
              <div class="col-md-6">
                <label class="form-label font-sm fw-600">Date of Birth</label>
                <input type="text" class="form-control" readonly value="{{ $customer->dob ?? 'N/A' }}">
              </div>
            </div>
          </div>

          <!-- 🏠 2. ADDRESS DETAILS -->
          <div class="mb-5">
            <div class="section-header-profile"><i class="fas fa-house"></i> Address Details</div>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label font-sm fw-600">Street Address</label>
                <input type="text" class="form-control" readonly value="{{ $customer->address ?? 'N/A' }}">
              </div>
              <div class="col-md-3 col-6">
                <label class="form-label font-sm fw-600">City</label>
                <input type="text" class="form-control" readonly value="{{ $customer->city ?? 'N/A' }}">
              </div>
              <div class="col-md-3 col-6">
                <label class="form-label font-sm fw-600">State</label>
                <input type="text" class="form-control" readonly value="{{ $customer->state ?? 'N/A' }}">
              </div>
              <div class="col-md-3 col-6">
                <label class="form-label font-sm fw-600">Country</label>
                <input type="text" class="form-control" readonly value="{{ $customer->country ?? 'India' }}">
              </div>
              <div class="col-md-3 col-6">
                <label class="form-label font-sm fw-600">ZIP / Postal Code</label>
                <input type="text" class="form-control" readonly value="{{ $customer->zip_code ?? 'N/A' }}">
              </div>
            </div>
          </div>

          <!-- 📄 3. DRIVING DETAILS & VERIFICATION -->
          <div class="mb-5">
            <div class="section-header-profile"><i class="fas fa-id-card"></i> Verification & Identity Documents</div>

            @if($customer->has_dl)
            <!-- OPTION A: DRIVING LICENSE -->
            <div id="profDlSection" class="p-3 border rounded-3 bg-surface mb-3">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="fw-700 text-primary font-sm"><i class="fas fa-id-badge me-1"></i> Driving License Details</div>
                <span class="badge bg-success-light text-success font-xs fw-700"><i class="fas fa-check-circle me-1"></i> Provided</span>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label font-sm">Driving License Number</label>
                  <input type="text" class="form-control" readonly value="{{ $customer->dl_number ?? 'N/A' }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label font-sm">License Expiry Date</label>
                  <input type="text" class="form-control" readonly value="{{ $customer->dl_expiry ?? 'N/A' }}">
                </div>
                @if($customer->dl_file)
                <div class="col-12">
                  <label class="form-label font-sm">Uploaded Driving License Copy</label>
                  <div class="d-flex align-items-center gap-3 p-2 bg-surface-2 border rounded">
                    <i class="fas fa-file-alt fs-3 text-primary"></i>
                    <div class="flex-fill font-sm">
                      <a href="{{ url($customer->dl_file) }}" target="_blank" class="fw-700 text-primary">View Uploaded License Document</a>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>
            @else
            <!-- OPTION B: ALTERNATE ID -->
            <div id="profAltIdSection" class="p-3 border rounded-3 bg-primary-lighter border-primary mb-3">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="fw-700 text-primary font-sm"><i class="fas fa-passport me-1"></i> Alternate Identity Document</div>
                <span class="badge bg-warning-light text-warning font-xs fw-700"><i class="fas fa-check-circle me-1"></i> Alternate ID</span>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label font-sm">Selected ID Type</label>
                  <input type="text" class="form-control" readonly value="{{ $customer->alt_id_type ?? 'Alternate ID' }}">
                </div>
                <div class="col-md-8">
                  <label class="form-label font-sm">Government ID Number</label>
                  <input type="text" class="form-control" readonly value="{{ $customer->alt_id_number ?? 'N/A' }}">
                </div>
                @if($customer->alt_id_file)
                <div class="col-12">
                  <label class="form-label font-sm">Uploaded ID Proof Document</label>
                  <div class="d-flex align-items-center gap-3 p-2 bg-white border rounded">
                    <i class="fas fa-file-image fs-3 text-primary"></i>
                    <div class="flex-fill font-sm">
                      <a href="{{ url($customer->alt_id_file) }}" target="_blank" class="fw-700 text-primary">View Uploaded Alternate ID Document</a>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </div>
            @endif

          </div>

          <!-- LOGOUT ACTION -->
          <div class="pt-3 border-top d-flex justify-content-end gap-3">
            <a href="{{ url('/user_logout') }}" class="btn btn-danger px-4 fw-700"><i class="fas fa-sign-out-alt me-2"></i> Logout Account</a>
          </div>

        </form>
      </div>

    </div>
  </main>
</div>

@include('sweetalert::alert')
@endsection
