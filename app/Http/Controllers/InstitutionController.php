<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionController extends Controller
{
    public function dashboard()
    {
        $institution = Auth::guard('institution')->user();
        
        return view('institution.index', compact('institution'));
    }
}