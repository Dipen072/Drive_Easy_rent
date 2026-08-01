<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — DriveEase Portal</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <link rel="stylesheet" href="{{url('admin/css/admin.css')}}">
  <style>
    body.admin-login-body {
      background: #0f172a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .admin-login-card {
      max-width: 420px;
      width: 100%;
      background: #1e293b;
      border: 1px solid rgba(255,255,255,.1);
      border-radius: var(--radius-lg);
      padding: 2.5rem 2rem;
      color: #fff;
      box-shadow: 0 20px 50px rgba(0,0,0,.5);
    }
    .admin-login-card .form-control {
      background: #0f172a;
      border-color: rgba(255,255,255,.15);
      color: #fff;
    }
    .admin-login-card .form-control:focus {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    }
  </style>
</head>
<body class="admin-login-body">

<div class="admin-login-card">
  <div class="text-center mb-4">
    <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-3 p-3 mb-3 text-white fs-3">
      <i class="fas fa-user-shield"></i>
    </div>
    <h3 class="fw-800 font-heading text-white mb-1">Drive<span>Ease</span> SaaS</h3>
    <span class="badge bg-primary-lighter text-primary font-xs uppercase fw-700">Administrator Portal</span>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger font-xs mb-4">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="adminLoginForm" action="{{ url('/admin/login_auth') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label class="form-label text-white-50 font-sm fw-600">Admin Email</label>
      <div class="input-group">
        <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" class="form-control" id="adminEmail" placeholder="e.g. admin@driveease.com" value="{{ old('email', request()->cookie('remember_admin_email')) }}" required>
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label text-white-50 font-sm fw-600">Password</label>
      <div class="input-group">
        <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" class="form-control" id="adminPass" placeholder="••••••••" value="{{ request()->cookie('remember_admin_password') }}" required>
      </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="1" {{ request()->cookie('remember_admin_email') ? 'checked' : '' }}>
        <label class="form-check-label text-white-50 font-xs fw-600 cursor-pointer" for="rememberMe">
          Remember Me (Save Cookie)
        </label>
      </div>
      <span class="badge bg-dark text-white-50 font-xs"><i class="fas fa-cookie-bite text-warning me-1"></i> Cookie Enabled</span>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">
      Sign In to Dashboard <i class="fas fa-arrow-right ms-2"></i>
    </button>
  </form>

  <div class="text-center mt-4 pt-3 border-top border-secondary">
    <a href="{{url('/index')}}" class="font-xs text-white-50 text-decoration-none"><i class="fas fa-globe me-1"></i> Return to Main Website</a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
@include('sweetalert::alert')
</body>
</html>
