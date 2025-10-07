<?php

namespace App\Http\Controllers\Institution\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invigilator;

class InvigilatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        // Code to list invigilators
        $lists = Invigilator::all();
        return view('institution.exam_management.invigilators.index', compact('lists'));
    }
}
