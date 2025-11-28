<?php

namespace App\Http\Controllers\API\Student\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL; // make sure this is at the top
use App\Models\Attendance;
use App\Models\Student;
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
         $currentStudent = Student::where('email', $request->email)->first();
            
        if (!$currentStudent) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
        }
        $institutionId = $currentStudent->institution_id;
        
        $assignments = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments' => function($query) use ($currentStudent) {
            $query->where('student_id', $currentStudent->id);
        }])
        ->where('class_id', $currentStudent->class_id)
        ->where('section_id', $currentStudent->section_id)
        ->where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        $assignments->map(function ($assignment) {
            // Convert file URLs
            $assignment->assignment_file = $assignment->assignment_file 
                ? URL::to($assignment->assignment_file) 
                : null;

            if ($assignment->institution) {
                $assignment->institution->logo = $assignment->institution->logo 
                    ? URL::to($assignment->institution->logo) 
                    : null;
            }

            if ($assignment->section && $assignment->section->institution) {
                $assignment->section->institution->logo = $assignment->section->institution->logo 
                    ? URL::to($assignment->section->institution->logo) 
                    : null;
            }

            if ($assignment->teacher) {
                $assignment->teacher->profile_image = $assignment->teacher->profile_image 
                    ? URL::to($assignment->teacher->profile_image) 
                    : null;
            }

            // ✅ Fix for studentAssignments (handle as collection)
            $assignment->studentAssignments = $assignment->studentAssignments->map(function ($studentAssignment) {
                $studentAssignment->submitted_file = $studentAssignment->submitted_file 
                    ? URL::to($studentAssignment->submitted_file) 
                    : null;
                return $studentAssignment;
            });

            // ✅ Decode section_ids safely
            if ($assignment->schoolClass) {
                $sectionIds = $assignment->schoolClass->section_ids;

                if (is_string($sectionIds)) {
                    $assignment->schoolClass->section_ids = json_decode($sectionIds, true);
                } elseif (is_array($sectionIds)) {
                    $assignment->schoolClass->section_ids = $sectionIds;
                } else {
                    $assignment->schoolClass->section_ids = [];
                }
            }


            return $assignment;
        });
            
        
        return response()->json([
            'success' => true,
            'message' => 'Assignments retrieved successfully',
            'data' => [
                'assignments' => $assignments,
                // 'student' => $currentStudent
            ]
        ], 200);
    }
    public function show(Request $request, $id)
    {
       
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
        }

        $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ->where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 1)
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or you do not have access to it.',
                'data' => []
            ], 404);
        }

        // Get student's submission if exists
        $studentSubmission = StudentAssignment::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();

        $studentSubmission->submitted_file = $studentSubmission->submitted_file 
            ? URL::to($studentSubmission->submitted_file) 
            : null;
        
        $assignment->assignment_file = $assignment->assignment_file 
            ? URL::to($assignment->assignment_file) 
            : null;
        
        $assignment->institution->logo = $assignment->institution->logo 
            ? URL::to($assignment->institution->logo) 
            : null;
        
        $assignment->section->institution->logo = $assignment->section->institution->logo 
        ? URL::to($assignment->section->institution->logo) 
        : null;

        $assignment->teacher->profile_image = $assignment->teacher->profile_image 
        ? URL::to($assignment->teacher->profile_image) 
        : null;

          // ✅ Decode section_ids from JSON string to array
        if ($assignment->schoolClass && $assignment->schoolClass->section_ids) {
            $assignment->schoolClass->section_ids = json_decode($assignment->schoolClass->section_ids, true);
        }

        if ($assignment->section && $assignment->section->class && $assignment->section->class->section_ids) {
            $assignment->section->class->section_ids = json_decode($assignment->section->class->section_ids, true);
        }

            return response()->json([
            'success' => true,
            'message' => 'Assignment retrieved successfully',
            'data' => [
                'assignment' => $assignment,
                'studentSubmission' => $studentSubmission,
                // 'student' => $student
            ]
        ], 200);
    }
    public function submit(Request $request, $id)
    {
        
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
        }

        $assignment = Assignment::where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 1)
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found or you do not have access to it.'
            ], 404);
        }

        // Check if already submitted
        $existingSubmission = StudentAssignment::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted this assignment.'
            ], 422);
        }

        // Check if due date has passed
        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
        $isLate = now()->gt($dueDate);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('submitted_file')) {
            $file = $request->file('submitted_file');
            $fileName = time() . '_' . $student->id . '_' . $file->getClientOriginalName();
            $file->move(public_path('student/uploads/assignments'), $fileName);
            $filePath = 'student/uploads/assignments/' . $fileName;
        }

        $studentAssignment = StudentAssignment::create([
            'assignment_id' => $id,
            'student_id' => $student->id,
            'submitted_file' => $filePath,
            'submission_date' => now(),
            'status' => $isLate ? 'late' : 'submitted',
            'remarks' => $request->remarks,
        ]);

        $studentAssignment->submitted_file = $studentAssignment->submitted_file 
            ? URL::to($studentAssignment->submitted_file) 
            : null;

        return response()->json([
            'success' => true,
            'message' => $isLate ? 'Assignment submitted successfully (Late submission)' : 'Assignment submitted successfully',
            'data' => $studentAssignment
        ]);
    }
    public function downloadAssignment(Request $request, $id)
    {   
        
        $student = Student::where('email', $request->email)->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'data' => []
            ], 404);
        }

        $assignment = Assignment::where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 1)
            ->first();

        if (!$assignment || !$assignment->assignment_file) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment file not found',
                'data' => []
            ], 404);
        }

        if (!file_exists(public_path($assignment->assignment_file))) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Assignment file downloaded successfully',
            'data' => [
                'file' => URL::to($assignment->assignment_file)
            ]
        ]);
    }

    public function downloadSubmission(Request $request, $id)
    {
       
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'data' => []
            ], 404);
        }

        $studentAssignment = StudentAssignment::where('id', $id)
            ->where('student_id', $student->id)
            ->first();

        if (!$studentAssignment || !$studentAssignment->submitted_file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
                'data' => []
            ], 404);
        }

        if (!file_exists(public_path($studentAssignment->submitted_file))) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server',
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Submission file downloaded successfully',
            'data' => [
                'file' => URL::to($studentAssignment->submitted_file)
            ]
        ]);
    }

}