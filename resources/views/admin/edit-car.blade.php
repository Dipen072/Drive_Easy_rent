@extends('admin.layout.structure')

@section('content')
<div class="admin-page-content">
  <div class="admin-page-header">
    <div>
      <h4 id="formHeading">Edit Vehicle — {{ $car->brand_name }} {{ $car->model_name }}</h4>
      <div class="breadcrumb-brand"><span>Admin</span> <span class="sep">/</span> <a href="{{url('/admin/cars')}}">Cars</a> <span class="sep">/</span> <span class="current">Edit #CAR-{{ $car->id }}</span></div>
    </div>
  </div>

  <div class="bg-surface p-4 rounded-card border shadow-xs">
    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="editCarForm" action="{{ url('/admin/update-car/' . $car->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Brand Name *</label>
          <input type="text" name="brand_name" class="form-control" id="carBrand" required placeholder="e.g. BMW, Toyota, Mercedes-Benz" value="{{ old('brand_name', $car->brand_name) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Model Name *</label>
          <input type="text" name="model_name" class="form-control" id="carModel" required placeholder="e.g. 3 Series, Camry, C-Class" value="{{ old('model_name', $car->model_name) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Model Year</label>
          <input type="number" name="year" class="form-control" id="carYear" value="{{ old('year', $car->year ?? '2024') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Category *</label>
          <select name="category_id" id="carCategory" class="form-select" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $car->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Daily Tariff Rate (₹) *</label>
          <input type="number" name="rate_per_day" class="form-control" id="carPrice" required placeholder="e.g. 8500" value="{{ old('rate_per_day', $car->rate_per_day) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" id="carLocation" value="{{ old('location', $car->location ?? 'Mumbai') }}" placeholder="e.g. Mumbai, Delhi, Bangalore">
        </div>
        <div class="col-md-3">
          <label class="form-label">Transmission</label>
          <select name="transmission" id="carTransmission" class="form-select">
            <option value="Automatic" {{ old('transmission', $car->transmission) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
            <option value="Manual" {{ old('transmission', $car->transmission) == 'Manual' ? 'selected' : '' }}>Manual</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Fuel Type</label>
          <select name="fuel_type" id="carFuel" class="form-select">
            <option value="Petrol" {{ old('fuel_type', $car->fuel_type) == 'Petrol' ? 'selected' : '' }}>Petrol</option>
            <option value="Diesel" {{ old('fuel_type', $car->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
            <option value="Electric" {{ old('fuel_type', $car->fuel_type) == 'Electric' ? 'selected' : '' }}>Electric</option>
            <option value="Hybrid" {{ old('fuel_type', $car->fuel_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Seating Capacity</label>
          <input type="number" name="seats" class="form-control" id="carSeats" value="{{ old('seats', $car->seats ?? 5) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" id="carStatus" class="form-select">
            <option value="Available" {{ old('status', $car->status) == 'Available' ? 'selected' : '' }}>Available</option>
            <option value="Rented" {{ old('status', $car->status) == 'Rented' ? 'selected' : '' }}>Rented</option>
            <option value="Maintenance" {{ old('status', $car->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Car Photo (Leave blank to keep current photo)</label>
          <input type="file" name="image" class="form-control" id="carImageFile" accept="image/*">
          <div id="imagePreviewContainer" class="mt-3">
            <label class="font-xs text-muted d-block mb-1">Current / Preview Image:</label>
            <img id="imagePreview" src="{{ str_starts_with($car->image, 'http') ? $car->image : url($car->image) }}" alt="Car Image Preview" style="max-height: 150px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: cover;">
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
        <a href="{{url('/admin/cars')}}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary-brand fw-700"><i class="fas fa-save me-1"></i> Update Vehicle</button>
      </div>
    </form>
  </div>
</div>
@include('sweetalert::alert')
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('#carImageFile').on('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          $('#imagePreview').attr('src', evt.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  });
</script>
@endsection
