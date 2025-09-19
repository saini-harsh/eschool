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

    public function markAttendancePage()
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;

        // Get classes and sections assigned to this teacher
        $assignments = AssignClassTeacher::where('teacher_id', $teacherId)
            ->where('status', true)
            ->with(['schoolClass:id,name', 'section:id,name'])
            ->get();

        $classes = $assignments->pluck('schoolClass')->unique('id')->values();

        return view('teacher.administration.attendance.mark-attendance', compact('classes'));
    }


    public function getSectionsByClass($classId)
    {
        $teacherId = auth('teacher')->user()->id;

        // First check if teacher is assigned to this class
        $isAssigned = AssignClassTeacher::where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('status', true)
            ->exists();

        if (!$isAssigned) {
            return response()->json([]);
        }

        // Get the class and its section_ids
        $schoolClass = SchoolClass::find($classId);
        if (!$schoolClass || !$schoolClass->section_ids) {
            return response()->json([]);
        }

        // Get sections using the section_ids from the class
        $sections = Section::whereIn('id', $schoolClass->section_ids)
            ->where('status', true)
            ->get(['id', 'name']);

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
            'date' => 'required|date_format:Y-m-d',
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
                        'is_confirmed' => true, // Teacher marks student attendance, directly confirmed
                        'confirmed_by' => $teacherId,
                        'confirmed_at' => now(),
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
                        'is_confirmed' => true, // Teacher marks student attendance, directly confirmed
                        'confirmed_by' => $teacherId,
                        'confirmed_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Student attendance marked and confirmed successfully.'
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

        // For student attendance, keep it confirmed when teacher updates
        // For teacher attendance, reset confirmation
        $updateData = [
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];

        if ($attendance->role === 'student') {
            // Student attendance remains confirmed when teacher updates
            $updateData['is_confirmed'] = true;
            $updateData['confirmed_by'] = $teacherId;
            $updateData['confirmed_at'] = now();
        } else {
            // Teacher attendance needs re-confirmation when updated
            $updateData['is_confirmed'] = false;
            $updateData['confirmed_by'] = null;
            $updateData['confirmed_at'] = null;
        }

        $attendance->update($updateData);

        $message = $attendance->role === 'student' 
            ? 'Student attendance updated and confirmed successfully.'
            : 'Teacher attendance updated successfully. Waiting for institution confirmation.';

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }


    public function markMyAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
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
            // Get teacher's assigned class and section
            $assignment = AssignClassTeacher::where('teacher_id', $teacherId)
                ->where('status', true)
                ->with(['schoolClass', 'section'])
                ->first();

            Attendance::create([
                'user_id' => $teacherId,
                'role' => 'teacher',
                'institution_id' => $institutionId,
                'class_id' => $assignment ? $assignment->class_id : null,
                'section_id' => $assignment ? $assignment->section_id : null,
                'teacher_id' => null, // Teacher marking their own attendance, not another teacher
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

    public function getAttendanceMatrix(Request $request)
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;
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

        // Get students/users based on role
        $users = [];
        if ($role === 'student' && $classId && $sectionId) {
            // Verify that this teacher is assigned to this class/section
            $assignment = AssignClassTeacher::where('teacher_id', $teacherId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', true)
                ->first();

            if (!$assignment) {
                return response()->json([
                    'error' => 'You are not assigned to this class/section'
                ], 403);
            }

            // Only get students for student role
            $users = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', true)
                ->get(['id', 'first_name', 'last_name', 'roll_number', 'admission_number']);
                
        } elseif ($role === 'teacher') {
            // Only get teachers for teacher role
            $users = Teacher::where('institution_id', $institutionId)
                ->where('status', true)
                ->get(['id', 'first_name', 'last_name', 'email']);
        }
        
        // If no users found for the selected role, return empty array
        if (empty($users)) {
            return response()->json([
                'dates' => $dates,
                'users' => [],
                'class_info' => $classInfo,
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
                
                $userAttendance[$date] = $attendance ? $attendance->status : null;
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

        $response = [
            'dates' => $dates,
            'users' => $attendanceData,
            'class_info' => $classInfo,
            'from_date' => $startDate->format('d M, Y'),
            'to_date' => $endDate->format('d M, Y'),
            'role' => $role
        ];
        
        
        return response()->json($response);
    }

    public function getMyAttendanceMatrix(Request $request)
    {
        $teacherId = auth('teacher')->user()->id;
        $institutionId = auth('teacher')->user()->institution_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        // Generate date range
        $dates = [];
        if ($fromDate && $toDate) {
            // Convert from "d M, Y" format to "Y-m-d" format
            $start = \Carbon\Carbon::createFromFormat('d M, Y', $fromDate);
            $end = \Carbon\Carbon::createFromFormat('d M, Y', $toDate);
            
            while ($start->lte($end)) {
                $dates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        } else {
            // Default to current month if no date range provided
            $start = \Carbon\Carbon::now()->startOfMonth();
            $end = \Carbon\Carbon::now()->endOfMonth();
            
            while ($start->lte($end)) {
                $dates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }

        // Get teacher's attendance records
        $query = Attendance::where('user_id', $teacherId)
            ->where('role', 'teacher')
            ->where('institution_id', $institutionId);

        if ($fromDate && $toDate) {
            // Convert dates to proper format for database query
            $startDate = \Carbon\Carbon::createFromFormat('d M, Y', $fromDate)->format('Y-m-d');
            $endDate = \Carbon\Carbon::createFromFormat('d M, Y', $toDate)->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $attendanceRecords = $query->get();

        // Create attendance matrix for the teacher
        $attendanceMatrix = [];
        foreach ($dates as $date) {
            // Convert date to Carbon object for proper comparison
            $dateObj = \Carbon\Carbon::parse($date);
            $record = $attendanceRecords->filter(function($item) use ($dateObj) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d') === $dateObj->format('Y-m-d');
            })->first();
            
            $attendanceMatrix[$date] = $record ? $record->status : null;
        }

        // Get teacher info
        $teacher = auth('teacher')->user();

        return response()->json([
            'dates' => $dates,
            'teacher' => [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'email' => $teacher->email,
                'designation' => $teacher->designation ?? 'Teacher'
            ],
            'attendance' => $attendanceMatrix,
            'from_date' => $fromDate,
            'to_date' => $toDate
        ]);
    }
}
