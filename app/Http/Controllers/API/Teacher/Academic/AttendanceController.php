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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Filter attendance data by date range and return student attendance records
     */
    public function filterAttendance(Request $request)
    {
        try {
            
            // Get teacher by email
            $teacher = Teacher::where('email', $request->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            // Parse date range
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            if ($request->role === 'teacher') {
                // Get teacher attendance
                $attendanceRecords = Attendance::where('institution_id', $teacher->institution_id)
                    ->where('role', 'teacher')
                    ->where('user_id', $teacher->id)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->get()
                    ->keyBy(function ($record) {
                        return $record->date->format('Y-m-d');
                    });

                $teacherAttendance = [];
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $dateString = $currentDate->format('Y-m-d');
                    $record = $attendanceRecords->get($dateString);

                    if ($record) {
                        $teacherAttendance[] = [
                            'date' => $dateString,
                            'status' => $record->status,
                            'remarks' => $record->remarks,
                            'is_confirmed' => $record->is_confirmed,
                            'marked_at' => $record->created_at->format('Y-m-d H:i:s')
                        ];
                    } else {
                        $teacherAttendance[] = [
                            'date' => $dateString,
                            'status' => 'not_marked',
                            'remarks' => null,
                            'is_confirmed' => false,
                            'marked_at' => null
                        ];
                    }
                    $currentDate->addDay();
                }

                $totalDays = count($teacherAttendance);
                $presentDays = collect($teacherAttendance)->where('status', 'present')->count();
                $absentDays = collect($teacherAttendance)->where('status', 'absent')->count();
                $lateDays = collect($teacherAttendance)->where('status', 'late')->count();
                $notMarkedDays = collect($teacherAttendance)->where('status', 'not_marked')->count();

                $data = [
                    'teacher_name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'attendance' => $teacherAttendance,
                    'summary' => [
                        'total_days' => $totalDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'late_days' => $lateDays,
                        'not_marked_days' => $notMarkedDays,
                        'attendance_percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
                    ]
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Teacher attendance retrieved successfully',
                    'data' => $data
                ]);
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

            // Get all students in the class and section
            $students = Student::where('institution_id', $teacher->institution_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 1)
                ->orderBy('first_name')
                ->get();

            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found in this class and section',
                    'data' => []
                ], 404);
            }

            // Parse date range
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Get attendance records for the date range
            $attendanceRecords = Attendance::where('institution_id', $teacher->institution_id)
                ->where('role', 'student')
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get()
                ->groupBy('user_id')
                ->map(function ($userRecords) {
                    return $userRecords->keyBy(function ($record) {
                        return $record->date->format('Y-m-d');
                    });
                });

            // Prepare response data
            $studentsData = [];
            
            foreach ($students as $student) {
                $studentAttendance = [];
                
                // Generate all dates in the range
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $dateString = $currentDate->format('Y-m-d');
                    
                    // Check if attendance exists for this student on this date
                    $record = $attendanceRecords->get($student->id, collect())->get($dateString);
                    
                    if ($record) {
                        $studentAttendance[] = [
                            'date' => $dateString,
                            'status' => $record->status,
                            'remarks' => $record->remarks,
                            'is_confirmed' => $record->is_confirmed,
                            'marked_at' => $record->created_at->format('Y-m-d H:i:s')
                        ];
                    } else {
                        // No attendance record for this date
                        $studentAttendance[] = [
                            'date' => $dateString,
                            'status' => 'not_marked',
                            'remarks' => null,
                            'is_confirmed' => false,
                            'marked_at' => null
                        ];
                    }
                    
                    $currentDate->addDay();
                }

                // Calculate summary statistics
                $totalDays = count($studentAttendance);
                $presentDays = collect($studentAttendance)->where('status', 'present')->count();
                $absentDays = collect($studentAttendance)->where('status', 'absent')->count();
                $lateDays = collect($studentAttendance)->where('status', 'late')->count();
                $notMarkedDays = collect($studentAttendance)->where('status', 'not_marked')->count();

                $studentsData[] = [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_roll_no' => $student->roll_no,
                    'attendance' => $studentAttendance,
                    'summary' => [
                        'total_days' => $totalDays,
                        'present_days' => $presentDays,
                        'absent_days' => $absentDays,
                        'late_days' => $lateDays,
                        'not_marked_days' => $notMarkedDays,
                        'attendance_percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
                    ]
                ];
            }

            // Get class and section names
            $className = SchoolClass::find($request->class_id)->name;
            $sectionName = Section::find($request->section_id)->name;

            return response()->json([
                'success' => true,
                'message' => 'Attendance data retrieved successfully',
                'data' => [
                    'class_name' => $className,
                    'section_name' => $sectionName,
                    'date_range' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'total_days' => $startDate->diffInDays($endDate) + 1
                    ],
                    'total_students' => count($studentsData),
                    'students' => $studentsData
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
    public function getStudentsForAttendance(Request $request)
    {

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
             return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
        }

        $class = SchoolClass::find($request->class_id);
        if (!$class) {
             return response()->json([
                    'success' => false,
                    'message' => 'No class found with this ID',
                    'data' => []
                ], 404);
        }
        
        $section = Section::find($request->section_id);
        if (!$section) {
             return response()->json([
                    'success' => false,
                    'message' => 'No section found with this ID',
                    'data' => []
                ], 404);
        }

        $students = Student::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get([
                DB::raw("CONCAT_WS(' ', students.first_name, students.middle_name, students.last_name) as name"),
                'students.admission_number',
                'students.roll_number',
                'students.id as student_id'
            ]);

        $attendance_date = Carbon::parse($request->date)->format('Y-m-d');

        $students->each(function ($student) use ($attendance_date) {
            $attendance = Attendance::where('user_id', $student->student_id)
                ->where('date', $attendance_date)
                ->first();

            $student->status = $attendance ? $attendance->status : 'not_marked';
            $student->remarks = $attendance ? $attendance->remarks : '';
        });

        return response()->json([
            'success' => true,
            'message' => 'Attendance data retrieved successfully',
            'data' => $students
        ], 200);
    }
    public function markAttendanceStudent(Request $request)
    {

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
             return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);    
        }

        foreach ($request->attendance_data as $data) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $data['student_id'],
                    'date' => Carbon::parse($data['date'])->format('Y-m-d'),
                ],
                [
                    'role' => 'student',
                    'institution_id' => $teacher->institution_id,
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id'],
                    'status' => $data['status'],
                    'remarks' => $data['remarks'],
                    'marked_by' => $teacher->id,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => []
        ], 200);
    }
     public function markAttendanceTeacher(Request $request)
    {

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
             return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);    
        }
        

        Attendance::updateOrCreate(
            [
                'user_id' => $teacher->id,
                'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            ],
            [
                'role' => 'teacher',
                'institution_id' => $teacher->institution_id,
                'class_id' => $request['class_id'],
                'section_id' => $request['section_id'],
                'status' => $request['status'],
                'remarks' => $request['remarks'],
                'marked_by' => $teacher->institution_id,
                'marked_by_role' => 'teacher'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => []
        ], 200);
    }
}


