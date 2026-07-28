<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CarController;

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

Route::get('/contact',[ContactController::class,'create']);
Route::post('/ins_contact',[ContactController::class,'store']);

Route::get('/faq', function () {
    return view('website.faq');
});

Route::get('/booking', function () {
    return view('website.booking');
});

Route::get('/booking-success', function () {
    return view('website.booking-success');
});

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

// Authentication Routes
Route::get('/login', function () {
    return view('website.auth.login');
});
Route::post('/login_auth', [CustomerController::class, 'login_auth']);

Route::get('/register', [CustomerController::class, 'create']);
Route::post('/register', [CustomerController::class, 'store']);

Route::get('/forgot-password', function () {
    return view('website.auth.forgot-password');
});

Route::get('/reset-password', function () {
    return view('website.auth.reset-password');
});

Route::get('/user_profile', [CustomerController::class, 'profile']);
Route::get('/profile', [CustomerController::class, 'profile']);
Route::get('/user_logout', [CustomerController::class, 'logout']);

// Admin Panel Routes
Route::prefix('admin')->group(function () {
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
    Route::get('/del-car/{id}', [CarController::class, 'destroy']);

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/save-category', [CategoryController::class, 'store']);
    Route::get('/del-category/{id}', [CategoryController::class, 'destroy']);

    Route::get('/bookings', function () {
        return view('admin.bookings');
    });
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
    Route::get('/login', function () {
        return view('admin.login');
    });
    Route::post('/login_auth', [AdminController::class, 'admin_login_auth']);
    Route::get('/logout', [AdminController::class, 'admin_logout']);
    Route::get('/notifications', function () {
        return view('admin.notifications');
    });
    Route::get('/offers', function () {
        return view('admin.offers');
    });
    Route::get('/payments', function () {
        return view('admin.payments');
    });
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

