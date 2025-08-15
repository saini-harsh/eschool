<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function dashboard()
    {
       return view('teacher.index');
    }

    public function attendance()
    {
        return view('teacher.attendance.index');
    }

    public function logout()
    {
        Auth::guard('teacher')->logout();
    }
}
