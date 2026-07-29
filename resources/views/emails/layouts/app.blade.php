<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DriveEase Car Rental')</title>
  <style>
    body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
    table { border-collapse: collapse; }
    .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .header-bar { background-color: #0d6efd; padding: 24px 32px; text-align: center; }
    .header-logo { color: #ffffff; font-size: 26px; font-weight: 800; text-decoration: none; letter-spacing: -0.5px; }
    .header-logo span { color: #ffc107; }
    .content-body { padding: 32px; color: #2b2d42; line-height: 1.6; }
    .h1-title { font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 16px; }
    .badge-status { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; text-transform: uppercase; }
    .badge-success { background-color: #d1fae5; color: #065f46; }
    .badge-primary { background-color: #dbeafe; color: #1e40af; }
    .badge-warning { background-color: #fef3c7; color: #92400e; }
    .badge-danger { background-color: #fee2e2; color: #991b1b; }
    .info-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 20px; margin: 20px 0; }
    .table-details { width: 100%; margin: 16px 0; font-size: 14px; }
    .table-details td { padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
    .table-details td.label { color: #64748b; font-weight: 600; width: 40%; }
    .table-details td.value { color: #0f172a; font-weight: 700; width: 60%; }
    .btn-action { display: inline-block; padding: 12px 28px; background-color: #0d6efd; color: #ffffff !important; font-weight: 700; text-decoration: none; border-radius: 6px; margin: 16px 4px 16px 0; font-size: 14px; }
    .btn-secondary { background-color: #475569; }
    .footer-bar { background-color: #0f172a; padding: 24px 32px; color: #94a3b8; font-size: 12px; text-align: center; }
    .footer-links a { color: #cbd5e1; text-decoration: none; margin: 0 8px; }
  </style>
</head>
<body>
  <div style="padding: 20px 0; background-color: #f4f6f9;">
    <div class="email-container">
      <!-- HEADER -->
      <div class="header-bar">
        <a href="{{ url('/') }}" class="header-logo">🚗 Drive<span>Ease</span></a>
      </div>

      <!-- MAIN CONTENT -->
      <div class="content-body">
        @yield('content')
      </div>

      <!-- FOOTER -->
      <div class="footer-bar">
        <p style="margin:0 0 10px; color:#ffffff; font-weight:700; font-size:14px;">DriveEase Car Rental Services</p>
        <p style="margin:0 0 12px;">Customer Support: <a href="mailto:support@driveease.in" style="color:#60a5fa;">support@driveease.in</a> | Phone: +91 1800-123-4567</p>
        <div class="footer-links" style="margin-bottom:12px;">
          <a href="{{ url('/cars') }}">Browse Cars</a> •
          <a href="{{ url('/my-bookings') }}">My Bookings</a> •
          <a href="{{ url('/contact') }}">Help Center</a>
        </div>
        <p style="margin:0; color:#64748b;">&copy; {{ date('Y') }} DriveEase Technologies. All rights reserved.</p>
      </div>
    </div>
  </div>
</body>
</html>
