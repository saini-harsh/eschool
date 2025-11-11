<?php

namespace App\Http\Controllers\API\Student\Academic;

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
            // Validate request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                // 'role' => 'required|in:student,teacher',
                // 'class_id' => 'required_if:role,student|exists:classes,id',
                // 'section_id' => 'required_if:role,student|exists:sections,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get teacher by email
            $student = Student::where('email', $request->email)->first();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found with this email',
                    'data' => []
                ], 404);
            }

            // Parse date range
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);


            // Check if teacher is assigned to this class
            $isAssigned = AssignClassTeacher::where('teacher_id', $student->teacher_id)
                ->where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this class'
                ], 403);
            }

            // Get all students in the class and section
            $students = Student::where('institution_id', $student->institution_id)
                ->where('email', $request->email)
                ->where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
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
            $attendanceRecords = Attendance::where('institution_id', $student->institution_id)
                ->where('role', 'student')
                ->where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
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
            $className = SchoolClass::find($student->class_id)->name;
            $sectionName = Section::find($student->section_id)->name;

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
}


