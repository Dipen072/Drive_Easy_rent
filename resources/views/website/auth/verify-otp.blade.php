<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP Code — DriveEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <style>
    body { background: var(--surface-2); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .auth-card { max-width: 440px; width: 100%; }
    .otp-input {
      font-size: 2rem;
      letter-spacing: 14px;
      text-align: center;
      font-family: monospace;
      font-weight: 800;
      color: var(--primary);
    }
  </style>
</head>
<body>
<div class="auth-card p-4 p-sm-5 bg-surface rounded-card border shadow-lg">
  <div class="text-center mb-4">
    <a href="{{url('/index')}}" class="navbar-brand justify-content-center mb-2">
      <div class="brand-logo"><i class="fas fa-car-side"></i></div>
      <span class="brand-name">Drive<span>Ease</span></span>
    </a>
    <h4 class="fw-800 font-heading mb-1">Verify OTP Code</h4>
    <p class="text-muted font-sm">
      We sent a 6-digit verification code to<br>
      <strong class="text-dark">{{ session('reset_email') }}</strong>
    </p>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger font-xs mb-3">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="verifyOtpForm" action="{{ url('/verify-reset-otp') }}" method="POST">
    @csrf
    <div class="mb-4 text-center">
      <label class="form-label fw-600 font-sm mb-2">Enter 6-Digit OTP Code *</label>
      <input type="text" name="otp" class="form-control otp-input" maxlength="6" pattern="[0-9]{6}" placeholder="------" required autofocus autocomplete="off">
      <span class="form-text font-xs text-muted mt-2 d-block"><i class="fas fa-clock text-warning me-1"></i> OTP valid for 10 minutes</span>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">
      Verify OTP <i class="fas fa-check-circle ms-2"></i>
    </button>
  </form>

  <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top font-xs">
    <a href="{{url('/forgot-password')}}" class="text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Change Email</a>
    <form action="{{ url('/send-reset-otp') }}" method="POST" class="d-inline">
      @csrf
      <input type="hidden" name="email" value="{{ session('reset_email') }}">
      <button type="submit" class="btn btn-link text-primary p-0 font-xs fw-600 text-decoration-none"><i class="fas fa-sync-alt me-1"></i> Resend OTP</button>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
</body>
</html>
