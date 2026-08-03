<?php
if (!function_exists('active')) {
    function active($currect_page) {
        $url_array = explode('/', $_SERVER['REQUEST_URI']);
        $url = end($url_array);
        if ($currect_page == $url) {
            echo 'active';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DriveEase — Rent Quality Cars at the Best Prices</title>
  <meta name="description" content="DriveEase is India's premier car rental platform. Rent quality cars at the best prices with simple and secure online booking.">
  
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Flatpickr -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- Leaflet CSS for Map Picker -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Website CSS Paths -->
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <link rel="stylesheet" href="{{url('website/css/home.css')}}">
  <link rel="stylesheet" href="{{url('website/css/chatbot.css')}}">
</head>
<body>

<!-- DriveEase Header Navigation -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand" href="{{url('/index')}}">
      <div class="brand-logo"><i class="fas fa-car-side"></i></div>
      <span class="brand-name">Drive<span>Ease</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Toggle navigation">
      <i class="fas fa-bars text-secondary fs-5"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link <?php active('index')?>" href="{{url('/index')}}">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php active('cars')?>" href="{{url('/cars')}}">Cars</a></li>
        <li class="nav-item"><a class="nav-link <?php active('locations')?>" href="{{url('/locations')}}">Locations</a></li>
        <li class="nav-item"><a class="nav-link <?php active('offers')?>" href="{{url('/offers')}}">Offers</a></li>
        <li class="nav-item"><a class="nav-link <?php active('about')?>" href="{{url('/about')}}">About Us</a></li>
        <li class="nav-item"><a class="nav-link <?php active('contact')?>" href="{{url('/contact')}}">Contact</a></li>
      </ul>
      <div class="nav-auth d-flex align-items-center gap-2 mt-2 mt-lg-0">
        @if(session('user_id'))
        <a href="{{url('/user_profile')}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-circle me-1"></i>Hi {{session('user_name')}} <i class="fa fa-edit ms-1"></i></a>
        <a href="{{url('/user_logout')}}" class="btn btn-danger btn-sm">Logout</a>
        @else
        <a href="{{url('/login')}}" class="btn btn-outline-primary btn-sm <?php active('login')?>">Login</a>
        <a href="{{url('/register')}}" class="btn btn-primary btn-sm <?php active('register')?>">Sign Up</a>
        @endif
      </div>
    </div>
  </div>
</nav>
