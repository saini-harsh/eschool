<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstitutionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('institution')->check()) {
            return redirect()->route('login');
        }

        // Get the authenticated institution
        $institution = Auth::guard('institution')->user();
        
        // Check if institution is active
        if (!$institution->status) {
            Auth::guard('institution')->logout();
            return redirect()->route('login')->with('error', 'Your institution account has been deactivated.');
        }

        // Share the institution data with all views and controllers
        view()->share('currentInstitution', $institution);
        $request->merge(['current_institution_id' => $institution->id]);

        return $next($request);
    }
}
