<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — DriveEase Enterprise</title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <link rel="stylesheet" href="{{url('admin/css/admin.css')}}">
</head>
<body class="admin-body">

<div class="admin-layout">
  
  <!-- SIDEBAR -->
  <aside class="admin-sidebar" id="adminSidebar">
    <a href="{{url('/admin/index')}}" class="sidebar-logo">
      <div class="logo-icon"><i class="fas fa-car-side"></i></div>
      <div class="logo-text">Drive<span>Ease</span> Admin</div>
    </a>
    <div class="sidebar-nav-section">
      <div class="sidebar-section-label">Main</div>
      <a href="{{url('/admin/index')}}" class="admin-nav-item {{ request()->is('admin/index') || request()->is('admin') ? 'active' : '' }}"><i class="fas fa-chart-line"></i><span class="nav-text">Dashboard</span></a>

      <!-- Cars Submenu -->
      <div class="admin-nav-item {{ request()->is('admin/cars') || request()->is('admin/add-car') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#carsSubmenu" aria-expanded="{{ request()->is('admin/cars') || request()->is('admin/add-car') ? 'true' : 'false' }}">
        <i class="fas fa-car"></i><span class="nav-text">Cars</span><i class="fas fa-chevron-down nav-arrow ms-auto"></i>
      </div>
      <div class="admin-submenu collapse {{ request()->is('admin/cars') || request()->is('admin/add-car') ? 'show' : '' }}" id="carsSubmenu">
        <a href="{{url('/admin/add-car')}}" class="admin-submenu-item {{ request()->is('admin/add-car') ? 'active' : '' }}"><i class="fas fa-plus font-xs"></i> Add Cars</a>
        <a href="{{url('/admin/cars')}}" class="admin-submenu-item {{ request()->is('admin/cars') ? 'active' : '' }}"><i class="fas fa-tasks font-xs"></i> Manage Cars</a>
      </div>

      <!-- Categories Submenu -->
      <div class="admin-nav-item {{ request()->is('admin/categories*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#categoriesSubmenu" aria-expanded="{{ request()->is('admin/categories*') ? 'true' : 'false' }}">
        <i class="fas fa-tags"></i><span class="nav-text">Categories</span><i class="fas fa-chevron-down nav-arrow ms-auto"></i>
      </div>
      <div class="admin-submenu collapse {{ request()->is('admin/categories*') ? 'show' : '' }}" id="categoriesSubmenu">
        <a href="{{url('/admin/categories')}}" class="admin-submenu-item {{ request()->is('admin/categories') ? 'active' : '' }}"><i class="fas fa-plus font-xs"></i> Add Categories</a>
        <a href="{{url('/admin/categories')}}" class="admin-submenu-item {{ request()->is('admin/categories') ? 'active' : '' }}"><i class="fas fa-tasks font-xs"></i> Manage Categories</a>
      </div>
      <a href="{{url('/admin/brands')}}" class="admin-nav-item {{ request()->is('admin/brands') ? 'active' : '' }}"><i class="fas fa-copyright"></i><span class="nav-text">Brands</span></a>
      <a href="{{url('/admin/locations')}}" class="admin-nav-item {{ request()->is('admin/locations') ? 'active' : '' }}"><i class="fas fa-map-location-dot"></i><span class="nav-text">Locations</span></a>

      <div class="sidebar-section-label">Management</div>
      <a href="{{url('/admin/customers')}}" class="admin-nav-item {{ request()->is('admin/customers') ? 'active' : '' }}"><i class="fas fa-users"></i><span class="nav-text">Customers</span></a>

      <!-- Bookings Submenu -->
      <div class="admin-nav-item {{ request()->is('admin/bookings*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#bookingsSubmenu" aria-expanded="{{ request()->is('admin/bookings*') ? 'true' : 'false' }}">
        <i class="fas fa-calendar-check"></i><span class="nav-text">Bookings</span><span class="nav-badge" id="pendingBookingBadge">5</span><i class="fas fa-chevron-down nav-arrow ms-auto"></i>
      </div>
      <div class="admin-submenu collapse {{ request()->is('admin/bookings*') ? 'show' : '' }}" id="bookingsSubmenu">
        <a href="{{url('/admin/bookings')}}" class="admin-submenu-item {{ request()->is('admin/bookings') ? 'active' : '' }}"><i class="fas fa-list font-xs"></i> All Bookings</a>
        <a href="{{url('/admin/bookings?status=Pending')}}" class="admin-submenu-item"><i class="fas fa-clock text-warning font-xs"></i> Pending</a>
        <a href="{{url('/admin/bookings?status=Confirmed')}}" class="admin-submenu-item"><i class="fas fa-check text-info font-xs"></i> Confirmed</a>
        <a href="{{url('/admin/bookings?status=Active')}}" class="admin-submenu-item"><i class="fas fa-key text-success font-xs"></i> Active</a>
        <a href="{{url('/admin/bookings?status=Completed')}}" class="admin-submenu-item"><i class="fas fa-flag-checkered text-primary font-xs"></i> Completed</a>
        <a href="{{url('/admin/bookings?status=Cancelled')}}" class="admin-submenu-item"><i class="fas fa-ban text-danger font-xs"></i> Cancelled</a>
      </div>

      <a href="{{url('/admin/payments')}}" class="admin-nav-item {{ request()->is('admin/payments') ? 'active' : '' }}"><i class="fas fa-credit-card"></i><span class="nav-text">Payments</span></a>
      <a href="{{url('/admin/offers')}}" class="admin-nav-item {{ request()->is('admin/offers') ? 'active' : '' }}"><i class="fas fa-ticket"></i><span class="nav-text">Offers & Coupons</span></a>
      <a href="{{url('/admin/reviews')}}" class="admin-nav-item {{ request()->is('admin/reviews') ? 'active' : '' }}"><i class="fas fa-star"></i><span class="nav-text">Reviews</span></a>
      <a href="{{url('/admin/contact-messages')}}" class="admin-nav-item {{ request()->is('admin/contact-messages') ? 'active' : '' }}"><i class="fas fa-envelope"></i><span class="nav-text">Contact Messages</span></a>

      <div class="sidebar-section-label">System</div>
      <a href="{{url('/admin/notifications')}}" class="admin-nav-item {{ request()->is('admin/notifications') ? 'active' : '' }}"><i class="fas fa-bell"></i><span class="nav-text">Notifications</span></a>
      <a href="{{url('/admin/reports')}}" class="admin-nav-item {{ request()->is('admin/reports') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span class="nav-text">Reports & Analytics</span></a>
      <a href="{{url('/admin/settings')}}" class="admin-nav-item {{ request()->is('admin/settings') ? 'active' : '' }}"><i class="fas fa-gear"></i><span class="nav-text">Settings</span></a>
      <a href="{{url('/admin/profile')}}" class="admin-nav-item {{ request()->is('admin/profile') ? 'active' : '' }}"><i class="fas fa-user-shield"></i><span class="nav-text">Admin Profile</span></a>

      <a href="{{url('/admin/logout')}}" class="admin-nav-item text-danger mt-3"><i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span></a>
    </div>
  </aside>

  <!-- TOPBAR -->
  <header class="admin-topbar" id="adminTopbar">
    <button class="topbar-toggle" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button>
    <div class="topbar-search">
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" class="form-control" placeholder="Search cars, bookings, customers...">
      </div>
    </div>
    <div class="topbar-actions">
      <button class="dark-mode-toggle" id="themeToggleBtn" title="Toggle Dark/Light Mode"></button>
      
      <div class="dropdown">
        <button class="admin-profile-btn" data-bs-toggle="dropdown">
          <div class="admin-profile-avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
          <span class="admin-profile-name d-none d-sm-inline">{{ session('admin_name', 'Admin') }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
          <li><a class="dropdown-item" href="{{url('/admin/profile')}}"><i class="fas fa-user-shield me-2 text-primary"></i>Admin Profile</a></li>
          <li><a class="dropdown-item" href="{{url('/admin/settings')}}"><i class="fas fa-gear me-2 text-secondary"></i>Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="{{url('/admin/logout')}}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- MAIN PAGE CONTENT -->
  <main class="admin-main">
