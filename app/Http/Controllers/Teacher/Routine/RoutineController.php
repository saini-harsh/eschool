<?php

namespace App\Http\Controllers\Teacher\Routine;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    /**
     * Display routine list page
     */
    public function index()
    {
        // Get the logged-in teacher's institution
        $currentTeacher = Auth::guard('teacher')->user();
        $institutionId = $currentTeacher->institution_id;
        
        // Filter routines by the teacher's institution
        $routines = Routine::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'classRoom'])
            ->where('institution_id', $institutionId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get classes for the teacher's institution
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name']);
        
        return view('teacher.routines.class-routine.index', compact('routines', 'classes'));
    }



    /**
     * Get sections by class
     */
    public function getSectionsByClass($classId)
    {
        try {
            $class = SchoolClass::find($classId);
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }

            // Get sections by class_id and institution_id
            $sections = Section::where('class_id', $classId)
                ->where('institution_id', $class->institution_id)
                ->where('status', 1)
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get routine report data
     */
    public function getRoutineReport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the logged-in teacher's institution
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $routines = Routine::with(['subject', 'teacher', 'classRoom'])
                ->where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
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
                'message' => 'Error fetching routine report: ' . $e->getMessage()
            ], 500);
        }
    }


}
