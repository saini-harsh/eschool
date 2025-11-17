<?php

namespace App\Http\Controllers\Teacher\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function Index(Request $request){
        // Get the current logged-in teacher's institution
        $currentUser = auth('teacher')->user();
        $institutionId = $currentUser->institution_id;
        
        // Get classes for the current institution
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name', 'institution_id', 'section_ids']);
        
        $sections = collect();
        $students = collect();
        
        // If class and section are selected, get students
        if ($request->filled('class_id') && $request->filled('section_id')) {
            $students = Student::where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->with(['institution', 'schoolClass', 'section'])
                ->get();
                
            // Get sections for the selected class
            $selectedClass = SchoolClass::find($request->class_id);
            if ($selectedClass) {
                $sectionIds = $selectedClass->section_ids ?? [];
                
                // If section_ids is a string (JSON), decode it
                if (is_string($sectionIds)) {
                    $sectionIds = json_decode($sectionIds, true) ?? [];
                }
                
                if (is_array($sectionIds) && !empty($sectionIds)) {
                    $sections = Section::whereIn('id', $sectionIds)
                        ->where('institution_id', $institutionId)
                        ->where('status', 1)
                        ->get(['id', 'name'])
                        ->unique('name')
                        ->values();
                }
            }
        }
        
        return view('teacher.administration.students.index', compact('students', 'classes', 'sections'));
    }

    // Method to get sections by class ID
    public function getSectionsByClass($classId)
    {
        try {
            $currentUser = auth('teacher')->user();
            $institutionId = $currentUser->institution_id;

            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$class) {
                return response()->json(['sections' => []]);
            }

            // Ensure section_ids is properly handled as an array
            $sectionIds = $class->section_ids ?? [];
            
            // If section_ids is a string (JSON), decode it
            if (is_string($sectionIds)) {
                $sectionIds = json_decode($sectionIds, true) ?? [];
            }
            
            // Ensure it's an array and not empty
            if (!is_array($sectionIds) || empty($sectionIds)) {
                return response()->json(['sections' => []]);
            }
            
            $sections = Section::whereIn('id', $sectionIds)
                ->where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'name'])
                ->unique('name')
                ->values();
            
            return response()->json(['sections' => $sections]);
        } catch (\Exception $e) {
            \Log::error('Error in getSectionsByClass: ' . $e->getMessage());
            return response()->json(['sections' => [], 'error' => 'An error occurred while fetching sections']);
        }
    }

    // Method to get classes by institution ID
    public function getClassesByInstitution($institutionId)
    {
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name', 'institution_id', 'section_ids']);
        
        return response()->json(['classes' => $classes]);
    }

    // Method to get teachers by institution ID
    public function getTeachersByInstitution($institutionId)
    {
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'first_name', 'last_name', 'institution_id']);
        
        return response()->json(['teachers' => $teachers]);
    }

    public function Show(Student $student)
    {
        // Get the current logged-in teacher's institution
        $currentUser = auth('teacher')->user();
        $institutionId = $currentUser->institution_id;
        
        // Ensure the student belongs to the current user's institution
        if ($student->institution_id !== $institutionId) {
            abort(403, 'Unauthorized access to student data.');
        }
        
        return view('teacher.administration.students.show', compact('student'));
    }

    // AJAX method to get students by class and section
    public function getStudentsByClassSection(Request $request)
    {
        // Get the current logged-in teacher's institution
        $currentUser = auth('teacher')->user();
        $institutionId = $currentUser->institution_id;
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id'
        ]);
        
        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->with(['institution', 'schoolClass', 'section'])
            ->get();
            
        return response()->json(['students' => $students]);
    }

    public function downloadPdf(Student $student)
    {
        $currentUser = auth('teacher')->user();
        if ($student->institution_id !== $currentUser->institution_id) {
            abort(403);
        }

        $student->load([
            'institution:id,name',
            'teacher:id,first_name,last_name',
            'schoolClass:id,name',
            'section:id,name'
        ]);

        $primaryColor = '#6366f1';
        $secondaryColor = '#0d6efd';

        $pdf = Pdf::loadView('admin.administration.students.pdf', [
            'student' => $student,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
        ])->setPaper('a4');

        $fileName = 'Student_' . ($student->student_id ?? $student->id) . '.pdf';
        return $pdf->download($fileName);
    }



}
