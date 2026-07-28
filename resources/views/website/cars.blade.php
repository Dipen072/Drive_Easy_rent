@extends('website.layout.structure')

@section('content')
<!-- TOP SEARCH SUMMARY BAR -->
<div class="search-summary-bar">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div class="search-summary-info" id="searchSummaryInfo">
        <div class="summary-chip"><i class="fas fa-map-marker-alt"></i> <span id="sumLocation">All Locations</span></div>
        <div class="summary-chip"><i class="fas fa-calendar-alt"></i> <span id="sumDates">All Dates</span></div>
      </div>
      <button class="btn btn-outline-brand btn-sm-brand" data-bs-toggle="modal" data-bs-target="#modifySearchModal">
        <i class="fas fa-sliders me-1"></i> Modify Search
      </button>
    </div>
  </div>
</div>

<!-- MAIN SEARCH & FILTER AREA -->
<section class="section-py bg-surface-2">
  <div class="container">
    
    <!-- CATEGORIES FILTER BAR -->
    <div class="d-flex align-items-center gap-2 overflow-auto pb-3 mb-4">
      <a href="{{ url('/cars') }}" class="btn {{ !request('category') ? 'btn-primary-brand' : 'btn-outline-brand' }} btn-sm rounded-pill px-3">
        <i class="fas fa-th me-1"></i> All Categories
      </a>
      @foreach($categories as $cat)
        <a href="{{ url('/cars?category=' . $cat->id) }}" class="btn {{ request('category') == $cat->id ? 'btn-primary-brand' : 'btn-outline-brand' }} btn-sm rounded-pill px-3">
          <i class="fas {{ $cat->icon ?? 'fa-car' }} me-1"></i> {{ $cat->name }} ({{ $cat->cars_count }})
        </a>
      @endforeach
    </div>

    <!-- CARS GRID -->
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
      @forelse($cars as $car)
      <div class="col">
        <div class="card car-card h-100 border shadow-xs rounded-card overflow-hidden bg-surface">
          <div class="position-relative">
            <img src="{{ str_starts_with($car->image, 'http') ? $car->image : url($car->image) }}" class="card-img-top car-card-img" alt="{{ $car->brand_name }} {{ $car->model_name }}" style="height:210px; object-fit:cover;">
            <span class="position-absolute top-0 end-0 m-3 badge bg-primary font-xs">{{ $car->category->name ?? 'Car' }}</span>
          </div>
          <div class="card-body p-4 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="fw-800 font-heading mb-0 text-dark">{{ $car->brand_name }} {{ $car->model_name }}</h5>
                <span class="font-xs text-muted fw-600">{{ $car->year ?? '2024' }}</span>
              </div>
              
              <div class="d-flex align-items-center gap-3 font-xs text-muted mb-3">
                <span><i class="fas fa-gas-pump me-1 text-primary"></i>{{ $car->fuel_type ?? 'Petrol' }}</span>
                <span><i class="fas fa-cog me-1 text-primary"></i>{{ $car->transmission ?? 'Auto' }}</span>
                <span><i class="fas fa-user-friends me-1 text-primary"></i>{{ $car->seats ?? 5 }} Seats</span>
              </div>

              <div class="font-xs text-secondary mb-3">
                <i class="fas fa-map-marker-alt text-danger me-1"></i> Location: {{ $car->location ?? 'Mumbai' }}
              </div>
            </div>

            <div class="pt-3 border-top d-flex align-items-center justify-content-between">
              <div>
                <span class="font-xs text-muted">Daily Rate</span>
                <div class="fs-4 fw-800 text-primary">₹{{ number_format($car->rate_per_day) }}<span class="font-xs text-muted fw-normal">/day</span></div>
              </div>
              <a href="{{ url('/booking?car=' . $car->id) }}" class="btn btn-primary-brand btn-sm-brand px-4">Book Now</a>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="alert alert-info text-center py-5 shadow-xs border-0">
          <i class="fas fa-car-side fs-1 text-primary mb-3"></i>
          <h4>No Cars Available in Selected Category</h4>
          <p class="text-muted mb-3">Check back soon or choose another category to explore available rental fleet.</p>
          <a href="{{ url('/cars') }}" class="btn btn-outline-brand">View All Cars</a>
        </div>
      </div>
      @endforelse
    </div>

  </div>
</section>

<!-- MODIFY SEARCH MODAL -->
<div class="modal fade" id="modifySearchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-700"><i class="fas fa-sliders me-2 text-primary"></i>Modify Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Pickup Location</label>
          <select id="modPickup" class="form-select"></select>
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <label class="form-label">Pickup Date</label>
            <input type="text" id="modPDate" class="form-control" readonly>
          </div>
          <div class="col-6">
            <label class="form-label">Return Date</label>
            <input type="text" id="modRDate" class="form-control" readonly>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary-brand" id="applyModifySearchBtn">Update Results</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script src="{{url('website/js/cars.js')}}"></script>
@endsection
