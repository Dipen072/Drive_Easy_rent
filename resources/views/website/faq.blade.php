@extends('website.layout.structure')

@section('content')
<section class="section-py bg-surface-2">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-label">Help Center</span>
      <h2 class="section-title">Searchable <span class="text-primary-brand">FAQ Knowledgebase</span></h2>
      <p class="section-subtitle mx-auto mt-2">Find answers to common questions about car bookings, payment methods, insurance and policies.</p>
    </div>

    <!-- Search Input & Category Pills -->
    <div class="max-w-700 mx-auto mb-4">
      <input type="text" class="form-control form-control-lg mb-3" id="faqSearchInput" placeholder="Search any question (e.g. deposit, license, refund)...">
      <div class="d-flex justify-content-center gap-2 flex-wrap" id="faqCategoryPills">
        <button class="btn btn-sm btn-outline-brand active" data-cat="all">All Questions</button>
        <button class="btn btn-sm btn-outline-brand" data-cat="Booking">Booking</button>
        <button class="btn btn-sm btn-outline-brand" data-cat="Payment">Payment</button>
        <button class="btn btn-sm btn-outline-brand" data-cat="Cancellation">Cancellation</button>
        <button class="btn btn-sm btn-outline-brand" data-cat="Rental Policy">Rental Policy</button>
        <button class="btn btn-sm btn-outline-brand" data-cat="Insurance">Insurance</button>
      </div>
    </div>

    <div class="max-w-800 mx-auto">
      <div class="accordion accordion-flush" id="fullFaqAccordion">
        <!-- Rendered by JS -->
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script>
  $(document).ready(function() {
    if (typeof FAQ_DATA !== 'undefined') {
      renderFaqs('all');

      $('#faqCategoryPills button').on('click', function() {
        $('#faqCategoryPills button').removeClass('active');
        $(this).addClass('active');
        renderFaqs($(this).data('cat'));
      });

      $('#faqSearchInput').on('input', function() {
        const q = $(this).val().toLowerCase();
        let filtered = FAQ_DATA.filter(f => f.q.toLowerCase().includes(q) || f.a.toLowerCase().includes(q));
        renderFaqItems(filtered);
      });
    }
  });

  function renderFaqs(cat) {
    let faqs = (typeof FAQ_DATA !== 'undefined') ? FAQ_DATA : [];
    if (cat !== 'all') faqs = faqs.filter(f => f.category === cat);
    renderFaqItems(faqs);
  }

  function renderFaqItems(items) {
    if (!items.length) {
      $('#fullFaqAccordion').html('<div class="text-center p-4 text-muted">No questions found.</div>');
      return;
    }
    const html = items.map((f, i) => `
      <div class="accordion-item border mb-3 rounded-card shadow-xs overflow-hidden">
        <h2 class="accordion-header">
          <button class="accordion-button fw-700 fs-6 ${i===0?'':'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#fullFaq${f.id}">
            <i class="fas fa-circle-question text-primary me-2"></i> [${f.category}] ${f.q}
          </button>
        </h2>
        <div id="fullFaq${f.id}" class="accordion-collapse collapse ${i===0?'show':''}" data-bs-parent="#fullFaqAccordion">
          <div class="accordion-body text-secondary leading-relaxed">${f.a}</div>
        </div>
      </div>
    `).join('');
    $('#fullFaqAccordion').html(html);
  }
</script>
@endsection
