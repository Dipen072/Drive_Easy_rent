<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingCancelledAdminMail;
use App\Mail\BookingCancelledMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\NewBookingAdminMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\ExtraService;
use App\Models\Location;
use App\Services\BookingService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Ensure default database records exist (Locations, Extra Services, Coupons)
     */
    protected function ensureDefaultDataExists(): void
    {
        $locationsList = [
            ['name' => 'Mumbai Airport', 'city' => 'Mumbai', 'address' => 'Chhatrapati Shivaji Maharaj International Airport, Santacruz East, Mumbai - 400099', 'phone' => '+91 22 6685 1000', 'status' => 'Active'],
            ['name' => 'Delhi IGI Airport', 'city' => 'Delhi', 'address' => 'Indira Gandhi International Airport, New Delhi - 110037', 'phone' => '+91 11 2567 5000', 'status' => 'Active'],
            ['name' => 'Bangalore Koramangala', 'city' => 'Bangalore', 'address' => '12th Cross, Koramangala 4th Block, Bengaluru - 560034', 'phone' => '+91 80 4123 7800', 'status' => 'Active'],
            ['name' => 'Hyderabad Hitech City', 'city' => 'Hyderabad', 'address' => 'HUDA Techno Enclave, Hitech City, Hyderabad - 500081', 'phone' => '+91 40 6770 1234', 'status' => 'Active'],
            ['name' => 'Pune Station', 'city' => 'Pune', 'address' => 'Near Pune Railway Station, Shivajinagar, Pune - 411005', 'phone' => '+91 20 2553 8800', 'status' => 'Active'],
            ['name' => 'Chennai Anna Salai', 'city' => 'Chennai', 'address' => '45 Anna Salai, Thousand Lights, Chennai - 600006', 'phone' => '+91 44 2811 2200', 'status' => 'Active'],
            ['name' => 'Kolkata Park Street', 'city' => 'Kolkata', 'address' => '18 Park Street, Kolkata - 700016', 'phone' => '+91 33 2229 4400', 'status' => 'Active'],
            ['name' => 'Goa Airport Branch', 'city' => 'Goa', 'address' => 'Dabolim International Airport, South Goa - 403801', 'phone' => '+91 832 254 0800', 'status' => 'Active'],
            ['name' => 'Jaipur City Center', 'city' => 'Jaipur', 'address' => 'C-Scheme, Ashok Marg, Jaipur - 302001', 'phone' => '+91 141 222 7700', 'status' => 'Active'],
            ['name' => 'Ahmedabad SG Highway', 'city' => 'Ahmedabad', 'address' => 'SG Highway, Satellite, Ahmedabad - 380015', 'phone' => '+91 79 2646 5500', 'status' => 'Active'],
        ];

        foreach ($locationsList as $locData) {
            Location::firstOrCreate(
                ['name' => $locData['name']],
                $locData
            );
        }

        if (ExtraService::count() === 0) {
            ExtraService::create(['name' => 'Full Comprehensive Insurance', 'description' => 'Zero excess coverage for accidental damages & theft protection', 'price_per_day' => 500.00, 'icon_class' => 'fas fa-shield-halved', 'status' => 'Active']);
            ExtraService::create(['name' => 'Child Safety Seat', 'description' => 'Suitable for infants and toddlers up to 4 years', 'price_per_day' => 300.00, 'icon_class' => 'fas fa-baby-carriage', 'status' => 'Active']);
            ExtraService::create(['name' => 'Additional Driver Permission', 'description' => 'Allow second driver to legally operate the vehicle', 'price_per_day' => 400.00, 'icon_class' => 'fas fa-user-plus', 'status' => 'Active']);
            ExtraService::create(['name' => 'Portable Wi-Fi Hotspot', 'description' => 'High-speed 5G data coverage for up to 5 devices', 'price_per_day' => 250.00, 'icon_class' => 'fas fa-wifi', 'status' => 'Active']);
        }

        if (Coupon::count() === 0) {
            Coupon::create(['code' => 'FIRSTRIDE', 'discount_type' => 'percentage', 'discount_value' => 10.00, 'minimum_booking_amount' => 1000.00, 'maximum_discount' => 1500.00, 'start_date' => now()->subDays(10)->format('Y-m-d'), 'end_date' => now()->addYear()->format('Y-m-d'), 'usage_limit' => 500, 'status' => 'Active']);
            Coupon::create(['code' => 'SUMMER500', 'discount_type' => 'fixed', 'discount_value' => 500.00, 'minimum_booking_amount' => 2000.00, 'maximum_discount' => 500.00, 'start_date' => now()->subDays(10)->format('Y-m-d'), 'end_date' => now()->addYear()->format('Y-m-d'), 'usage_limit' => 200, 'status' => 'Active']);
        }
    }

    /**
     * Display the Frontend Booking Checkout Page
     */
    public function showBookingPage(Request $request, $carParam = null)
    {
        $this->ensureDefaultDataExists();

        $carId = $carParam 
            ?? $request->query('car') 
            ?? $request->query('car_id') 
            ?? $request->query('id');

        $car = null;
        if ($carId) {
            if ($carId instanceof Car) {
                $car = $carId->loadMissing('category');
            } else {
                $car = Car::with('category')->find($carId);
            }
        }

        if (!$car) {
            $car = Car::with('category')->where('status', 'Available')->first();
        }

        if (!$car) {
            Alert::error('No Cars Available', 'There are no active vehicles available for booking right now.');
            return redirect('/cars');
        }

        $locations = Location::where('status', 'Active')->get();
        $extraServices = ExtraService::where('status', 'Active')->get();

        $customer = null;
        if (session()->has('user_id')) {
            $customer = Customer::find(session('user_id'));
        }

        return view('website.booking', compact('car', 'locations', 'extraServices', 'customer'));
    }

    /**
     * AJAX endpoint for server-side price breakdown calculation
     */
    public function calculatePrice(Request $request)
    {
        $this->ensureDefaultDataExists();

        $request->validate([
            'car_id'         => 'required|exists:cars,id',
            'pickup_date'    => 'required|date',
            'return_date'    => 'required|date',
            'extra_services' => 'nullable|array',
            'coupon_code'    => 'nullable|string',
        ]);

        try {
            $pricing = $this->bookingService->calculatePricing(
                (int) $request->car_id,
                $request->pickup_date,
                $request->return_date,
                $request->extra_services ?? [],
                $request->coupon_code
            );

            return response()->json([
                'success' => true,
                'data'    => $pricing,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * AJAX endpoint to check car availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'car_id'      => 'required|exists:cars,id',
            'pickup_date' => 'required|date',
            'return_date' => 'required|date',
        ]);

        $car = Car::find($request->car_id);
        $available = $car ? $car->isAvailableForDates($request->pickup_date, $request->return_date) : false;

        return response()->json([
            'available' => $available,
            'message'   => $available ? 'Car is available!' : 'Selected car is NOT available for these dates.',
        ]);
    }

    /**
     * Process & Submit Booking
     */
    public function store(StoreBookingRequest $request)
    {
        $this->ensureDefaultDataExists();

        try {
            $customer = null;

            if (session()->has('user_id')) {
                $customer = Customer::find(session('user_id'));
            }

            if (!$customer) {
                // Check if customer already exists by email
                $customer = Customer::where('email', $request->email)->first();

                if (!$customer) {
                    // Split full name into first and last name
                    $parts = explode(' ', trim($request->full_name), 2);
                    $firstName = $parts[0] ?? $request->full_name;
                    $lastName  = $parts[1] ?? 'Customer';

                    $customer = Customer::create([
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'email'      => $request->email,
                        'phone'      => $request->phone,
                        'password'   => Hash::make('DriveEase@123'),
                        'address'    => $request->address,
                        'city'       => $request->city,
                        'state'      => $request->state,
                        'zip_code'   => $request->zip,
                        'has_dl'     => true,
                        'dl_number'  => $request->driving_license,
                        'status'     => 'Active',
                    ]);
                }

                // Log customer in session
                session()->put('user_id', $customer->id);
                session()->put('user_name', $customer->first_name . ' ' . $customer->last_name);
            }

            // Update profile fields & email if edited in form
            $customer->update([
                'email'      => $request->email ?? $customer->email,
                'phone'      => $request->phone,
                'address'    => $request->address,
                'city'       => $request->city,
                'state'      => $request->state,
                'zip_code'   => $request->zip,
                'dl_number'  => $request->driving_license ?? $customer->dl_number,
            ]);

            // Create Booking via Service
            $booking = $this->bookingService->createBooking($customer->id, $request->validated());

            // Reload all relationships for fresh mailable rendering
            $booking->load(['customer', 'car.category', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment']);

            // Target recipient email specified in checkout form or customer account
            $recipientEmail = $request->email ?? ($customer ? $customer->email : null) ?? ($booking->customer ? $booking->customer->email : null);

            // 1. Dispatch Customer Reservation Confirmation Email
            try {
                if ($recipientEmail) {
                    Mail::to($recipientEmail)->send(new BookingConfirmationMail($booking));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Customer Booking Confirmation Mail Exception for ' . ($recipientEmail ?? 'unknown') . ': ' . $e->getMessage());
            }

            // 2. Dispatch Customer Payment Receipt Email (if payment is Paid)
            try {
                if ($recipientEmail && $booking->payment_status === 'Paid' && $booking->payment) {
                    Mail::to($recipientEmail)->send(new PaymentSuccessMail($booking, $booking->payment));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Customer Payment Success Mail Exception for ' . ($recipientEmail ?? 'unknown') . ': ' . $e->getMessage());
            }

            // 3. Dispatch Admin Notification Email
            try {
                $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@driveease.in'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new NewBookingAdminMail($booking));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Admin Booking Email Exception: ' . $e->getMessage());
            }

            // 2. Dispatch Mobile SMS Text Message
            try {
                SmsService::sendBookingSms($booking);
            } catch (\Throwable $e) {
                // Silent fallback
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'        => true,
                    'booking_id'     => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'redirect_url'   => url('/booking/' . $booking->booking_number . '/success'),
                ]);
            }

            Alert::success('Booking Created!', 'Your reservation ' . $booking->booking_number . ' has been created successfully.');
            return redirect('/booking/' . $booking->booking_number . '/success');
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            Alert::error('Booking Failed', $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display Booking Success / Confirmation Page
     */
    public function success($bookingNumber)
    {
        $booking = Booking::with(['car.category', 'customer', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment'])
            ->where('booking_number', $bookingNumber)
            ->firstOrFail();

        return view('website.booking-success', compact('booking'));
    }

    /**
     * Customer Dashboard: My Bookings List
     */
    public function myBookings()
    {
        if (!session()->has('user_id')) {
            Alert::warning('Login Required', 'Please log in to view your bookings.');
            return redirect('/login');
        }

        $customer = Customer::findOrFail(session('user_id'));
        $bookings = Booking::with(['car', 'pickupLocation', 'dropoffLocation', 'payment'])
            ->where('customer_id', $customer->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('website.my-bookings', compact('customer', 'bookings'));
    }

    /**
     * Customer Dashboard: Booking Details View
     */
    public function showMyBooking($id)
    {
        if (!session()->has('user_id')) {
            Alert::warning('Login Required', 'Please log in to view your booking.');
            return redirect('/login');
        }

        $customer = Customer::findOrFail(session('user_id'));
        $booking = Booking::with(['car.category', 'customer', 'pickupLocation', 'dropoffLocation', 'extraServices', 'payment'])
            ->where('customer_id', $customer->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('website.booking-details', compact('customer', 'booking'));
    }

    /**
     * Customer Dashboard: Cancel Booking
     */
    public function cancelBooking(Request $request, $id)
    {
        if (!session()->has('user_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('customer_id', session('user_id'))
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
        }

        if (in_array($booking->booking_status, ['Completed', 'Cancelled'])) {
            return response()->json(['success' => false, 'message' => 'This booking cannot be cancelled.'], 400);
        }

        $booking->update([
            'booking_status'      => 'Cancelled',
            'cancellation_reason' => $request->input('reason', 'Cancelled by customer'),
            'cancelled_at'        => now(),
        ]);

        if ($booking->payment) {
            $booking->payment->update([
                'payment_status' => ($booking->payment->payment_status === 'Paid') ? 'Refunded' : 'Failed',
            ]);
        }

        // Dispatch Email & Mobile SMS Notifications
        try {
            if ($booking->customer && $booking->customer->email) {
                Mail::to($booking->customer->email)->send(new BookingCancelledMail($booking));
            }

            $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@driveease.in'));
            Mail::to($adminEmail)->send(new BookingCancelledAdminMail($booking));
            
            SmsService::sendCancellationSms($booking);
        } catch (\Throwable $e) {
            // Silent fallback
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking ' . $booking->booking_number . ' has been cancelled successfully.',
        ]);
    }
}
