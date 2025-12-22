<?php

namespace App\Http\Controllers\Institution\Administration;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index(Request $request)
    {
        $institutionId = auth('institution')->id();
        
        $query = Hostel::where('institution_id', $institutionId)
            ->with(['student.schoolClass', 'student.section']);

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

        // Filter by section
        if ($request->filled('section_id')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        $boardingStudents = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get();
        
        $sections = collect();
        if ($request->filled('class_id')) {
            $sections = Section::where('class_id', $request->class_id)
                ->where('status', 1)
                ->get();
        }

        return view('institution.administration.boarding.index', compact('boardingStudents', 'classes', 'sections'));
    }

    public function create()
    {
        $institutionId = auth('institution')->id();
        
        // Get students who are not already in boarding
        $existingBoardingStudentIds = Hostel::where('institution_id', $institutionId)
            ->pluck('student_id')
            ->toArray();
        
        $availableStudents = Student::where('institution_id', $institutionId)
            ->whereNotIn('id', $existingBoardingStudentIds)
            ->where('status', 1)
            ->with(['schoolClass', 'section'])
            ->orderBy('first_name')
            ->get();
        
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get();
        
        return view('institution.administration.boarding.create', compact('availableStudents', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $institutionId = auth('institution')->id();
        
        // Check if student already in boarding
        $existing = Hostel::where('institution_id', $institutionId)
            ->where('student_id', $request->student_id)
            ->first();
        
        if ($existing) {
            return redirect()->back()
                ->with('error', 'This student is already in boarding.')
                ->withInput();
        }

        // Verify student belongs to this institution
        $student = Student::where('id', $request->student_id)
            ->where('institution_id', $institutionId)
            ->firstOrFail();

        Hostel::create([
            'student_id' => $request->student_id,
            'institution_id' => $institutionId,
        ]);

        return redirect()->route('institution.boarding.index')
            ->with('success', 'Student added to boarding successfully!');
    }

    public function edit($id)
    {
        $institutionId = auth('institution')->id();
        
        $boarding = Hostel::where('id', $id)
            ->where('institution_id', $institutionId)
            ->with('student')
            ->firstOrFail();
        
        return view('institution.administration.boarding.edit', compact('boarding'));
    }

    public function update(Request $request, $id)
    {
        $institutionId = auth('institution')->id();
        
        $boarding = Hostel::where('id', $id)
            ->where('institution_id', $institutionId)
            ->firstOrFail();

        // If changing student, check if new student is already in boarding
        if ($request->filled('student_id') && $request->student_id != $boarding->student_id) {
            $existing = Hostel::where('institution_id', $institutionId)
                ->where('student_id', $request->student_id)
                ->where('id', '!=', $id)
                ->first();
            
            if ($existing) {
                return redirect()->back()
                    ->with('error', 'This student is already in boarding.')
                    ->withInput();
            }

            // Verify student belongs to this institution
            $student = Student::where('id', $request->student_id)
                ->where('institution_id', $institutionId)
                ->firstOrFail();
        }

        $boarding->update([
            'student_id' => $request->student_id ?? $boarding->student_id,
        ]);

        return redirect()->route('institution.boarding.index')
            ->with('success', 'Boarding information updated successfully!');
    }

    public function destroy($id)
    {
        $institutionId = auth('institution')->id();
        
        $boarding = Hostel::where('id', $id)
            ->where('institution_id', $institutionId)
            ->firstOrFail();
        
        $studentName = $boarding->student->first_name . ' ' . $boarding->student->last_name;
        $boarding->delete();

        return redirect()->route('institution.boarding.index')
            ->with('success', "Student {$studentName} removed from boarding successfully!");
    }

    public function getStudentsByClass($classId)
    {
        $institutionId = auth('institution')->id();
        
        // Get students who are not already in boarding
        $existingBoardingStudentIds = Hostel::where('institution_id', $institutionId)
            ->pluck('student_id')
            ->toArray();
        
        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->whereNotIn('id', $existingBoardingStudentIds)
            ->where('status', 1)
            ->with('section')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'admission_number', 'roll_number', 'section_id']);
        
        return response()->json($students);
    }
}

