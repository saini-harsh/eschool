<?php

namespace App\Http\Controllers\Institution\Academic;

use App\Models\Teacher;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AssignSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AssignSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        $currentInstitution = auth('institution')->user();
        
        $institutions = collect([$currentInstitution]); // Only show current institution
        $teachers = Teacher::where('institution_id', $currentInstitution->id)->where('status', 1)->get();
        $classes = SchoolClass::where('institution_id', $currentInstitution->id)->where('status', 1)->get();
        $subjects = Subject::where('institution_id', $currentInstitution->id)->where('status', 1)->get();
        $lists = AssignSubject::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ->where('institution_id', $currentInstitution->id)
            ->get();
        
        return view('institution.academic.assign-subject', compact('teachers', 'classes', 'institutions', 'subjects', 'lists'));
    }

    // Fetch classes by institution
    public function getClassesByInstitution($institutionId)
    {
        $currentInstitution = auth('institution')->user();
        
        // Ensure the requested institution matches the current institution
        if ($institutionId != $currentInstitution->id) {
            return response()->json(['classes' => []]);
        }
        
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name']);
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
        
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'first_name', 'middle_name', 'last_name']);
        return response()->json(['teachers' => $teachers]);
    }

    // Fetch subjects by institution and class
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        $currentInstitution = auth('institution')->user();
        
        // Ensure the requested institution matches the current institution
        if ($institutionId != $currentInstitution->id) {
            return response()->json(['subjects' => []]);
        }
        
        $subjects = Subject::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('status', 1)
            ->get(['id', 'name', 'code']);
        return response()->json(['subjects' => $subjects]);
    }

    // Fetch sections by class
    public function getSectionsByClass($classId)
    {
        $class = SchoolClass::find($classId);
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
        try {
            $validator = Validator::make($request->all(), [
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if assignment already exists
            $existingAssignment = AssignSubject::where('institution_id', $request->institution_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $request->teacher_id)
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This subject assignment already exists for the selected teacher, class, and section.'
                ], 422);
            }

            $assignSubject = AssignSubject::create([
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'status' => $request->status ? 1 : 0,
            ]);

            // Load relationships for response
            $assignSubject->load(['institution', 'schoolClass', 'section', 'subject', 'teacher']);

            return response()->json([
                'success' => true,
                'message' => 'Subject assigned successfully',
                'data' => $assignSubject
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while assigning the subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $assignSubject = AssignSubject::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $assignSubject
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
            $validator = Validator::make($request->all(), [
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assignSubject = AssignSubject::findOrFail($id);

            // Check if assignment already exists (excluding current record)
            $existingAssignment = AssignSubject::where('institution_id', $request->institution_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $request->teacher_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This subject assignment already exists for the selected teacher, class, and section.'
                ], 422);
            }

            $assignSubject->update([
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'status' => $request->status ? 1 : 0,
            ]);

            // Load relationships for response
            $assignSubject->load(['institution', 'schoolClass', 'section', 'subject', 'teacher']);

            return response()->json([
                'success' => true,
                'message' => 'Subject assignment updated successfully',
                'data' => $assignSubject
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $assignSubject = AssignSubject::findOrFail($id);
            $assignSubject->status = (int)$request->status;
            $assignSubject->save();

            return response()->json([
                'success' => true,
                'message' => 'Assignment status updated successfully',
                'data' => [
                    'id' => $assignSubject->id,
                    'status' => $assignSubject->status,
                    'status_text' => $assignSubject->status ? 'Active' : 'Inactive'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the assignment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $assignSubject = AssignSubject::findOrFail($id);
            $assignSubject->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subject assignment deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignments list for AJAX
     */
    public function getAssignments()
    {
        try {
            $assignments = AssignSubject::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
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