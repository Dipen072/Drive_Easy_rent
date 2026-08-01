<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — DriveEase</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <style>body{background:var(--surface-2);min-height:100vh;display:flex;align-items:center;justify-content:center;}.auth-card{max-width:420px;width:100%;}</style>
</head>
<body>
<div class="auth-card p-4 p-sm-5 bg-surface rounded-card border shadow-lg">
  <div class="text-center mb-4">
    <a href="{{url('/index')}}" class="navbar-brand justify-content-center mb-2">
      <div class="brand-logo"><i class="fas fa-car-side"></i></div>
      <span class="brand-name">Drive<span>Ease</span></span>
    </a>
    <h4 class="fw-800 font-heading">Forgot Password</h4>
    <p class="text-muted font-sm">Enter your registered email address to receive a 6-digit OTP code.</p>
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

  <form id="forgotForm" action="{{ url('/send-reset-otp') }}" method="POST">
    @csrf
    <div class="mb-4">
      <label class="form-label fw-600 font-sm">Registered Email Address *</label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
        <input type="email" name="email" class="form-control" id="forgotEmail" placeholder="e.g. arjun.sharma@email.com" value="{{ old('email') }}" required autofocus>
      </div>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">
      Send OTP Code <i class="fas fa-paper-plane ms-2"></i>
    </button>
  </form>
  <div class="text-center mt-4 pt-3 border-top">
    <a href="{{url('/login')}}" class="font-sm text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
</body>
</html>
