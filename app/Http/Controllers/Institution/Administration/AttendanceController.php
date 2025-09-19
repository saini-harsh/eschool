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

    public function markAttendancePage()
    {
        $institutionId = auth('institution')->user()->id;
        
        // Get all classes for this institution
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', true)
            ->get(['id', 'name']);

        return view('institution.administration.attendance.mark-attendance', compact('classes'));
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
            'date' => 'required|date_format:Y-m-d',
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

                // Get class/section based on role
                $attendanceClassId = null;
                $attendanceSectionId = null;
                $attendanceTeacherId = null;

                if ($role === 'student') {
                    $attendanceClassId = $classId;
                    $attendanceSectionId = $sectionId;
                    $attendanceTeacherId = $teacherId;
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
                        'class_id' => $attendanceClassId,
                        'section_id' => $attendanceSectionId,
                        'teacher_id' => $attendanceTeacherId,
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

    public function getAttendanceMatrix(Request $request)
    {
        $institutionId = auth('institution')->user()->id;
        $role = $request->role;
        $classId = $request->class;
        $sectionId = $request->section;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        // Get date range
        $startDate = $fromDate ? \Carbon\Carbon::createFromFormat('d M, Y', $fromDate) : \Carbon\Carbon::now()->startOfMonth();
        $endDate = $toDate ? \Carbon\Carbon::createFromFormat('d M, Y', $toDate) : \Carbon\Carbon::now()->endOfMonth();

        // Generate date range
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get users based on role
        $users = [];
        if ($role === 'student' && $classId && $sectionId) {
            $users = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', true)
                ->get(['id', 'first_name', 'last_name', 'roll_number', 'admission_number']);
                
        } elseif ($role === 'teacher') {
            $users = Teacher::where('institution_id', $institutionId)
                ->where('status', true)
                ->get(['id', 'first_name', 'last_name', 'email']);
                
        } elseif ($role === 'nonworkingstaff') {
            $users = NonWorkingStaff::where('institution_id', $institutionId)
                ->where('status', true)
                ->get(['id', 'first_name', 'last_name', 'email', 'designation']);
        }
        
        // If no users found for the selected role, return empty array
        if (empty($users)) {
            return response()->json([
                'dates' => $dates,
                'users' => [],
                'class_info' => '',
                'from_date' => $startDate->format('d M, Y'),
                'to_date' => $endDate->format('d M, Y'),
                'message' => 'No ' . $role . 's found for the selected criteria'
            ]);
        }

        // Get attendance data
        $attendanceData = [];
        foreach ($users as $user) {
            $userAttendance = [];
            foreach ($dates as $date) {
                $attendance = Attendance::where('user_id', $user->id)
                    ->where('role', $role)
                    ->where('institution_id', $institutionId)
                    ->whereDate('date', $date)
                    ->first();
                
                if ($attendance) {
                    $userAttendance[$date] = [
                        'status' => $attendance->status,
                        'is_confirmed' => $attendance->is_confirmed,
                        'marked_by_role' => $attendance->marked_by_role,
                        'attendance_id' => $attendance->id
                    ];
                } else {
                    $userAttendance[$date] = null;
                }
            }
            $attendanceData[] = [
                'user' => $user,
                'attendance' => $userAttendance
            ];
        }

        // Get class and section info for title
        $classInfo = '';
        if ($classId && $sectionId) {
            $class = SchoolClass::find($classId);
            $section = Section::find($sectionId);
            $classInfo = $class ? $class->name : '';
            $classInfo .= $section ? ' - ' . $section->name : '';
        }

        return response()->json([
            'dates' => $dates,
            'users' => $attendanceData,
            'class_info' => $classInfo,
            'from_date' => $startDate->format('d M, Y'),
            'to_date' => $endDate->format('d M, Y'),
            'role' => $role
        ]);
    }

    public function confirmAttendance(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $institutionId = auth('institution')->user()->id;

        // Check if this attendance belongs to this institution
        if ($attendance->institution_id !== $institutionId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Update attendance confirmation
        $attendance->update([
            'is_confirmed' => true,
            'confirmed_by' => $institutionId,
            'confirmed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance confirmed successfully'
        ]);
    }
}
