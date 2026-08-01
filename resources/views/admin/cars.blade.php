@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4>Manage Fleet Vehicles</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <span class="current">Cars</span></div>
    </div>
    <a href="{{url('/admin/add-car')}}" class="btn btn-primary-brand btn-sm-brand"><i class="fas fa-plus me-1"></i> Add New Car</a>
  </div>

  <div class="admin-table-card">
    <div class="admin-table-header flex-column flex-md-row align-items-md-center justify-content-between gap-3">
      <h5 class="mb-0 fw-700">Total Fleet Vehicles: {{ count($cars) }}</h5>
      <a href="{{url('/admin/add-car')}}" class="btn btn-primary-brand btn-sm-brand"><i class="fas fa-plus me-1"></i> Add New Vehicle</a>
    </div>
    <div class="admin-table-wrapper">
      <table class="table-brand align-middle">
        <thead>
          <tr>
            <th>Image</th>
            <th>ID</th>
            <th>Brand & Model</th>
            <th>Category</th>
            <th>Rate / Day</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($cars as $car)
          <tr>
            <td>
              <img src="{{ str_starts_with($car->image, 'http') ? $car->image : url($car->image) }}" class="rounded" style="width:65px; height:45px; object-fit:cover;">
            </td>
            <td><strong>#CAR-{{ $car->id }}</strong></td>
            <td>
              <div class="fw-700 text-dark">{{ $car->brand_name }} {{ $car->model_name }}</div>
              <span class="font-xs text-muted">Year: {{ $car->year ?? '2024' }}</span>
            </td>
            <td>
              <span class="badge bg-primary-lighter text-primary font-xs">{{ $car->category->name ?? 'N/A' }}</span>
            </td>
            <td>
              <strong class="text-primary fs-6">₹{{ number_format($car->rate_per_day) }}</strong><span class="font-xs text-muted">/day</span>
            </td>
            <td>
              <div><i class="fas fa-map-marker-alt text-danger me-1 font-xs"></i> {{ $car->location ?? 'Mumbai' }}</div>
            </td>
            <td>
              <span class="badge bg-success-light text-success fw-700"><i class="fas fa-check-circle me-1"></i> {{ $car->status }}</span>
            </td>
            <td>
              <a href="{{ url('/admin/edit-car/' . $car->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Vehicle">
                <i class="fas fa-edit"></i>
              </a>
              <a href="{{ url('/admin/del-car/' . $car->id) }}" onclick="return confirm('Are you sure you want to delete this vehicle?')" class="btn btn-sm btn-outline-danger" title="Delete Vehicle">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">No cars added to database yet. Click "Add New Vehicle" above to add your first car.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('sweetalert::alert')
@endsection
