<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_id')) {
            Alert::warning('Access Denied', 'Please log in to access the Admin Panel!');
            return redirect('/admin/login');
        }

        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin) {
            session()->forget(['admin_id', 'admin_name', 'admin_email']);
            Alert::error('Access Denied', 'Invalid admin session. Please log in again!');
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
