@extends('website.layout.structure')

@section('content')
<section class="section-py bg-surface-2">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Save Big On Every Trip</span>
      <h2 class="section-title">Promotional <span class="text-primary-brand">Offers & Coupons</span></h2>
      <p class="section-subtitle mx-auto mt-2">Copy coupon codes below and apply them during checkout for instant discounts.</p>
    </div>

    <div class="row g-4" id="allOffersGrid">
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
      const coupons = Storage.getCoupons();
      const html = coupons.map(c => `
        <div class="col-md-6 col-lg-4">
          <div class="offer-card" style="background:${c.bgColor || 'var(--primary-gradient)'}">
            <span class="badge bg-white text-dark fw-700 px-3 py-2 rounded-pill mb-3">${c.description}</span>
            <div class="offer-discount">${c.type === 'percentage' ? c.value + '% OFF' : '₹' + c.value + ' OFF'}</div>
            <p class="mb-3 opacity-75">Min booking: ₹${c.minAmount.toLocaleString()} · Valid till ${c.expiryDate}</p>
            <div class="d-flex align-items-center justify-content-between">
              <div class="offer-code" onclick="if(typeof copyToClipboard !== 'undefined') copyToClipboard('${c.code}', 'Coupon Copied!')">
                <i class="far fa-copy me-1"></i> ${c.code}
              </div>
              <a href="{{url('/cars')}}" class="btn btn-sm btn-light fw-700 rounded-pill px-3">Redeem</a>
            </div>
          </div>
        </div>
      `).join('');
      $('#allOffersGrid').html(html);
    }
  });
</script>
@endsection
