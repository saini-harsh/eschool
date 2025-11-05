<?php

namespace App\Http\Controllers\API\Teacher\Academic;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Get attendance data with filtering options
     */
    public function getAttendanceData(Request $request)
    {
        try {
            $teacher = Teacher::where('email', $request->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'class_id' => 'nullable|exists:school_classes,id',
                'section_id' => 'nullable|exists:sections,id',
                'date' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'nullable|in:present,absent,late',
                'student_id' => 'nullable|exists:students,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get assigned classes for the teacher
            $assignedClasses = AssignClassTeacher::where('teacher_id', $teacher->id)
                ->pluck('class_id')
                ->unique()
                ->toArray();

            if (empty($assignedClasses)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No assigned classes found',
                    'data' => [
                        'attendance' => [],
                        'summary' => []
                    ]
                ]);
            }

            // Build query
            $query = Attendance::with(['student', 'schoolClass', 'section'])
                ->where('institution_id', $teacher->institution_id)
                ->where('role', 'student')
                ->whereIn('class_id', $assignedClasses);

            // Apply filters
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            if ($request->filled('student_id')) {
                $query->where('user_id', $request->student_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Date filtering
            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                // Default to current month
                $query->whereMonth('date', Carbon::now()->month)
                      ->whereYear('date', Carbon::now()->year);
            }

            $attendance = $query->orderBy('date', 'desc')
                ->orderBy('class_id')
                ->orderBy('section_id')
                ->get();

            // Calculate summary statistics
            $summary = $this->calculateAttendanceSummary($attendance);

            return response()->json([
                'success' => true,
                'message' => 'Attendance data retrieved successfully',
                'data' => [
                    'attendance' => $attendance,
                    'summary' => $summary,
                    'total_records' => $attendance->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving attendance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance by class and section
     */
    public function getClassSectionAttendance(Request $request)
    {
        try {
            $teacher = Teacher::where('email', $request->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:school_classes,id',
                'section_id' => 'required|exists:sections,id',
                'date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if teacher is assigned to this class
            $isAssigned = AssignClassTeacher::where('teacher_id', $teacher->id)
                ->where('class_id', $request->class_id)
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this class'
                ], 403);
            }

            // Get students in the class and section
            $students = Student::where('institution_id', $teacher->institution_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 1)
                ->orderBy('first_name')
                ->get();

            // Get existing attendance for the date
            $existingAttendance = Attendance::where('institution_id', $teacher->institution_id)
                ->where('role', 'student')
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->whereDate('date', $request->date)
                ->get()
                ->keyBy('user_id');

            $attendanceData = $students->map(function ($student) use ($existingAttendance) {
                $attendance = $existingAttendance->get($student->id);
                return [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_roll_no' => $student->roll_no,
                    'attendance_id' => $attendance ? $attendance->id : null,
                    'status' => $attendance ? $attendance->status : 'present',
                    'remarks' => $attendance ? $attendance->remarks : '',
                    'is_confirmed' => $attendance ? $attendance->is_confirmed : false,
                    'marked_at' => $attendance ? $attendance->created_at->format('Y-m-d H:i:s') : null
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Class attendance retrieved successfully',
                'data' => [
                    'students' => $attendanceData,
                    'class_name' => SchoolClass::find($request->class_id)->name,
                    'section_name' => Section::find($request->section_id)->name,
                    'date' => $request->date,
                    'total_students' => $students->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving class attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark or update attendance
     */
    public function markAttendance(Request $request)
    {
        try {
            $teacher = Teacher::where('email', $request->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:school_classes,id',
                'section_id' => 'required|exists:sections,id',
                'date' => 'required|date',
                'attendance' => 'required|array',
                'attendance.*.student_id' => 'required|exists:students,id',
                'attendance.*.status' => 'required|in:present,absent,late',
                'attendance.*.remarks' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if teacher is assigned to this class
            $isAssigned = AssignClassTeacher::where('teacher_id', $teacher->id)
                ->where('class_id', $request->class_id)
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this class'
                ], 403);
            }

            $attendanceData = [];
            $date = Carbon::parse($request->date);

            foreach ($request->attendance as $attendance) {
                // Check if student exists and belongs to the class/section
                $student = Student::where('id', $attendance['student_id'])
                    ->where('class_id', $request->class_id)
                    ->where('section_id', $request->section_id)
                    ->first();

                if (!$student) {
                    continue;
                }

                $attendanceRecord = Attendance::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'role' => 'student',
                        'institution_id' => $teacher->institution_id,
                        'class_id' => $request->class_id,
                        'section_id' => $request->section_id,
                        'date' => $date->format('Y-m-d')
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'status' => $attendance['status'],
                        'remarks' => $attendance['remarks'] ?? null,
                        'marked_by' => $teacher->id,
                        'marked_by_role' => 'teacher',
                        'is_confirmed' => false
                    ]
                );

                $attendanceData[] = $attendanceRecord;
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => [
                    'attendance' => $attendanceData,
                    'total_marked' => count($attendanceData)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance summary/statistics
     */
    public function getAttendanceSummary(Request $request)
    {
        try {
            $teacher = Teacher::where('email', $request->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'class_id' => 'nullable|exists:school_classes,id',
                'section_id' => 'nullable|exists:sections,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'student_id' => 'nullable|exists:students,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get assigned classes for the teacher
            $assignedClasses = AssignClassTeacher::where('teacher_id', $teacher->id)
                ->pluck('class_id')
                ->unique()
                ->toArray();

            if (empty($assignedClasses)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No assigned classes found',
                    'data' => [
                        'summary' => [],
                        'statistics' => []
                    ]
                ]);
            }

            // Build query
            $query = Attendance::where('institution_id', $teacher->institution_id)
                ->where('role', 'student')
                ->whereIn('class_id', $assignedClasses);

            // Apply filters
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            if ($request->filled('student_id')) {
                $query->where('user_id', $request->student_id);
            }

            // Date filtering
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                // Default to current month
                $query->whereMonth('date', Carbon::now()->month)
                      ->whereYear('date', Carbon::now()->year);
            }

            $attendance = $query->get();

            // Calculate detailed summary
            $summary = $this->calculateDetailedSummary($attendance);

            return response()->json([
                'success' => true,
                'message' => 'Attendance summary retrieved successfully',
                'data' => [
                    'summary' => $summary,
                    'date_range' => [
                        'start_date' => $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
                        'end_date' => $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving attendance summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate basic attendance summary
     */
    private function calculateAttendanceSummary($attendance)
    {
        $total = $attendance->count();
        $present = $attendance->where('status', 'present')->count();
        $absent = $attendance->where('status', 'absent')->count();
        $late = $attendance->where('status', 'late')->count();

        return [
            'total_records' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'present_percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            'absent_percentage' => $total > 0 ? round(($absent / $total) * 100, 2) : 0,
            'late_percentage' => $total > 0 ? round(($late / $total) * 100, 2) : 0
        ];
    }

    /**
     * Calculate detailed attendance summary
     */
    private function calculateDetailedSummary($attendance)
    {
        $summary = $this->calculateAttendanceSummary($attendance);
        
        // Group by class
        $byClass = $attendance->groupBy('class_id')->map(function ($classAttendance) {
            return $this->calculateAttendanceSummary($classAttendance);
        });

        // Group by section
        $bySection = $attendance->groupBy('section_id')->map(function ($sectionAttendance) {
            return $this->calculateAttendanceSummary($sectionAttendance);
        });

        // Group by date
        $byDate = $attendance->groupBy(function ($record) {
            return Carbon::parse($record->date)->format('Y-m-d');
        })->map(function ($dateAttendance) {
            return $this->calculateAttendanceSummary($dateAttendance);
        });

        return [
            'overall' => $summary,
            'by_class' => $byClass,
            'by_section' => $bySection,
            'by_date' => $byDate
        ];
    }
}