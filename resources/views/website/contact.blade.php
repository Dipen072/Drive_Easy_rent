@extends('website.layout.structure')

@section('content')
<section class="section-py">
  <div class="container">
    <div class="row g-5">
      
      <!-- CONTACT FORM -->
      <div class="col-lg-7">
        <div class="bg-surface p-4 p-sm-5 rounded-card border shadow-xs">
          <span class="section-label">Get In Touch</span>
          <h3 class="fw-800 font-heading mb-3">Send Us a Message</h3>
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
              <i class="fas fa-exclamation-circle me-2"></i><strong>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2 ps-3 font-sm">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{url('/ins_contact')}}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Rahul Sharma" name="name">
                @error('name')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="e.g. rahul@example.com" name="email">
                @error('email')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number *</label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="+91 98765 43210" name="phone">
                @error('phone')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Subject *</label>
                <select class="form-select @error('subject') is-invalid @enderror" required name="subject">
                  <option value="">Select Query Type</option>
                  <option value="Booking Inquiry" {{ old('subject') == 'Booking Inquiry' ? 'selected' : '' }}>Booking Inquiry</option>
                  <option value="Cancellation & Refund" {{ old('subject') == 'Cancellation & Refund' ? 'selected' : '' }}>Cancellation & Refund</option>
                  <option value="Corporate Rental" {{ old('subject') == 'Corporate Rental' ? 'selected' : '' }}>Corporate Rental</option>
                  <option value="General Question" {{ old('subject') == 'General Question' ? 'selected' : '' }}>General Question</option>
                </select>
                @error('subject')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label class="form-label">Message *</label>
                <textarea class="form-control @error('message') is-invalid @enderror" rows="4" required placeholder="How can we help you?" name="message">{{ old('message') }}</textarea>
                @error('message')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <button type="submit" class="btn btn-primary-brand btn-lg-brand mt-4">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
          </form>
        </div>
      </div>

      <!-- CONTACT INFO & MAP PLACEHOLDER -->
      <div class="col-lg-5">
        <div class="bg-surface p-4 rounded-card border shadow-xs mb-4">
          <h5 class="fw-700 font-heading border-bottom pb-3 mb-3">Contact Information</h5>
          
          <div class="d-flex align-items-center gap-3 mb-3">
            <i class="fas fa-headset fs-3 text-primary"></i>
            <div>
              <div class="fw-700 font-sm">Customer Helpline (24/7)</div>
              <a href="tel:+9118001234567" class="text-primary fw-600">1800-123-4567</a>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <i class="fas fa-envelope fs-3 text-success"></i>
            <div>
              <div class="fw-700 font-sm">Email Support</div>
              <a href="mailto:support@driveease.in" class="text-secondary font-sm">support@driveease.in</a>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-3">
            <i class="fas fa-building fs-3 text-info"></i>
            <div>
              <div class="fw-700 font-sm">Head Office</div>
              <div class="font-sm text-muted">DriveEase HQ, 12th Floor, Bandra Kurla Complex, Mumbai - 400051</div>
            </div>
          </div>
        </div>

        <!-- Styled Map Placeholder -->
        <div class="bg-surface-2 border rounded-card p-4 text-center">
          <i class="fas fa-map-location-dot fs-1 text-primary mb-2"></i>
          <h6 class="fw-700 font-heading">Interactive Map Branch Locator</h6>
          <p class="font-xs text-muted mb-0">Visit any of our 10+ pickup points across major airports and metro hubs in India.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
@endsection
