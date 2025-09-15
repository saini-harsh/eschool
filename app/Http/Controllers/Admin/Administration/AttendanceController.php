<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
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
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $institutions = Institution::where('status', true)->get();
        return view('admin.administration.attendance.attendance', compact('institutions'));
    }

    public function filter(Request $request)
    {
        $role = $request->role;
        $institutionId = $request->institution;
        $classId = $request->class;
        $sectionId = $request->section;
        $date = $request->date;

        $query = Attendance::query();

        // Apply filters
        if ($role) {
            $query->where('role', $role);
        }
        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }
        if ($classId) {
            $query->where('class_id', $classId);
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
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

    public function getClassesByInstitution($institutionId)
    {
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'name']);

        return response()->json($classes);
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

    public function getStudentsByClassSection(Request $request)
    {
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $institutionId = $request->institution_id;

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'roll_number', 'admission_number']);

        return response()->json($students);
    }

    public function getTeachersByInstitution($institutionId)
    {
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'email']);

        return response()->json($teachers);
    }

    public function getStaffByInstitution($institutionId)
    {
        $staff = NonWorkingStaff::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'first_name', 'last_name', 'email', 'designation']);

        return response()->json($staff);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,teacher,nonworkingstaff',
            'institution_id' => 'required|exists:institutions,id',
            'date' => 'required|date_format:Y-m-d',
            'attendance_data' => 'required|array',
        ]);

        $role = $request->role;
        $institutionId = $request->institution_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $date = $request->date;
        $attendanceData = $request->attendance_data;

        $markedBy = auth('admin')->id();
        $markedByRole = 'admin';

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

                // Get class/section based on role
                $attendanceClassId = null;
                $attendanceSectionId = null;
                $attendanceTeacherId = null;

                if ($role === 'student') {
                    $attendanceClassId = $classId;
                    $attendanceSectionId = $sectionId;
                    $attendanceTeacherId = $this->getAssignedTeacher($classId, $sectionId);
                } elseif ($role === 'teacher') {
                    // Get teacher's assigned class and section
                    $assignment = \App\Models\AssignClassTeacher::where('teacher_id', $userId)
                        ->where('status', true)
                        ->first();
                    if ($assignment) {
                        $attendanceClassId = $assignment->class_id;
                        $attendanceSectionId = $assignment->section_id;
                    }
                }

                if ($existingAttendance) {
                    // Update existing attendance
                    $existingAttendance->update([
                        'class_id' => $attendanceClassId,
                        'section_id' => $attendanceSectionId,
                        'teacher_id' => $attendanceTeacherId,
                        'status' => $status,
                        'remarks' => $remarks,
                        'marked_by' => $markedBy,
                        'marked_by_role' => $markedByRole,
                        'is_confirmed' => true, // Admin can directly confirm
                        'confirmed_by' => $markedBy,
                        'confirmed_at' => now(),
                    ]);
                } else {
                    // Create new attendance
                    Attendance::create([
                        'user_id' => $userId,
                        'role' => $role,
                        'institution_id' => $institutionId,
                        'class_id' => $attendanceClassId,
                        'section_id' => $attendanceSectionId,
                        'teacher_id' => $attendanceTeacherId,
                        'date' => $date,
                        'status' => $status,
                        'remarks' => $remarks,
                        'marked_by' => $markedBy,
                        'marked_by_role' => $markedByRole,
                        'is_confirmed' => true, // Admin can directly confirm
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
            'confirmed_by' => auth('admin')->id(),
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

    private function getAssignedTeacher($classId, $sectionId)
    {
        $assignment = AssignClassTeacher::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', true)
            ->first();

        return $assignment ? $assignment->teacher_id : null;
    }
}
