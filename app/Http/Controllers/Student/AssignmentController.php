<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display assignment list for student
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get assignments for the student's class and section
        $assignments = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher', 'studentAssignments' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->where('class_id', $student->class_id)
        ->where('section_id', $student->section_id)
        ->where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('student.assignments.index', compact('assignments', 'student'));
    }

    /**
     * Show assignment details and submission form
     */
    public function show($id)
    {
        $student = Auth::guard('student')->user();
        
        $assignment = Assignment::with(['institution', 'schoolClass', 'section', 'subject', 'teacher'])
            ->where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 1)
            ->first();

        if (!$assignment) {
            return redirect()->route('student.assignments.index')
                ->with('error', 'Assignment not found or you do not have access to it.');
        }

        // Get student's submission if exists
        $studentSubmission = StudentAssignment::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.assignments.show', compact('assignment', 'studentSubmission', 'student'));
    }

    /**
     * Submit assignment
     */
    public function submit(Request $request, $id)
    {
        $student = Auth::guard('student')->user();
        
        $validator = Validator::make($request->all(), [
            'submitted_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => $isLate ? 'Assignment submitted successfully (Late submission)' : 'Assignment submitted successfully',
            'data' => $studentAssignment
        ]);
    }

    /**
     * Download submitted assignment file
     */
    public function downloadSubmission($id)
    {
        $student = Auth::guard('student')->user();
        
        $studentAssignment = StudentAssignment::where('id', $id)
            ->where('student_id', $student->id)
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
    }

    /**
     * Download assignment file
     */
    public function downloadAssignment($id)
    {
        $student = Auth::guard('student')->user();
        
        $assignment = Assignment::where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 1)
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
    }
}
