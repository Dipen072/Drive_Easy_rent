<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AdminGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('admin_id')) {
            Alert::info('Already Logged In', 'You are already logged in as Admin!');
            return redirect('/admin/index');
        }

        // Auto login via remember cookie if exists
        $cookieEmail = $request->cookie('remember_admin_email');
        $cookiePass  = $request->cookie('remember_admin_password');

        if ($cookieEmail && $cookiePass) {
            $admin = Admin::where('email', $cookieEmail)->first();
            if ($admin) {
                $passwordMatches = false;
                if ($cookiePass === $admin->password) {
                    $passwordMatches = true;
                } else {
                    try {
                        $passwordMatches = Hash::check($cookiePass, $admin->password);
                    } catch (\Throwable $e) {
                        $passwordMatches = false;
                    }
                }

                if ($passwordMatches) {
                    session()->put('admin_id', $admin->id);
                    session()->put('admin_name', $admin->name);
                    session()->put('admin_email', $admin->email);
                    return redirect('/admin/index');
                }
            }
        }

        return $next($request);
    }
}
