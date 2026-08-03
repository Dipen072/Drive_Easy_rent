<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\ChatbotController;

// Customer Guest Routes (Only accessible when Customer is NOT logged in)
Route::middleware(['customer.guest'])->group(function () {
    Route::get('/login', function () {
        return view('website.auth.login');
    });
    Route::post('/login_auth', [CustomerController::class, 'login_auth']);

    Route::get('/register', [CustomerController::class, 'create']);
    Route::post('/register', [CustomerController::class, 'store']);

    // Customer Forgot Password & OTP Reset Routes
    Route::get('/forgot-password', [CustomerController::class, 'showForgotPassword']);
    Route::post('/send-reset-otp', [CustomerController::class, 'sendResetOtp']);

    Route::get('/verify-otp', [CustomerController::class, 'showVerifyOtp']);
    Route::post('/verify-reset-otp', [CustomerController::class, 'verifyResetOtp']);

    Route::get('/reset-password', [CustomerController::class, 'showResetPassword']);
    Route::post('/update-password', [CustomerController::class, 'updatePassword']);
});

// Customer Auth Routes (Protected - ALL Website pages require Customer Login)
Route::middleware(['customer.auth'])->group(function () {
    // Website Main Pages
    Route::get('/', [CarController::class, 'websiteIndex']);
    Route::get('/index', [CarController::class, 'websiteIndex']);
    Route::get('/cars', [CarController::class, 'websiteCars']);

    Route::get('/about', function () {
        return view('website.about');
    });

    Route::get('/car-details', function () {
        return view('website.car-details');
    });

    Route::get('/locations', function () {
        return view('website.locations');
    });

    Route::get('/offers', function () {
        return view('website.offers');
    });

    Route::get('/contact', [ContactController::class, 'create']);
    Route::post('/ins_contact', [ContactController::class, 'store']);

    Route::get('/faq', function () {
        return view('website.faq');
    });

    // Booking Routes
    Route::get('/booking', [BookingController::class, 'showBookingPage']);
    Route::get('/booking/{car}', [BookingController::class, 'showBookingPage']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::post('/booking/calculate-price', [BookingController::class, 'calculatePrice']);
    Route::post('/booking/check-availability', [BookingController::class, 'checkAvailability']);
    Route::get('/booking/{booking_number}/success', [BookingController::class, 'success']);

    // Customer Dashboard & Profile
    Route::get('/user_profile', [CustomerController::class, 'profile']);
    Route::get('/profile', [CustomerController::class, 'profile']);
    Route::post('/update-profile', [CustomerController::class, 'updateProfile']);
    Route::get('/user_logout', [CustomerController::class, 'logout']);

    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::get('/my-bookings/{id}', [BookingController::class, 'showMyBooking']);
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancelBooking']);

    // Coupon API
    Route::post('/apply-coupon', [CouponController::class, 'apply']);

    // Payment API (Razorpay & Callback)
    Route::post('/payment/create-order', [PaymentController::class, 'createRazorpayOrder']);
    Route::post('/payment/callback', [PaymentController::class, 'callback']);

    // Policies
    Route::get('/privacy-policy', function () {
        return view('website.privacy-policy');
    });

    Route::get('/terms', function () {
        return view('website.terms');
    });

    Route::get('/refund-policy', function () {
        return view('website.refund-policy');
    });

    Route::get('/rental-policy', function () {
        return view('website.rental-policy');
    });

    Route::get('/cancellation-policy', function () {
        return view('website.cancellation-policy');
    });
});

// AI Chatbot API (Accessible to all visitors & logged in users)
Route::post('/chatbot/message', [ChatbotController::class, 'respond']);

// Admin Guest Routes (Only accessible when Admin is NOT logged in)
Route::middleware(['admin.guest'])->prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm']);
    Route::post('/login_auth', [AdminController::class, 'admin_login_auth']);
});

// Admin Auth Routes (Protected - requires Admin Login)
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    });
    Route::get('/index', function () {
        return view('admin.index');
    });

    // Cars Management
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/add-car', [CarController::class, 'create']);
    Route::post('/store-car', [CarController::class, 'store']);
    Route::get('/edit-car/{id}', [CarController::class, 'edit']);
    Route::post('/update-car/{id}', [CarController::class, 'update']);
    Route::get('/del-car/{id}', [CarController::class, 'destroy']);

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/save-category', [CategoryController::class, 'store']);
    Route::get('/del-category/{id}', [CategoryController::class, 'destroy']);

    // Admin Bookings Management
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show']);
    Route::match(['POST', 'PATCH'], '/bookings/{id}/status', [AdminBookingController::class, 'updateStatus']);
    Route::post('/bookings/{id}/approve-cash', [AdminBookingController::class, 'approveCash']);

    Route::get('/brands', function () {
        return view('admin.brands');
    });
    Route::get('/contact-messages', [ContactController::class, 'index']);
    Route::get('/del_contact/{id}', [ContactController::class, 'destroy']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/del_customer/{id}', [CustomerController::class, 'destroy']);
    Route::get('/status_customer/{id}', [CustomerController::class, 'status']);
    Route::get('/locations', function () {
        return view('admin.locations');
    });
    Route::get('/logout', [AdminController::class, 'admin_logout']);
    Route::get('/notifications', function () {
        return view('admin.notifications');
    });
    Route::get('/offers', function () {
        return view('admin.offers');
    });
    Route::get('/payments', [AdminBookingController::class, 'payments']);
    Route::get('/profile', [AdminController::class, 'admin_profile']);
    Route::get('/reports', function () {
        return view('admin.reports');
    });
    Route::get('/reviews', function () {
        return view('admin.reviews');
    });
    Route::get('/settings', function () {
        return view('admin.settings');
    });
});
