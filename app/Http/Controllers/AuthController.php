<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Your custom HTML
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $role = $request->input('role');

        $guards = [
            'admin' => 'admin',
            'institution' => 'institution',
            'teacher' => 'teacher',
            'student' => 'student',
        ];

        $redirects = [
            'admin' => route('admin.dashboard'),
            'institution' => '/institution/dashboard',
            'teacher' => route('teacher.dashboard'),
            'student' => route('student.dashboard'),
        ];

        if (!isset($guards[$role])) {
            return back()->with('error', 'Invalid role selected.');
        }

        $guard = $guards[$role];

        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Store the guard name in session for logout
            $request->session()->put('auth_guard', $guard);

            return redirect()->intended($redirects[$role]);
        }

        return back()->with('error', 'Invalid credentials for the selected role.');
    }

    public function logout(Request $request)
    {
        $guard = $request->session()->get('auth_guard', 'web');
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
