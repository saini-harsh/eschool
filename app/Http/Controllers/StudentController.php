<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Auth;

class StudentController extends Controller
{

    //
    public function dashboard()
    {
        dd('student dashboard');
    }

    public function logout()
    {
        Auth::guard('teacher')->logout();
    }
}
