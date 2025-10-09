<?php

namespace App\Http\Controllers\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display routine page for student
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get student's class and section
        $classId = (int) $student->class_id; // Convert string to integer
        $sectionId = (int) $student->section_id; // Convert string to integer
        $institutionId = $student->institution_id;
        
        // Get routines for student's class and section - load all data
        $routines = Routine::with([
                'subject:id,name,code',
                'teacher:id,first_name,last_name',
                'classRoom:id,room_no,room_name'
            ])
            ->where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 1)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Group by day
        $groupedRoutines = $routines->groupBy('day');

        return view('student.academic.routine.index', compact('routines', 'groupedRoutines', 'student'));
    }

    /**
     * Get routine data via API
     */
    public function getRoutineData()
    {
        try {
            $student = Auth::guard('student')->user();
            
            $classId = (int) $student->class_id; // Convert string to integer
            $sectionId = (int) $student->section_id; // Convert string to integer
            $institutionId = $student->institution_id;
            
            // Get routines for student's class and section - load all data
            $routines = Routine::with([
                    'subject:id,name,code',
                    'teacher:id,first_name,last_name',
                    'classRoom:id,room_no,room_name'
                ])
                ->where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', 1)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            // Group by day
            $groupedRoutines = $routines->groupBy('day');

            return response()->json([
                'success' => true,
                'data' => $groupedRoutines
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching routine data: ' . $e->getMessage()
            ], 500);
        }
    }
}
