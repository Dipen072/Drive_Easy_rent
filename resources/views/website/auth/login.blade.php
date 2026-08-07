<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Login — DriveEase</title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('website/css/main.css')}}">
  <style>
    body { background: var(--surface-2); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .auth-card { max-width: 440px; width: 100%; }
  </style>
</head>
<body>

<div class="auth-card p-4 p-sm-5 bg-surface rounded-card border shadow-lg my-4">
  <div class="text-center mb-4">
    <a href="{{url('/index')}}" class="navbar-brand justify-content-center mb-2">
      <div class="brand-logo"><i class="fas fa-car-side"></i></div>
      <span class="brand-name">Drive<span>Ease</span></span>
    </a>
    <h4 class="fw-800 font-heading">Welcome Back</h4>
    <p class="text-muted font-sm">Log in to manage your car rental bookings</p>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="loginForm" action="{{ url('/login_auth') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" name="email" class="form-control" id="loginEmail" placeholder="e.g. arjun.sharma@email.com" required value="{{ old('email') }}">
    </div>
    <div class="mb-3">
      <div class="d-flex justify-content-between">
        <label class="form-label">Password</label>
        <a href="{{url('/forgot-password')}}" class="font-xs text-primary text-decoration-none">Forgot password?</a>
      </div>
      <input type="password" name="password" class="form-control" id="loginPassword" placeholder="••••••••" required>
    </div>
    <div class="form-check mb-4">
      <input class="form-check-input" type="checkbox" id="rememberMe" checked>
      <label class="form-check-label font-sm text-muted" for="rememberMe">Remember me for 30 days</label>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">Sign In</button>
  </form>

  <div class="text-center mt-4 pt-3 border-top">
    <p class="font-sm text-muted mb-0">Don't have an account? <a href="{{url('/register')}}" class="fw-700 text-primary">Register here</a></p>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{asset('website/js/data.js')}}"></script>
<script src="{{asset('website/js/storage.js')}}"></script>
<script src="{{asset('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
</body>
</html>
