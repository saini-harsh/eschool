<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    //
    public function dashboard()
    {
        dd('student dashboard');
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('login');
    }
}
