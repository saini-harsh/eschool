<?php

namespace App\Http\Controllers\Teacher\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function index()
    {
        // Logic to display attendance page
        return view('teacher.administration.attendance.index');
    }
}
