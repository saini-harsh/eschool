<?php

namespace App\Http\Controllers\Institution\Academic;
use App\Models\Teacher;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignClassTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        $currentInstitution = auth('institution')->user();
        
        $institutions = collect([$currentInstitution]); // Only show current institution
        $teachers = Teacher::where('institution_id', $currentInstitution->id)->get();
        $classes = SchoolClass::where('institution_id', $currentInstitution->id)->get();
        $lists = AssignClassTeacher::with(['institution', 'class', 'section', 'teacher'])
            ->where('institution_id', $currentInstitution->id)
            ->get();
        return view('institution.academic.assign-teacher', compact('teachers', 'classes', 'institutions','lists'));
    }

    // Fetch classes by institution
    public function getClassesByInstitution($institutionId)
    {
        $currentInstitution = auth('institution')->user();
        
        // Ensure the requested institution matches the current institution
        if ($institutionId != $currentInstitution->id) {
            return response()->json(['classes' => []]);
        }
        
        $classes = SchoolClass::where('institution_id', $institutionId)->get(['id', 'name']);
        return response()->json(['classes' => $classes]);
    }

    // Fetch teachers by institution
    public function getTeachersByInstitution($institutionId)
    {
        $currentInstitution = auth('institution')->user();
        
        // Ensure the requested institution matches the current institution
        if ($institutionId != $currentInstitution->id) {
            return response()->json(['teachers' => []]);
        }
        
        $teachers = Teacher::where('institution_id', $institutionId)->get(['id', 'first_name', 'last_name']);
        return response()->json(['teachers' => $teachers]);
    }

    // Fetch sections by class
    public function getSectionsByClass($classId)
    {
        $currentInstitution = auth('institution')->user();
        
        $class = SchoolClass::where('id', $classId)
            ->where('institution_id', $currentInstitution->id)
            ->first();
            
        if (!$class) {
            return response()->json(['sections' => []]);
        }
        
        $sectionIds = $class->section_ids ?? [];
        
        // If section_ids is a string (JSON), decode it
        if (is_string($sectionIds)) {
            $sectionIds = json_decode($sectionIds, true) ?? [];
        }
        
        // Ensure it's an array and not empty
        if (!is_array($sectionIds) || empty($sectionIds)) {
            return response()->json(['sections' => []]);
        }
        
        $sections = Section::whereIn('id', $sectionIds)->get(['id', 'name']);
        return response()->json(['sections' => $sections]);
    }

    public function store(Request $request)
    {
        $currentInstitution = auth('institution')->user();
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'nullable|boolean',
        ]);

        // Validate that class, teacher belong to current institution
        $class = SchoolClass::where('id', $request->class_id)
            ->where('institution_id', $currentInstitution->id)
            ->first();
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class does not belong to your institution'], 422);
        }

        $teacher = Teacher::where('id', $request->teacher_id)
            ->where('institution_id', $currentInstitution->id)
            ->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher does not belong to your institution'], 422);
        }

        // Check if assignment already exists
        $existingAssignment = AssignClassTeacher::where('institution_id', $currentInstitution->id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('teacher_id', $request->teacher_id)
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'This teacher assignment already exists for the selected class and section.'
            ], 422);
        }

        $assignClassTeacher = AssignClassTeacher::create([
            'institution_id' => $currentInstitution->id, // Force current institution
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
            'status' => $request->status ? 1 : 0,
        ]);

        // Load relationships for response
        $assignClassTeacher->load(['institution', 'class', 'section', 'teacher']);

        return response()->json([
            'success' => true,
            'message' => 'Class teacher assigned successfully!',
            'data' => $assignClassTeacher
        ]);
    }

    public function edit($id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $assignClassTeacher = AssignClassTeacher::with(['institution', 'class', 'section', 'teacher'])
                ->where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $assignClassTeacher
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $request->validate([
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'teacher_id' => 'required|exists:teachers,id',
                'status' => 'nullable|boolean',
            ]);

            $assignClassTeacher = AssignClassTeacher::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);

            // Validate that class, teacher belong to current institution
            $class = SchoolClass::where('id', $request->class_id)
                ->where('institution_id', $currentInstitution->id)
                ->first();
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class does not belong to your institution'], 422);
            }

            $teacher = Teacher::where('id', $request->teacher_id)
                ->where('institution_id', $currentInstitution->id)
                ->first();
            if (!$teacher) {
                return response()->json(['success' => false, 'message' => 'Teacher does not belong to your institution'], 422);
            }

            // Check if assignment already exists (excluding current record)
            $existingAssignment = AssignClassTeacher::where('institution_id', $currentInstitution->id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('teacher_id', $request->teacher_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This teacher assignment already exists for the selected class and section.'
                ], 422);
            }

            $assignClassTeacher->update([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'teacher_id' => $request->teacher_id,
                'status' => $request->status ? 1 : 0,
            ]);

            // Load relationships for response
            $assignClassTeacher->load(['institution', 'class', 'section', 'teacher']);

            return response()->json([
                'success' => true,
                'message' => 'Class teacher assignment updated successfully!',
                'data' => $assignClassTeacher
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $assignClassTeacher = AssignClassTeacher::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            $assignClassTeacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Class teacher assignment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assignment'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $assignClassTeacher = AssignClassTeacher::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            $assignClassTeacher->update(['status' => $request->status ? 1 : 0]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

    public function getAssignments()
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $assignments = AssignClassTeacher::with(['institution', 'class', 'section', 'teacher'])
                ->where('institution_id', $currentInstitution->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'data' => $assignments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assignments'
            ], 500);
        }
    }
}
