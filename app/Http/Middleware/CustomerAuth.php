<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user_id')) {
            Alert::warning('Login Required', 'Please log in first to access the website!');
            return redirect('/login');
        }

        $customer = Customer::find(session('user_id'));
        if (!$customer || $customer->status == 'Blocked' || $customer->status == 'Block') {
            session()->forget(['user_id', 'user_name']);
            Alert::error('Account Blocked', 'Your account has been blocked by Admin.');
            return redirect('/login');
        }

        return $next($request);
    }
}
