<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;

class BoardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Hostel::with(['student.schoolClass', 'student.section', 'institution']);

        // Filter by institution
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('admission_number', 'like', '%' . $search . '%')
                  ->orWhere('roll_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $boardingStudents = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $institutions = Institution::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();

        return view('admin.administration.boarding.index', compact('boardingStudents', 'institutions', 'classes'));
    }
}

