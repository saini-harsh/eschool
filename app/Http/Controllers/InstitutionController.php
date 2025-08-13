<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionController extends Controller
{

    //
    public function dashboard()
    {
        dd('Insttutions dashboard');
    }

    public function logout()
    {
        Auth::guard('teacher')->logout();
    }
}
