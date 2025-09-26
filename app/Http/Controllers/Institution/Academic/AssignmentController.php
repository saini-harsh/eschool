<?php

namespace App\Http\Controllers\Institution\Academic;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentAssignment;
use App\Models\AssignSubject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    /**
     * Display assignment list page
     */
    public function index()
    {
        // Get the logged-in institution
        $currentInstitution = Auth::guard('institution')->user();
        $institutionId = $currentInstitution->id;
        
        // Get assignments for the institution
        $assignments = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments'])
            ->where('institution_id', $institutionId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get classes for the institution
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name']);
        
        // Get all subjects for the institution
        $subjects = Subject::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name', 'code', 'class_id']);
        
        // Get teachers for the institution
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'first_name', 'last_name']);
        
        return view('institution.academic.assignments.index', compact('assignments', 'classes', 'subjects', 'teachers', 'currentInstitution'));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'due_date' => 'required|string|after:today',
                'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that class, section, subject, and teacher belong to current institution
            $class = SchoolClass::where('id', $request->class_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class does not belong to your institution'], 422);
            }

            $section = Section::where('id', $request->section_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$section) {
                return response()->json(['success' => false, 'message' => 'Section does not belong to your institution'], 422);
            }

            $subject = Subject::where('id', $request->subject_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject does not belong to your institution'], 422);
            }

            $teacher = Teacher::where('id', $request->teacher_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$teacher) {
                return response()->json(['success' => false, 'message' => 'Teacher does not belong to your institution'], 422);
            }

            // Validate that the subject is assigned to the teacher
            $assignedSubject = AssignSubject::where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $request->teacher_id)
                ->where('status', 1)
                ->first();

            if (!$assignedSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected teacher is not assigned to teach this subject for the selected class and section.'
                ], 422);
            }

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
                'institution_id' => $institutionId, // Force current institution
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'due_date' => $request->due_date,
                'assignment_file' => $filePath,
                'status' => $request->status ? 1 : 0,
            ]);

            // Load relationships for response
            $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher']);

            return response()->json([
                'success' => true,
                'message' => 'Assignment created successfully',
                'data' => $assignment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified assignment
     */
    public function edit($id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

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
     * Update the specified assignment
     */
    public function update(Request $request, $id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found or you do not have permission to edit it'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'due_date' => 'required|string|after:today',
                'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that class, section, subject, and teacher belong to current institution
            $class = SchoolClass::where('id', $request->class_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class does not belong to your institution'], 422);
            }

            $section = Section::where('id', $request->section_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$section) {
                return response()->json(['success' => false, 'message' => 'Section does not belong to your institution'], 422);
            }

            $subject = Subject::where('id', $request->subject_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject does not belong to your institution'], 422);
            }

            $teacher = Teacher::where('id', $request->teacher_id)
                ->where('institution_id', $institutionId)
                ->first();
            if (!$teacher) {
                return response()->json(['success' => false, 'message' => 'Teacher does not belong to your institution'], 422);
            }

            // Validate that the subject is assigned to the teacher
            $assignedSubject = AssignSubject::where('institution_id', $institutionId)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $request->teacher_id)
                ->where('status', 1)
                ->first();

            if (!$assignedSubject) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected teacher is not assigned to teach this subject for the selected class and section.'
                ], 422);
            }

            $filePath = $assignment->assignment_file;
            if ($request->hasFile('assignment_file')) {
                // Delete old file if exists
                if ($assignment->assignment_file && file_exists(public_path($assignment->assignment_file))) {
                    unlink(public_path($assignment->assignment_file));
                }

                $file = $request->file('assignment_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('teacher/uploads/assignment'), $fileName);
                $filePath = 'teacher/uploads/assignment/' . $fileName;
            }

            $assignment->update([
                'title' => $request->title,
                'description' => $request->description,
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'due_date' => $request->due_date,
                'assignment_file' => $filePath,
                'status' => $request->status ? 1 : 0,
            ]);

            // Load relationships for response
            $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher']);

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully',
                'data' => $assignment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified assignment
     */
    public function destroy($id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found or you do not have permission to delete it'
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
     * Update assignment status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found or you do not have permission to update it'
                ], 404);
            }

            $assignment->update([
                'status' => $request->status ? 1 : 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment status updated successfully',
                'data' => $assignment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the assignment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View student submissions for an assignment
     */
    public function viewSubmissions($id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found or you do not have permission to view it'
                ], 404);
            }

            $submissions = StudentAssignment::with(['student', 'assignment'])
                ->where('assignment_id', $id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'assignment' => $assignment,
                    'submissions' => $submissions
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching submissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grade student assignment
     */
    public function gradeAssignment(Request $request, $id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

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

            $studentAssignment = StudentAssignment::where('id', $request->student_assignment_id)
                ->whereHas('assignment', function($query) use ($institutionId) {
                    $query->where('institution_id', $institutionId);
                })
                ->first();

            if (!$studentAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student assignment not found or you do not have permission to grade it'
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
                'data' => $studentAssignment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while grading the assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download student submission file
     */
    public function downloadStudentSubmission($id)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            $institutionId = $currentInstitution->id;

            $submission = StudentAssignment::where('id', $id)
                ->whereHas('assignment', function($query) use ($institutionId) {
                    $query->where('institution_id', $institutionId);
                })
                ->first();

            if (!$submission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission not found or you do not have permission to download it'
                ], 404);
            }

            if (!$submission->submitted_file || !file_exists(public_path($submission->submitted_file))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submitted file not found'
                ], 404);
            }

            return response()->download(public_path($submission->submitted_file));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while downloading the file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // AJAX methods for dynamic dropdowns
    public function getClassesByInstitution($institutionId)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            
            // Only allow access to current institution
            if ($institutionId != $currentInstitution->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to institution data'
                ], 403);
            }

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

    public function getSectionsByClass($classId)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            
            $sections = Section::where('institution_id', $currentInstitution->id)
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

    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            
            // Only allow access to current institution
            if ($institutionId != $currentInstitution->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to institution data'
                ], 403);
            }

            $subjects = Subject::where('institution_id', $institutionId)
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

    public function getTeachersByInstitution($institutionId)
    {
        try {
            $currentInstitution = Auth::guard('institution')->user();
            
            // Only allow access to current institution
            if ($institutionId != $currentInstitution->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to institution data'
                ], 403);
            }

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
}
