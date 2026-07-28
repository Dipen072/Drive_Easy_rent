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
    <h4 class="fw-800 font-heading">Reset Password</h4>
    <p class="text-muted font-sm">Enter your registered email to receive a password reset link.</p>
  </div>
  <form id="forgotForm">
    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" class="form-control" id="forgotEmail" placeholder="arjun.sharma@email.com" required>
    </div>
    <button type="submit" class="btn btn-primary-brand w-100 btn-lg-brand fw-700">Send Reset Link</button>
  </form>
  <div class="text-center mt-4 pt-3 border-top">
    <a href="{{url('/login')}}" class="font-sm text-muted text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script>
  $('#forgotForm').on('submit', function(e){
    e.preventDefault();
    Toast.success('Reset Link Sent', 'Password reset instructions have been sent to your email.');
    setTimeout(() => window.location.href = "{{url('/reset-password')}}", 1500);
  });
</script>
</body>
</html>
