<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated with admin guard
        if (!Auth::guard('admin')->check()) {
            // If not authenticated, redirect to login with admin role pre-selected
            return redirect()->route('login')->with('error', 'Please login as admin to access this page.');
        }

        // Check if the authenticated user is actually an admin
        $admin = Auth::guard('admin')->user();
        if (!$admin || $admin->role !== 'admin') {
            Auth::guard('admin')->logout();
            return redirect()->route('login')->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
