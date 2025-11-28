<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignment;
use App\Models\AssignSubject;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of assignments.
     */
    public function index(Request $request)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Get all institutions for dropdown
        $institutions = Institution::where('status', 1)->get();
        
        // Get all classes for dropdown
        $classes = SchoolClass::where('status', 1)->get();
        
        // Get all sections for dropdown
        $sections = Section::where('status', 1)->get();
        
        // Get all subjects for dropdown
        $subjects = Subject::where('status', 1)->get();
        
        // Get all teachers for dropdown
        $teachers = Teacher::where('status', 1)->get();
        
        $query = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments']);

        if ($request->filled('institution_ids')) {
            $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
            $query->whereIn('institution_id', $institutionIds);
        }

        $assignments = $query->orderBy('created_at', 'desc')->get();

        return view('admin.academic.assignments.index', compact(
            'assignments', 
            'institutions', 
            'classes', 
            'sections', 
            'subjects', 
            'teachers',
            'currentAdmin'
        ));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'due_date' => 'required|date|after:today',
                'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assignmentData = [
                'title' => $request->title,
                'description' => $request->description,
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'due_date' => $request->due_date,
                'status' => $request->status ? 1 : 0,
            ];

            // Handle file upload
            if ($request->hasFile('assignment_file')) {
                $file = $request->file('assignment_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/uploads/assignments'), $fileName);
                $assignmentData['assignment_file'] = 'admin/uploads/assignments/' . $fileName;
            }

            $assignment = Assignment::create($assignmentData);

            // Load relationships for response
            $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments']);

            return response()->json([
                'success' => true,
                'message' => 'Assignment created successfully!',
                'data' => $assignment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get assignment data for editing.
     */
    public function edit($id)
    {
        try {
            $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->where('id', $id)
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
     * Update the specified assignment.
     */
    public function update(Request $request, $id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'teacher_id' => 'required|exists:teachers,id',
                'due_date' => 'required|date',
                'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'status' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $assignmentData = [
                'title' => $request->title,
                'description' => $request->description,
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'due_date' => $request->due_date,
                'status' => $request->status ? 1 : 0,
            ];

            // Handle file upload
            if ($request->hasFile('assignment_file')) {
                // Delete old file if exists
                if ($assignment->assignment_file && file_exists(public_path($assignment->assignment_file))) {
                    unlink(public_path($assignment->assignment_file));
                }

                $file = $request->file('assignment_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/uploads/assignments'), $fileName);
                $assignmentData['assignment_file'] = 'admin/uploads/assignments/' . $fileName;
            }

            $assignment->update($assignmentData);

            // Load relationships for response
            $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments']);

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully!',
                'data' => $assignment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy($id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            // Delete associated file if exists
            if ($assignment->assignment_file && file_exists(public_path($assignment->assignment_file))) {
                unlink(public_path($assignment->assignment_file));
            }

            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignment deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update assignment status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $assignment = Assignment::findOrFail($id);
            $assignment->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment status updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View student submissions for an assignment.
     */
    public function viewSubmissions($id)
    {
        try {
            $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->findOrFail($id);

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
     * Grade student assignment submission.
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

            $studentAssignment = StudentAssignment::findOrFail($request->student_assignment_id);
            $studentAssignment->update([
                'marks' => $request->marks,
                'feedback' => $request->feedback,
                'status' => 'graded'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment graded successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error grading assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download student submission file.
     */
    public function downloadStudentSubmission($id)
    {
        try {
            $submission = StudentAssignment::findOrFail($id);

            if (!$submission->submitted_file || !file_exists(public_path($submission->submitted_file))) {
                return redirect()->back()->with('error', 'Submitted file not found.');
            }

            return response()->download(public_path($submission->submitted_file));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }

    /**
     * Download assignment file.
     */
    public function downloadAssignment($id)
    {
        try {
            $assignment = Assignment::findOrFail($id);

            if (!$assignment->assignment_file || !file_exists(public_path($assignment->assignment_file))) {
                return redirect()->back()->with('error', 'Assignment file not found.');
            }

            return response()->download(public_path($assignment->assignment_file));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }

    // AJAX Methods for dynamic dropdowns

    /**
     * Get classes by institution.
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
     * Get sections by class.
     */
    public function getSectionsByClass($classId)
    {
        try {
            $sections = Section::where('status', 1)->get(['id', 'name']);

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
     * Get subjects by institution and class.
     */
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        try {
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

    /**
     * Get teachers by institution.
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
}
