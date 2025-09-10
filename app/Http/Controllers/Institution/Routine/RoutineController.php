<?php

namespace App\Http\Controllers\Institution\Routine;

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
        $this->middleware('auth:institution');
    }

    /**
     * Display routine list page
     */
    public function index()
    {
        // Get the current authenticated institution
        $currentInstitution = Auth::guard('institution')->user();
        
        $routines = Routine::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'classRoom'])
            ->where('institution_id', $currentInstitution->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('institution.routines.class-routine.index', compact('routines'));
    }

    /**
     * Display add routine page
     */
    public function create()
    {
        // Get the current authenticated institution
        $currentInstitution = Auth::guard('institution')->user();
        
        // Only show current institution
        $institutions = collect([$currentInstitution]);
        $classes = collect();
        $sections = collect();
        $subjects = collect();
        $teachers = collect();
        $classRooms = ClassRoom::where('status', 1)->get();

        return view('institution.routines.class-routine.create', compact('institutions', 'classes', 'sections', 'subjects', 'teachers', 'classRooms'));
    }

    /**
     * Store routine data
     */
    public function store(Request $request)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'class_room_id' => 'nullable|exists:class_rooms,id',
                'day' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_break' => 'boolean',
                'is_other_day' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check for conflicts before creating routine
            $conflicts = $this->checkRoutineConflicts($request);
            if (!empty($conflicts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Routine conflicts detected',
                    'errors' => $conflicts
                ], 422);
            }

            $routine = Routine::create([
                'institution_id' => $currentInstitution->id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'class_room_id' => $request->class_room_id,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_break' => $request->boolean('is_break'),
                'is_other_day' => $request->boolean('is_other_day'),
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Routine created successfully',
                'data' => $routine
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating routine: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes by institution
     */
    public function getClassesByInstitution($institutionId)
    {
        try {
            $classes = SchoolClass::where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching classes: ' . $e->getMessage()
            ], 500);
        }
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

            $sectionIds = $class->section_ids ?? [];
            
            // If no sections are assigned to this class, return empty array
            if (empty($sectionIds) || !is_array($sectionIds)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            $sections = Section::whereIn('id', $sectionIds)
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
     * Get subjects by institution and class
     */
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        try {
            $subjects = Subject::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('status', 1)
                ->get(['id', 'name', 'code']);

            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teachers by institution
     */
    public function getTeachersByInstitution($institutionId)
    {
        try {
            $teachers = Teacher::where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'first_name', 'last_name']);

            return response()->json([
                'success' => true,
                'data' => $teachers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching teachers: ' . $e->getMessage()
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

            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $routines = Routine::with(['subject', 'teacher', 'classRoom'])
                ->where('institution_id', $currentInstitution->id)
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

    /**
     * Update routine status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $routine = Routine::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            $routine->status = $request->status;
            $routine->save();

            return response()->json([
                'success' => true,
                'message' => 'Routine status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating routine status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete routine
     */
    public function destroy($id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $routine = Routine::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            $routine->delete();

            return response()->json([
                'success' => true,
                'message' => 'Routine deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting routine: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for routine conflicts
     */
    private function checkRoutineConflicts(Request $request)
    {
        $conflicts = [];
        
        // Get the current authenticated institution
        $currentInstitution = Auth::guard('institution')->user();

        // Check if same class/section already has a routine at this time
        $existingRoutine = Routine::where('institution_id', $currentInstitution->id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->where('status', 1)
            ->first();

        if ($existingRoutine) {
            $conflicts[] = 'A routine already exists for this class and section at this time.';
        }

        // Check if same teacher already has a routine at this time
        $teacherConflict = Routine::where('institution_id', $currentInstitution->id)
            ->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->where('status', 1)
            ->first();

        if ($teacherConflict) {
            $conflicts[] = 'This teacher already has a routine at this time.';
        }

        // Check if same classroom is already occupied at this time
        if ($request->class_room_id) {
            $roomConflict = Routine::where('institution_id', $currentInstitution->id)
                ->where('class_room_id', $request->class_room_id)
                ->where('day', $request->day)
                ->where('start_time', $request->start_time)
                ->where('status', 1)
                ->first();

            if ($roomConflict) {
                $conflicts[] = 'This classroom is already occupied at this time.';
            }
        }

        return $conflicts;
    }
}
