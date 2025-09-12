<?php

namespace App\Http\Controllers\Teacher\Academic;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class SchoolClassController extends Controller
{

    // Removed duplicate middleware since it's already applied in the route group

    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Get all class assignments for this teacher
        $classAssignments = AssignClassTeacher::where('teacher_id', $teacher->id)
            ->where('status', 1)
            ->with(['class', 'section'])
            ->get();

        // Group by class and get unique classes with their sections
        $classes = collect();
        
        foreach ($classAssignments as $assignment) {
            $classId = $assignment->class_id;
            
            // Check if we already have this class
            if (!$classes->contains('id', $classId)) {
                $class = $assignment->class;
                
                // Get all sections for this class that are assigned to this teacher
                $assignedSections = $classAssignments
                    ->where('class_id', $classId)
                    ->pluck('section')
                    ->unique('id')
                    ->values();
                
                // Count students in all assigned sections for this class
                $studentCount = Student::where('class_id', $classId)
                    ->whereIn('section_id', $assignedSections->pluck('id'))
                    ->where('institution_id', $teacher->institution_id)
                    ->count();
                
                $classes->push([
                    'id' => $class->id,
                    'name' => $class->name,
                    'sections' => $assignedSections,
                    'student_count' => $studentCount,
                    'status' => $class->status,
                    'institution_id' => $class->institution_id
                ]);
            }
        }

        return view('teacher.academic.classes.index', compact('classes', 'teacher'));
    }

}
