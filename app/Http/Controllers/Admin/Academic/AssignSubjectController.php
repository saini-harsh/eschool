<?php

namespace App\Http\Controllers\Admin\Academic;

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
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $institutions = Institution::where('status', 1)->get();
        $teachers = Teacher::where('status', 1)->get();
        $classes = SchoolClass::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        
        // Apply filters if any
        $query = AssignSubject::with(['institution', 'schoolClass', 'section', 'subject', 'teacher']);
        
        if ($request->filled('institution_ids')) {
            $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
            $query->whereIn('institution_id', $institutionIds);
        }
        
        if ($request->filled('class_ids')) {
            $classIds = is_array($request->class_ids) ? $request->class_ids : [$request->class_ids];
            $query->whereIn('class_id', $classIds);
        }
        
        if ($request->filled('teacher_ids')) {
            $teacherIds = is_array($request->teacher_ids) ? $request->teacher_ids : [$request->teacher_ids];
            $query->whereIn('teacher_id', $teacherIds);
        }
        
        if ($request->filled('subject_ids')) {
            $subjectIds = is_array($request->subject_ids) ? $request->subject_ids : [$request->subject_ids];
            $query->whereIn('subject_id', $subjectIds);
        }
        
        if ($request->filled('status')) {
            $status = $request->status;
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }
        
        $lists = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.academic.assign-subject', compact('teachers', 'classes', 'institutions', 'subjects', 'lists'));
    }

    // Fetch classes by institution
    public function getClassesByInstitution($institutionId)
    {
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name']);
        return response()->json(['classes' => $classes]);
    }

    // Fetch teachers by institution
    public function getTeachersByInstitution($institutionId)
    {
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'first_name', 'middle_name', 'last_name']);
        return response()->json(['teachers' => $teachers]);
    }

    // Fetch subjects by institution and class
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
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

    /**
     * Filter assignments via AJAX
     */
    public function filter(Request $request)
    {
        try {
            // Log the request for debugging
            \Log::info('Admin assign subject filter request received:', $request->all());
            
            // Build query with filters
            $query = AssignSubject::with(['institution', 'schoolClass', 'section', 'subject', 'teacher']);
            
            if ($request->filled('institution_ids')) {
                $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
                $query->whereIn('institution_id', $institutionIds);
            }
            
            if ($request->filled('class_ids')) {
                $classIds = is_array($request->class_ids) ? $request->class_ids : [$request->class_ids];
                $query->whereIn('class_id', $classIds);
            }
            
            if ($request->filled('teacher_ids')) {
                $teacherIds = is_array($request->teacher_ids) ? $request->teacher_ids : [$request->teacher_ids];
                $query->whereIn('teacher_id', $teacherIds);
            }
            
            if ($request->filled('subject_ids')) {
                $subjectIds = is_array($request->subject_ids) ? $request->subject_ids : [$request->subject_ids];
                $query->whereIn('subject_id', $subjectIds);
            }
            
            if ($request->filled('status')) {
                $status = $request->status;
                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', $status);
                }
            }
            
            // Get filtered results
            $assignments = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $assignments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering assignments'
            ], 500);
        }
    }
}