<?php

namespace App\Http\Controllers\Institution\Administration;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\NonWorkingStaff;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        $institutionId = auth('institution')->user()->id;
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'name']);

        return view('institution.administration.attendance.attendance', compact('classes'));
    }

    public function filter(Request $request)
    {
        $institutionId = auth('institution')->user()->id;
        $role = $request->role;
        $classId = $request->class;
        $sectionId = $request->section;
        $teacherId = $request->teacher;
        $date = $request->date;

        $query = Attendance::where('institution_id', $institutionId);

        // Apply filters
        if ($role) {
            $query->where('role', $role);
        }
        if ($classId) {
            $query->where('class_id', $classId);
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        if ($date) {
            $query->whereDate('date', $date);
        }

        // Eager load relationships
        $query->with(['institution', 'schoolClass', 'section', 'assignedTeacher', 'markedBy', 'confirmedBy']);

        // Load user based on role
        switch ($role) {
            case 'student':
                $query->with('student');
                break;
            case 'teacher':
                $query->with('teacher');
                break;
            case 'nonworkingstaff':
                $query->with('staff');
                break;
        }

        $records = $query->orderBy('date', 'desc')->get();

        return response()->json($records);
    }

    public function getSectionsByClass($classId)
    {
        $class = SchoolClass::find($classId);
        if (!$class) {
            return response()->json([]);
        }

        $sections = Section::whereIn('id', $class->section_ids ?? [])
            ->where('status', true)
            ->get(['id', 'name']);

        return response()->json($sections);
    }

    public function getTeachersByClassSection(Request $request)
    {
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $teachers = AssignClassTeacher::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->with('teacher:id,first_name,last_name,email')
            ->get()
            ->pluck('teacher');

        return response()->json($teachers);
    }

    public function getStudentsByClassSection(Request $request)
    {
        $institutionId = auth('institution')->user()->id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'roll_number', 'admission_number']);

        return response()->json($students);
    }

    public function getTeachersByInstitution()
    {
        $institutionId = auth('institution')->user()->id;
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'email']);

        return response()->json($teachers);
    }

    public function getStaffByInstitution()
    {
        $institutionId = auth('institution')->user()->id;
        $staff = NonWorkingStaff::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'email', 'designation']);

        return response()->json($staff);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,teacher,nonworkingstaff',
            'class_id' => 'required_if:role,student|nullable|exists:classes,id',
            'section_id' => 'required_if:role,student|nullable|exists:sections,id',
            'teacher_id' => 'required_if:role,student|nullable|exists:teachers,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
        ]);

        $institutionId = auth('institution')->user()->id;
        $role = $request->role;
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $teacherId = $request->teacher_id;
        $date = $request->date;
        $attendanceData = $request->attendance_data;

        $markedBy = auth('institution')->id();
        $markedByRole = 'institution';

        try {
            foreach ($attendanceData as $data) {
                $userId = $data['user_id'];
                $status = $data['status'];
                $remarks = $data['remarks'] ?? null;

                // Check if attendance already exists
                $existingAttendance = Attendance::where('user_id', $userId)
                    ->where('role', $role)
                    ->where('institution_id', $institutionId)
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
                        'is_confirmed' => true, // Institution can directly confirm
                        'confirmed_by' => $markedBy,
                        'confirmed_at' => now(),
                    ]);
                } else {
                    // Create new attendance
                    Attendance::create([
                        'user_id' => $userId,
                        'role' => $role,
                        'institution_id' => $institutionId,
                        'class_id' => $classId,
                        'section_id' => $sectionId,
                        'teacher_id' => $teacherId,
                        'date' => $date,
                        'status' => $status,
                        'remarks' => $remarks,
                        'marked_by' => $markedBy,
                        'marked_by_role' => $markedByRole,
                        'is_confirmed' => true, // Institution can directly confirm
                        'confirmed_by' => $markedBy,
                        'confirmed_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully'
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

        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'status' => $request->status,
            'remarks' => $request->remarks,
            'confirmed_by' => auth('institution')->id(),
            'confirmed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully'
        ]);
    }

    public function deleteAttendance($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully'
        ]);
    }
}
