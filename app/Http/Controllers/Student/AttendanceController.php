<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student = auth('student')->user();
        return view('student.attendance.index', compact('student'));
    }

    public function getMyAttendanceMatrix(Request $request)
    {
        $student = auth('student')->user();
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

        // Get attendance records for the student
        $attendanceRecords = Attendance::where('user_id', $student->id)
            ->where('role', 'student')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        // Build attendance data for each date
        $attendance = [];
        foreach ($dates as $date) {
            $attendanceRecord = $attendanceRecords->filter(function ($record) use ($date) {
                return \Carbon\Carbon::parse($record->date)->format('Y-m-d') === $date;
            })->first();

            if ($attendanceRecord) {
                $attendance[$date] = [
                    'status' => $attendanceRecord->status,
                    'is_confirmed' => $attendanceRecord->is_confirmed,
                    'marked_by_role' => $attendanceRecord->marked_by_role,
                    'attendance_id' => $attendanceRecord->id,
                    'remarks' => $attendanceRecord->remarks
                ];
            } else {
                $attendance[$date] = null;
            }
        }

        return response()->json([
            'dates' => $dates,
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'roll_number' => $student->roll_number,
                'admission_number' => $student->admission_number
            ],
            'attendance' => $attendance,
            'from_date' => $startDate->format('d M, Y'),
            'to_date' => $endDate->format('d M, Y')
        ]);
    }

    public function getAttendanceStats(Request $request)
    {
        $student = auth('student')->user();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        // Get date range
        $startDate = $fromDate ? \Carbon\Carbon::createFromFormat('d M, Y', $fromDate) : \Carbon\Carbon::now()->startOfMonth();
        $endDate = $toDate ? \Carbon\Carbon::createFromFormat('d M, Y', $toDate) : \Carbon\Carbon::now()->endOfMonth();

        $query = Attendance::where('user_id', $student->id)
            ->where('role', 'student')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        $records = $query->get();

        $totalDays = $records->count();
        $presentDays = $records->where('status', 'present')->count();
        $absentDays = $records->where('status', 'absent')->count();
        $lateDays = $records->where('status', 'late')->count();
        $excusedDays = $records->where('status', 'excused')->count();
        
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        return response()->json([
            'totalDays' => $totalDays,
            'presentDays' => $presentDays,
            'absentDays' => $absentDays,
            'lateDays' => $lateDays,
            'excusedDays' => $excusedDays,
            'attendancePercentage' => $attendancePercentage
        ]);
    }
}
