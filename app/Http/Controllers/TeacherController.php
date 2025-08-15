<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    //
    public function dashboard()
    {
       return view('teacher.index');
    }

    public function logout()
    {
        Auth::guard('teacher')->logout();
    }
}
