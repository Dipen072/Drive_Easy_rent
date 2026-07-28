@extends('website.layout.structure')

@section('content')
<section class="section-py">
  <div class="container">
    <div class="row align-items-center g-5 mb-5">
      <div class="col-lg-6">
        <span class="section-label">Who We Are</span>
        <h2 class="section-title">Reinventing Car Rental <span class="text-primary-brand">For Modern India</span></h2>
        <p class="lead mt-3">DriveEase was founded with a mission to make self-drive car rentals effortless, transparent, and accessible to everyone.</p>
        <p class="text-muted leading-relaxed">Whether you need an economy hatchback for city errands, a luxury sedan for business travel, or a 7-seater SUV for a family road trip, DriveEase provides top-quality, sanitized cars with zero hidden costs.</p>
      </div>
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1552960562-daf630e9278b?w=900&q=80" class="img-fluid rounded-card shadow-lg" alt="About DriveEase">
      </div>
    </div>

    <!-- Stats -->
    <div class="bg-surface-2 p-5 rounded-card border mb-5">
      <div class="row text-center g-4">
        <div class="col-6 col-md-3"><h2 class="fw-800 text-primary display-5 font-heading">50,000+</h2><span class="text-muted font-sm fw-600">HAPPY RENTERS</span></div>
        <div class="col-6 col-md-3"><h2 class="fw-800 text-primary display-5 font-heading">20+</h2><span class="text-muted font-sm fw-600">CAR MODELS</span></div>
        <div class="col-6 col-md-3"><h2 class="fw-800 text-primary display-5 font-heading">10</h2><span class="text-muted font-sm fw-600">CITIES IN INDIA</span></div>
        <div class="col-6 col-md-3"><h2 class="fw-800 text-primary display-5 font-heading">4.8★</h2><span class="text-muted font-sm fw-600">AVERAGE RATING</span></div>
      </div>
    </div>

    <!-- Leadership Team -->
    <div class="text-center mb-4">
      <span class="section-label">Leadership</span>
      <h3 class="fw-800 font-heading">Meet Our Leadership Team</h3>
    </div>
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-4" id="teamGrid">
      <!-- Rendered by JS -->
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script>
  $(document).ready(function() {
    if (typeof TEAM_DATA !== 'undefined' && $('#teamGrid').length) {
      $('#teamGrid').html(TEAM_DATA.map(t => `
        <div class="col">
          <div class="card-brand p-3 text-center h-100">
            <img src="${t.image}" class="rounded-circle mx-auto mb-3" style="width:100px;height:100px;object-fit:cover;">
            <h6 class="fw-700 font-heading mb-1">${t.name}</h6>
            <span class="badge bg-primary-lighter text-primary font-xs mb-2">${t.role}</span>
            <p class="font-xs text-muted mb-0">${t.bio}</p>
          </div>
        </div>
      `).join(''));
    }
  });
</script>
@endsection
