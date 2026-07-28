@extends('website.layout.structure')

@section('content')
<section class="section-py bg-surface-2">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Pickup & Dropoff Points</span>
      <h2 class="section-title">DriveEase <span class="text-primary-brand">Rental Locations</span></h2>
      <p class="section-subtitle mx-auto mt-2">Available across 10 major cities, airports and railway stations in India.</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-surface p-3 rounded-card border mb-4 shadow-xs">
      <div class="row g-3">
        <div class="col-md-8">
          <input type="text" class="form-control" id="locSearchInput" placeholder="Search by city or branch name...">
        </div>
        <div class="col-md-4">
          <select id="locTypeFilter" class="form-select">
            <option value="all">All Branch Types</option>
            <option value="airport">Airports</option>
            <option value="city">City Center</option>
            <option value="railway">Railway Stations</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Locations Cards Grid -->
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3" id="locationsGrid">
      <!-- Rendered by JS -->
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script>
  $(document).ready(function() {
    if (typeof Storage !== 'undefined') {
      Storage.seed();
    }
    renderLocs();

    $('#locSearchInput, #locTypeFilter').on('input change', renderLocs);
  });

  function renderLocs() {
    const q = $('#locSearchInput').val() ? $('#locSearchInput').val().toLowerCase() : '';
    const type = $('#locTypeFilter').val() || 'all';

    let locs = (typeof Storage !== 'undefined') ? Storage.getLocations() : [];

    if (q) {
      locs = locs.filter(l => l.name.toLowerCase().includes(q) || l.city.toLowerCase().includes(q) || l.address.toLowerCase().includes(q));
    }
    if (type !== 'all') {
      locs = locs.filter(l => l.type === type);
    }

    if (!locs.length) {
      $('#locationsGrid').html('<div class="col-12 text-center p-5"><p class="text-muted">No locations match your search.</p></div>');
      return;
    }

    const html = locs.map(l => `
      <div class="col">
        <div class="bg-surface p-4 rounded-card border shadow-xs h-100 d-flex flex-direction-column justify-content-between">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <span class="badge bg-primary-lighter text-primary font-xs uppercase fw-700">${l.type} Branch</span>
              <small class="text-muted"><i class="fas fa-clock me-1"></i>${l.hours}</small>
            </div>
            <h5 class="fw-700 font-heading mb-2">${l.name}</h5>
            <p class="font-sm text-secondary mb-3"><i class="fas fa-map-marker-alt text-primary me-2"></i>${l.address}</p>
            <p class="font-sm text-muted mb-3"><i class="fas fa-phone me-2 text-success"></i>${l.phone}</p>
          </div>
          <a href="{{url('/cars')}}?city=${encodeURIComponent(l.city)}" class="btn btn-outline-brand btn-sm-brand w-100 mt-2">
            View Cars in ${l.city} <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    `).join('');

    $('#locationsGrid').html(html);
  }
</script>
@endsection
