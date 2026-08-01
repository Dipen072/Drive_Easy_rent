<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeCustomerMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource for Admin panel.
     */
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('admin.customers', compact('customers'));
    }

    /**
     * Delete customer record
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete();
            Alert::success('Deleted', 'Customer deleted successfully!');
        }
        return redirect('/admin/customers');
    }

    /**
     * Toggle Customer status (Active / Blocked)
     */
    public function status($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->status = ($customer->status == 'Active' || $customer->status == 'Unblock') ? 'Blocked' : 'Active';
            $customer->save();
            Alert::success('Updated', 'Customer status updated successfully!');
        }
        return redirect('/admin/customers');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('website.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:customers,email',
            'phone'           => 'required|string|max:20',
            'password'        => 'required|string|min:6',
            'dob'             => 'nullable',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string',
            'state'           => 'nullable|string',
            'country'         => 'nullable|string',
            'zip_code'        => 'nullable|string',
            'dl_number'       => 'nullable|string',
            'dl_expiry'       => 'nullable',
            'dl_file'         => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'alt_id_type'     => 'nullable|string',
            'alt_id_number'   => 'nullable|string',
            'alt_id_file'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $customer = new Customer();
        $customer->first_name = $request->first_name;
        $customer->last_name  = $request->last_name;
        $customer->email      = $request->email;
        $customer->phone      = $request->phone;
        $customer->dob        = $request->dob;
        $customer->password   = Hash::make($request->password);
        $customer->address    = $request->address;
        $customer->city       = $request->city;
        $customer->state      = $request->state;
        $customer->country    = $request->country ?? 'India';
        $customer->zip_code   = $request->zip_code;

        // Handle Profile Picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '_avatar_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('upload/customers/avatars');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $fileName);
            $customer->profile_picture = 'upload/customers/avatars/' . $fileName;
        }

        // Handle Driving License vs Alternate ID
        $hasNoDl = $request->has('no_dl');
        $customer->has_dl = !$hasNoDl;

        if (!$hasNoDl) {
            $customer->dl_number = $request->dl_number;
            $customer->dl_expiry = $request->dl_expiry;

            if ($request->hasFile('dl_file')) {
                $file = $request->file('dl_file');
                $fileName = time() . '_dl_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('upload/customers/documents');
                if (!file_exists($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                $file->move($targetDir, $fileName);
                $customer->dl_file = 'upload/customers/documents/' . $fileName;
            }
        } else {
            $customer->alt_id_type   = $request->alt_id_type;
            $customer->alt_id_number = $request->alt_id_number;

            if ($request->hasFile('alt_id_file')) {
                $file = $request->file('alt_id_file');
                $fileName = time() . '_altid_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $targetDir = public_path('upload/customers/documents');
                if (!file_exists($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                $file->move($targetDir, $fileName);
                $customer->alt_id_file = 'upload/customers/documents/' . $fileName;
            }
        }

        $customer->status = 'Active';
        $customer->save();

        // Send Welcome Customer Email asynchronously
        try {
            Mail::to($customer->email)->send(new WelcomeCustomerMail($customer));
        } catch (\Throwable $e) {
            // Logged automatically in mailable or silent fallback
        }

        Alert::success('Successfully Registered', 'Customer Registered Successfully! Please login to your account.');
        return redirect('/login');
    }

    /**
     * Customer Login Authentication
     */
    public function login_auth(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $data = Customer::where('email', $request->email)->first();
        if ($data) {
            // Check if customer account is blocked
            if ($data->status == 'Blocked' || $data->status == 'Block') {
                Alert::error('Login Failed', 'Your account has been Blocked by Admin!');
                return back();
            }

            if (Hash::check($request->password, $data->password)) {
                if ($data->status == "Active" || $data->status == "Unblock") {
                    session()->put('user_id', $data->id);
                    session()->put('user_name', $data->first_name . ' ' . $data->last_name);

                    Alert::success('Login Success', 'Login Successfully');
                    return redirect('/user_profile');
                } else {
                    Alert::error('Login Failed', 'Your account is Blocked!');
                    return back();
                }
            } else {
                Alert::error('Failed', 'Login Failed Due to Wrong Password');
                return back();
            }
        } else {
            Alert::error('Failed', 'Login Failed Due to Wrong Email');
            return back();
        }
    }

    /**
     * Customer Profile Page
     */
    public function profile()
    {
        if (!session()->has('user_id')) {
            Alert::warning('Login Required', 'Please log in to view your profile.');
            return redirect('/login');
        }

        $customer = Customer::find(session('user_id'));
        if (!$customer || $customer->status == 'Blocked' || $customer->status == 'Block') {
            session()->pull('user_id');
            session()->pull('user_name');
            Alert::error('Account Blocked', 'Your account has been blocked. Access denied.');
            return redirect('/login');
        }

        return view('website.profile', compact('customer'));
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request)
    {
        if (!session()->has('user_id')) {
            Alert::warning('Login Required', 'Please log in to update your profile.');
            return redirect('/login');
        }

        $customer = Customer::find(session('user_id'));
        if (!$customer) {
            Alert::error('Error', 'Customer profile not found.');
            return redirect('/login');
        }

        $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:customers,email,' . $customer->id,
            'phone'           => 'required|string|max:20',
            'dob'             => 'nullable',
            'address'         => 'nullable|string',
            'city'            => 'nullable|string',
            'state'           => 'nullable|string',
            'country'         => 'nullable|string',
            'zip_code'        => 'nullable|string',
            'dl_number'       => 'nullable|string',
            'dl_expiry'       => 'nullable',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $customer->first_name = $request->first_name;
        $customer->last_name  = $request->last_name;
        $customer->email      = $request->email;
        $customer->phone      = $request->phone;
        $customer->dob        = $request->dob;
        $customer->address    = $request->address;
        $customer->city       = $request->city;
        $customer->state      = $request->state;
        $customer->country    = $request->country ?? 'India';
        $customer->zip_code   = $request->zip_code;
        if ($request->filled('dl_number')) {
            $customer->dl_number = $request->dl_number;
        }
        if ($request->filled('dl_expiry')) {
            $customer->dl_expiry = $request->dl_expiry;
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '_avatar_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('upload/customers/avatars');
            if (!file_exists($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $fileName);
            $customer->profile_picture = 'upload/customers/avatars/' . $fileName;
        }

        $customer->save();

        // Update session user_name
        session()->put('user_name', $customer->first_name . ' ' . $customer->last_name);

        Alert::success('Profile Updated', 'Your profile details have been updated successfully!');
        return redirect()->back();
    }

    /**
     * Customer Logout
     */
    public function logout()
    {
        session()->pull('user_id');
        session()->pull('user_name');

        Alert::success('Logged Out', 'Logout Successfully');
        return redirect('/login');
    }

    /**
     * Show Forgot Password View
     */
    public function showForgotPassword()
    {
        return view('website.auth.forgot-password');
    }

    /**
     * Send OTP to customer's registered email
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            Alert::error('Email Not Found', 'This email address is not registered in our system.');
            return back()->withInput();
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP & Email in session for 10 minutes
        session()->put('reset_email', $customer->email);
        session()->put('reset_otp', $otp);
        session()->put('reset_otp_expires_at', now()->addMinutes(10));
        session()->forget('reset_otp_verified');

        // Send OTP email
        try {
            Mail::to($customer->email)->send(new \App\Mail\PasswordResetOtpMail($otp, $customer->first_name, $customer->email));
        } catch (\Throwable $e) {
            // Email dispatch fallback
        }

        Alert::success('OTP Sent!', 'A 6-digit OTP verification code has been sent to ' . $customer->email);
        return redirect('/verify-otp');
    }

    /**
     * Show Verify OTP View
     */
    public function showVerifyOtp()
    {
        if (!session()->has('reset_email') || !session()->has('reset_otp')) {
            Alert::warning('Session Expired', 'Please enter your registered email to get a reset OTP.');
            return redirect('/forgot-password');
        }

        return view('website.auth.verify-otp');
    }

    /**
     * Verify submitted OTP
     */
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        if (!session()->has('reset_otp') || !session()->has('reset_email')) {
            Alert::error('Session Expired', 'OTP session has expired. Please request a new OTP.');
            return redirect('/forgot-password');
        }

        $expiresAt = session('reset_otp_expires_at');
        if ($expiresAt && now()->greaterThan($expiresAt)) {
            session()->forget(['reset_email', 'reset_otp', 'reset_otp_expires_at', 'reset_otp_verified']);
            Alert::error('OTP Expired', 'The OTP code has expired. Please request a new one.');
            return redirect('/forgot-password');
        }

        if ($request->otp == session('reset_otp')) {
            session()->put('reset_otp_verified', true);
            Alert::success('OTP Verified', 'OTP verified successfully! Please enter your new password.');
            return redirect('/reset-password');
        } else {
            Alert::error('Invalid OTP', 'The OTP code you entered is incorrect. Please try again.');
            return back();
        }
    }

    /**
     * Show Reset Password View
     */
    public function showResetPassword()
    {
        if (!session()->has('reset_otp_verified') || !session()->has('reset_email')) {
            Alert::warning('Unauthorized', 'Please verify your OTP before resetting password.');
            return redirect('/forgot-password');
        }

        return view('website.auth.reset-password');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        if (!session()->has('reset_otp_verified') || !session()->has('reset_email')) {
            Alert::error('Session Expired', 'Password reset session expired. Please start again.');
            return redirect('/forgot-password');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $customer = Customer::where('email', session('reset_email'))->first();

        if (!$customer) {
            Alert::error('Error', 'Customer record not found.');
            return redirect('/login');
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        // Clear reset session variables
        session()->forget(['reset_email', 'reset_otp', 'reset_otp_expires_at', 'reset_otp_verified']);

        Alert::success('Password Updated', 'Your password has been reset successfully! Please log in with your new password.');
        return redirect('/login');
    }
}
