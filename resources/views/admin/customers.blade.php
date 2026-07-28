@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Registered Customers Roster</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Customers</span></div>
    </div>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-header">
      <div class="admin-search-bar">
        <h5 class="mb-0 fw-700">Total Customers: {{ count($customers) }}</h5>
      </div>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand align-middle">
        <thead>
          <tr>
            <th>Avatar</th>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Contact Details</th>
            <th>Address</th>
            <th>Identity Proof</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $customer)
          <tr>
            <td>
              <img src="{{ $customer->profile_picture ? url($customer->profile_picture) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&q=80' }}" class="rounded-circle" style="width:45px; height:45px; object-fit:cover;">
            </td>
            <td><strong>#CUST-{{ $customer->id }}</strong></td>
            <td>
              <div class="fw-700 text-dark">{{ $customer->first_name }} {{ $customer->last_name }}</div>
              <span class="font-xs text-muted">Reg: {{ $customer->created_at->format('d M Y') }}</span>
            </td>
            <td>
              <div><i class="fas fa-envelope me-1 text-primary"></i> {{ $customer->email }}</div>
              <div class="font-xs text-muted"><i class="fas fa-phone me-1"></i> {{ $customer->phone }}</div>
            </td>
            <td>
              <div>{{ $customer->city ?? 'N/A' }}, {{ $customer->state ?? '' }}</div>
              <span class="font-xs text-muted">{{ $customer->country ?? 'India' }}</span>
            </td>
            <td>
              @if($customer->has_dl)
                <span class="badge bg-primary mb-1">Driving License</span>
                <div class="font-xs fw-600">{{ $customer->dl_number ?? 'N/A' }}</div>
                @if($customer->dl_file)
                  <a href="{{ url($customer->dl_file) }}" target="_blank" class="font-xs text-primary"><i class="fas fa-file-download me-1"></i> View DL File</a>
                @endif
              @else
                <span class="badge bg-info mb-1">{{ $customer->alt_id_type ?? 'Alt ID' }}</span>
                <div class="font-xs fw-600">{{ $customer->alt_id_number ?? 'N/A' }}</div>
                @if($customer->alt_id_file)
                  <a href="{{ url($customer->alt_id_file) }}" target="_blank" class="font-xs text-primary"><i class="fas fa-file-download me-1"></i> View ID File</a>
                @endif
              @endif
            </td>
            <td>
              @if($customer->status == 'Active' || $customer->status == 'Unblock')
                <span class="badge bg-success-light text-success fw-700"><i class="fas fa-check-circle me-1"></i> Active</span>
              @else
                <span class="badge bg-danger-light text-danger fw-700"><i class="fas fa-ban me-1"></i> Blocked</span>
              @endif
            </td>
            <td>
              <div class="d-flex gap-2">
                <a href="{{ url('/admin/status_customer/' . $customer->id) }}" class="btn btn-sm btn-outline-warning" title="Toggle Status (Active/Block)">
                  <i class="fas {{ ($customer->status == 'Active' || $customer->status == 'Unblock') ? 'fa-ban' : 'fa-check' }}"></i>
                </a>
                <a href="{{ url('/admin/del_customer/' . $customer->id) }}" onclick="return confirm('Are you sure you want to delete this customer?')" class="btn btn-sm btn-outline-danger" title="Delete Customer">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">No customers registered yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('sweetalert::alert')
@endsection
