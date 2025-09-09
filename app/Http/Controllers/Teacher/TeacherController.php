<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        return view('teacher.dashboard');
    }

    public function attendance()
    {
        return view('teacher.attendance.index');
    }

    public function logout()
    {
        Auth::guard('teacher')->logout();
        return redirect()->route('login');
    }
}
