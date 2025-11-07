<?php

namespace App\Http\Controllers\API\Teacher\Academic;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use App\Models\AssignSubject;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\StudentAssignment;

class AssignmentController extends Controller
{
    public function Lists(Request $request)
    {
        // Get the logged-in teacher
         $currentTeacher = Teacher::where('email', $request->email)->first();
            
        if (!$currentTeacher) {
            return response()->json([
                'success' => false,
                'message' => 'No teacher found with this email',
                'data' => []
            ], 404);
        }
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
        
        return response()->json([
            'success' => true,
            'message' => 'Assignments retrieved successfully',
            'data' => [
                'assignments' => $assignments,
                'classes' => $classes,
                'subjects' => $subjects,
                'currentTeacher' => $currentTeacher
            ]
        ], 200);
    }
    
    public function createAssignment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'due_date' => 'required|string|after:today',
                // 'assignment_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

             if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get teacher by email
            $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

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
                    ,'data' => []
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
                'data' => []
                // 'data' => $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating assignment: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    public function editAssignment(Request $request, $id)
    {
        try {
             $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }
            $institutionId = $currentTeacher->institution_id;

            $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
                ->where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found'
                    ,'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Assignment found',
                'data' => $assignment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assignment: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    public function updateAssignment(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'subject_id' => 'required|exists:subjects,id',
                'due_date' => 'required|string',
                // 'assignment_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'status' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $institutionId = $currentTeacher->institution_id;
            $teacherId = $currentTeacher->id;

            $assignment = Assignment::where('id', $id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment not found',
                    'data' => []
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
                    'message' => 'You are not assigned to teach this subject for the selected class and section.',
                    'data' => []
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
                'data' => []
                // 'data' => $assignment->load(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating assignment: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deleteAssignment(Request $request, $id)
    {
        try {
            $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }
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
                'message' => 'Assignment deleted successfully',
                'data' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting assignment: ' . $e->getMessage()
            ], 500);
        }
    }
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
                    'data' => $validator->errors()
                ], 422);
            }

            $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

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
}