<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    /**
     * Show Admin Login Form
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Admin Login Authentication
     */
    public function admin_login_auth(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $data = Admin::where('email', $request->email)->first();
        if ($data) {
            // Support both Plain-text password (direct phpMyAdmin insert) & Hashed password safely
            $passwordMatches = false;

            if ($request->password === $data->password) {
                $passwordMatches = true;
            } else {
                try {
                    $passwordMatches = Hash::check($request->password, $data->password);
                } catch (\Throwable $e) {
                    $passwordMatches = false;
                }
            }

            if ($passwordMatches) {
                session()->put('admin_id', $data->id);
                session()->put('admin_name', $data->name);
                session()->put('admin_email', $data->email);

                // Handle Remember Me Cookies (Store for 30 days)
                if ($request->has('remember')) {
                    Cookie::queue('remember_admin_email', $request->email, 60 * 24 * 30);
                    Cookie::queue('remember_admin_password', $request->password, 60 * 24 * 30);
                } else {
                    Cookie::queue(Cookie::forget('remember_admin_email'));
                    Cookie::queue(Cookie::forget('remember_admin_password'));
                }

                Alert::success('Login Success', 'Welcome to Admin Dashboard, ' . $data->name . '!');
                return redirect('/admin/index');
            } else {
                Alert::error('Login Failed', 'Invalid Admin Password!');
                return back();
            }
        } else {
            Alert::error('Login Failed', 'Admin Email not found in Database!');
            return back();
        }
    }

    /**
     * Display Admin Profile Page with database details
     */
    public function admin_profile()
    {
        if (!session()->has('admin_id')) {
            Alert::warning('Login Required', 'Please log in to access Admin Panel.');
            return redirect('/admin/login');
        }

        $admin = Admin::find(session('admin_id'));
        if (!$admin) {
            session()->pull('admin_id');
            session()->pull('admin_name');
            session()->pull('admin_email');
            return redirect('/admin/login');
        }

        return view('admin.profile', compact('admin'));
    }

    /**
     * Admin Logout
     */
    public function admin_logout()
    {
        session()->pull('admin_id');
        session()->pull('admin_name');
        session()->pull('admin_email');

        Alert::success('Logged Out', 'Logout Successfully!');
        return redirect('/admin/login');
    }
}
