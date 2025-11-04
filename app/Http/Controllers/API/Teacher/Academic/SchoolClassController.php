<?php

namespace App\Http\Controllers\API\Teacher\Academic;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\AssignClassTeacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class SchoolClassController extends Controller
{
    public function Classes(Request $request)
    {
        $teacher = Teacher::where('email', $request->email)->first();
    
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'No teacher found with this email',
                'data' => []
            ], 404);
        }
    
        // Get all class assignments for this teacher
        $classAssignments = AssignClassTeacher::where('teacher_id', $teacher->id)
            ->where('status', 1)
            ->with(['class:id,name,status,institution_id', 'section:id,name,class_id'])
            ->get();
    
        // Group by class and get unique classes with their sections
        $classes = $classAssignments->groupBy('class_id')->map(function ($assignments, $classId) use ($teacher) {
    
            $class = $assignments->first()->class;
    
            // Get unique sections for this class
            $sections = $assignments->pluck('section')->unique('id')->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                ];
            })->values();
    
            // Count students in all assigned sections for this class
            $studentCount = Student::where('class_id', $classId)
                ->whereIn('section_id', $sections->pluck('id'))
                ->where('institution_id', $teacher->institution_id)
                ->count();
    
            return [
                'id' => $class->id,
                'name' => $class->name,
                'sections' => $sections,
                'student_count' => $studentCount,
                'status' => $class->status,
                'institution_id' => $class->institution_id
            ];
        })->values();
    
        return response()->json([
            'success' => true,
            'message' => 'Classes fetched successfully',
            'data' => $classes
        ], 200);
    }
    

}
