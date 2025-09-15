<?php

namespace App\Http\Controllers\Teacher\Administration;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function index()
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;

        // Get classes and sections assigned to this teacher
        $assignments = AssignClassTeacher::where('teacher_id', $teacherId)
            ->where('status', true)
            ->with(['schoolClass:id,name', 'section:id,name'])
            ->get();

        $classes = $assignments->pluck('schoolClass')->unique('id')->values();
        $sections = $assignments->pluck('section')->unique('id')->values();

        return view('teacher.administration.attendance.attendance', compact('classes', 'sections'));
    }

    public function filter(Request $request)
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;
        $classId = $request->class;
        $sectionId = $request->section;
        $date = $request->date;

        $query = Attendance::where('institution_id', $institutionId)
            ->where('role', 'student'); // Teachers can only manage student attendance

        // Apply filters
        if ($classId) {
            $query->where('class_id', $classId);
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }
        if ($date) {
            $query->whereDate('date', $date);
        }

        // Only show attendance for classes/sections assigned to this teacher
        $query->where(function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId)
              ->orWhere('marked_by', $teacherId);
        });

        // Eager load relationships
        $query->with(['institution', 'schoolClass', 'section', 'student', 'assignedTeacher', 'markedBy', 'confirmedBy']);

        $records = $query->orderBy('date', 'desc')->get();

        return response()->json($records);
    }

    public function getSectionsByClass($classId)
    {
        $teacherId = auth('teacher')->user()->id;

        // Get sections assigned to this teacher for the given class
        $assignments = AssignClassTeacher::where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('status', true)
            ->with('section:id,name')
            ->get();

        $sections = $assignments->pluck('section')->unique('id')->values();

        return response()->json($sections);
    }

    public function getStudentsByClassSection(Request $request)
    {
        $institutionId = auth('teacher')->user()->institution_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'roll_number', 'admission_number']);

        return response()->json($students);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
        ]);

        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $date = $request->date;
        $attendanceData = $request->attendance_data;

        // Verify that this teacher is assigned to this class/section
        $assignment = AssignClassTeacher::where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this class/section'
            ], 403);
        }

        $markedBy = $teacherId;
        $markedByRole = 'teacher';

        try {
            foreach ($attendanceData as $data) {
                $userId = $data['user_id'];
                $status = $data['status'];
                $remarks = $data['remarks'] ?? null;

                // Check if attendance already exists
                $existingAttendance = Attendance::where('user_id', $userId)
                    ->where('role', 'student')
                    ->where('institution_id', $institutionId)
                    ->where('class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->whereDate('date', $date)
                    ->first();

                if ($existingAttendance) {
                    // Update existing attendance
                    $existingAttendance->update([
                        'class_id' => $classId,
                        'section_id' => $sectionId,
                        'teacher_id' => $teacherId,
                        'status' => $status,
                        'remarks' => $remarks,
                        'marked_by' => $markedBy,
                        'marked_by_role' => $markedByRole,
                        'is_confirmed' => false, // Teacher marks attendance, needs institution confirmation
                    ]);
                } else {
                    // Create new attendance
                    Attendance::create([
                        'user_id' => $userId,
                        'role' => 'student',
                        'institution_id' => $institutionId,
                        'class_id' => $classId,
                        'section_id' => $sectionId,
                        'teacher_id' => $teacherId,
                        'date' => $date,
                        'status' => $status,
                        'remarks' => $remarks,
                        'marked_by' => $markedBy,
                        'marked_by_role' => $markedByRole,
                        'is_confirmed' => false, // Teacher marks attendance, needs institution confirmation
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully. Waiting for institution confirmation.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        $teacherId = auth('teacher')->user()->id;
        $attendance = Attendance::findOrFail($id);

        // Check if this teacher can update this attendance
        if ($attendance->teacher_id !== $teacherId && $attendance->marked_by !== $teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this attendance'
            ], 403);
        }

        $attendance->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
            'is_confirmed' => false, // Reset confirmation when updated
            'confirmed_by' => null,
            'confirmed_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully. Waiting for institution confirmation.'
        ]);
    }

    public function getMyAttendance()
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;

        $attendance = Attendance::where('user_id', $teacherId)
            ->where('role', 'teacher')
            ->where('institution_id', $institutionId)
            ->with(['institution', 'markedBy', 'confirmedBy'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($attendance);
    }

    public function markMyAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:500',
        ]);

        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;
        $date = $request->date;
        $status = $request->status;
        $remarks = $request->remarks;

        // Check if attendance already exists
        $existingAttendance = Attendance::where('user_id', $teacherId)
            ->where('role', 'teacher')
            ->where('institution_id', $institutionId)
            ->whereDate('date', $date)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already marked for this date'
            ], 400);
        }

        try {
            Attendance::create([
                'user_id' => $teacherId,
                'role' => 'teacher',
                'institution_id' => $institutionId,
                'date' => $date,
                'status' => $status,
                'remarks' => $remarks,
                'marked_by' => $teacherId,
                'marked_by_role' => 'teacher',
                'is_confirmed' => false, // Teacher marks own attendance, needs institution confirmation
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your attendance marked successfully. Waiting for institution confirmation.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance: ' . $e->getMessage()
            ], 500);
        }
    }
}
