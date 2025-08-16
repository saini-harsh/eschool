<?php

namespace App\Http\Controllers\Admin\Academic;
use App\Models\Teacher;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class AssignClassTeacherController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Logic to retrieve and display class teachers
        $institutions = Institution::all(); // Retrieve all institutions
        // You can also retrieve teachers and classes if needed
        // For example, you might want to pass teachers and classes to the view
        $teachers = Teacher::all();
        $classes = SchoolClass::all(); // Placeholder for classes data
        return view('admin.academic.assign-teacher', compact('teachers', 'classes','institutions'));
    }
}
