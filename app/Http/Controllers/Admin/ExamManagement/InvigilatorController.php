<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invigilator;

class InvigilatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Code to list invigilators
        $lists = Invigilator::all();
        return view('admin.exam_management.invigilators.index', compact('lists'));
    }
}
