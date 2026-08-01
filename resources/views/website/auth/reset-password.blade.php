<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set New Password — DriveEase</title>
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
    <h4 class="fw-800 font-heading">Set New Password</h4>
    <p class="text-muted font-sm">Create a strong new password for your account.</p>
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

  <form id="resetPassForm" action="{{ url('/update-password') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label fw-600 font-sm">New Password *</label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
        <input type="password" name="password" class="form-control" id="newPass" placeholder="Minimum 6 characters" minlength="6" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-600 font-sm">Confirm New Password *</label>
      <div class="input-group">
        <span class="input-group-text bg-light"><i class="fas fa-check-double text-muted"></i></span>
        <input type="password" name="password_confirmation" class="form-control" id="confirmNewPass" placeholder="Re-enter new password" minlength="6" required>
      </div>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">
      Update Password <i class="fas fa-shield-alt ms-2"></i>
    </button>
  </form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
</body>
</html>
