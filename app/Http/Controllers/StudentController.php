<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        return view('student.index', compact('student'));
    }
}
