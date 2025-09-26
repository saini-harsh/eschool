<?php

namespace App\Http\Controllers\Teacher\Routine;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentAssignment;
use App\Models\AssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    /**
     * Display assignment list page
     */
    public function index()
    {
        // Get the logged-in teacher
        $currentTeacher = Auth::guard('teacher')->user();
        $institutionId = $currentTeacher->institution_id;
        
        // Get assignments for the teacher's institution
        $assignments = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments'])
            ->where('institution_id', $institutionId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get classes for the teacher's institution
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name']);
        
        // Get subjects assigned to the logged-in teacher
        $assignedSubjects = AssignSubject::where('institution_id', $institutionId)
            ->where('teacher_id', $currentTeacher->id)
            ->where('status', 1)
            ->with(['subject' => function($query) {
                $query->where('status', 1)->select('id', 'name', 'code', 'class_id');
            }])
            ->get()
            ->pluck('subject')
            ->filter() // Remove null subjects
            ->unique('id') // Remove duplicates
            ->values(); // Reset array keys
        
        $subjects = $assignedSubjects;
        
        return view('teacher.routines.assignments.index', compact('assignments', 'classes', 'subjects', 'currentTeacher'));
    }

    /**
     * Store assignment data
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'due_date' => 'required|string|after:today',
                'assignment_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the logged-in teacher
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;
            $teacherId = $currentTeacher->id;

            // Validate that the subject is assigned to this teacher
            $assignedSubject = AssignSubject::where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacherId)
                ->where('status', 1)
                ->first();

            if (!$assignedSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach this subject for the selected class and section.'
                ], 422);
            }

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('assignment_file')) {
                $file = $request->file('assignment_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('teacher/uploads/assignment'), $fileName);
                $filePath = 'teacher/uploads/assignment/' . $fileName;
            }

            $assignment = Assignment::create([
                'title' => $request->title,
                'description' => $request->description,
                'institution_id' => $institutionId,
                'teacher_id' => $teacherId,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'due_date' => $request->due_date,
                'assignment_file' => $filePath,
                'status' => $request->boolean('status', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment created successfully',
                'data' => $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignment data for editing
     */
    public function edit($id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $assignment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update assignment data
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'due_date' => 'required|string',
                'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;
            $teacherId = $currentTeacher->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            // Validate that the subject is assigned to this teacher
            $assignedSubject = AssignSubject::where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacherId)
                ->where('status', 1)
                ->first();

            if (!$assignedSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach this subject for the selected class and section.'
                ], 422);
            }

            // Handle file upload if new file is provided
            if ($request->hasFile('assignment_file')) {
                // Delete old file
                if ($assignment->assignment_file && file_exists(public_path($assignment->assignment_file))) {
                    unlink(public_path($assignment->assignment_file));
                }

                $file = $request->file('assignment_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('teacher/uploads/assignment'), $fileName);
                $assignment->assignment_file = 'teacher/uploads/assignment/' . $fileName;
            }

            $assignment->update([
                'title' => $request->title,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'due_date' => $request->due_date,
                'status' => $request->boolean('status', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully',
                'data' => $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete assignment
     */
    public function destroy($id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            // Delete file if exists
            if ($assignment->assignment_file && file_exists(public_path($assignment->assignment_file))) {
                unlink(public_path($assignment->assignment_file));
            }

            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update assignment status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            $assignment->status = $request->status;
            $assignment->save();

            return response()->json([
                'success' => true,
                'message' => 'Assignment status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download assignment file
     */
    public function download($id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment || !$assignment->assignment_file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment file not found'
                ], 404);
            }

            if (!file_exists(public_path($assignment->assignment_file))) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            return response()->download(public_path($assignment->assignment_file));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading file: ' . $e->getMessage()
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
     * Get subjects by institution and class (only subjects assigned to the logged-in teacher)
     */
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        try {
            // Get the logged-in teacher
            $currentTeacher = Auth::guard('teacher')->user();
            $teacherId = $currentTeacher->id;
            $teacherInstitutionId = $currentTeacher->institution_id;
            
            // Only allow access to teacher's own institution
            if ($institutionId != $teacherInstitutionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to institution data'
                ], 403);
            }

            // Get subjects that are assigned to this teacher for the specified class
            $assignedSubjects = AssignSubject::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('teacher_id', $teacherId)
                ->where('status', 1)
                ->with(['subject' => function($query) {
                    $query->where('status', 1)->select('id', 'name', 'code');
                }])
                ->get()
                ->pluck('subject')
                ->filter() // Remove null subjects
                ->unique('id') // Remove duplicates
                ->values(); // Reset array keys

            return response()->json([
                'success' => true,
                'data' => $assignedSubjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View student submissions for an assignment
     */
    public function viewSubmissions($id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            $submissions = StudentAssignment::with(['student'])
                ->where('assignment_id', $id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'assignment' => $assignment,
                    'submissions' => $submissions
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grade student assignment
     */
    public function gradeAssignment(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_assignment_id' => 'required|exists:student_assignments,id',
                'marks' => 'required|numeric|min:0|max:100',
                'feedback' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                ], 404);
            }

            $studentAssignment = StudentAssignment::where('id', $request->student_assignment_id)
                ->where('assignment_id', $id)
                ->first();

            if (!$studentAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student submission not found'
                ], 404);
            }

            $studentAssignment->update([
                'marks' => $request->marks,
                'feedback' => $request->feedback,
                'status' => 'graded'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment graded successfully',
                'data' => $studentAssignment->load('student')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error grading assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download student submission file
     */
    public function downloadStudentSubmission($id)
    {
        try {
            $currentTeacher = Auth::guard('teacher')->user();
            $institutionId = $currentTeacher->institution_id;

            $studentAssignment = StudentAssignment::with(['assignment'])
                ->where('id', $id)
                ->whereHas('assignment', function($query) use ($institutionId) {
                    $query->where('institution_id', $institutionId);
                })
                ->first();

            if (!$studentAssignment || !$studentAssignment->submitted_file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            if (!file_exists(public_path($studentAssignment->submitted_file))) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            return response()->download(public_path($studentAssignment->submitted_file));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading file: ' . $e->getMessage()
            ], 500);
        }
    }
}
