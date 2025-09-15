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
        
        // Get current month's attendance records
        $currentMonth = Carbon::now()->format('Y-m');
        $attendanceRecords = Attendance::where('user_id', $student->id)
            ->where('role', 'student')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate attendance statistics
        $totalDays = $attendanceRecords->count();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $absentDays = $attendanceRecords->where('status', 'absent')->count();
        $lateDays = $attendanceRecords->where('status', 'late')->count();
        $excusedDays = $attendanceRecords->where('status', 'excused')->count();
        
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        return view('student.attendance.index', compact(
            'attendanceRecords', 
            'totalDays', 
            'presentDays', 
            'absentDays', 
            'lateDays', 
            'excusedDays', 
            'attendancePercentage'
        ));
    }

    public function filter(Request $request)
    {
        $student = auth('student')->user();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Attendance::where('user_id', $student->id)
            ->where('role', 'student');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $records = $query->orderBy('date', 'desc')->get();

        return response()->json($records);
    }

    public function getAttendanceStats(Request $request)
    {
        $student = auth('student')->user();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Attendance::where('user_id', $student->id)
            ->where('role', 'student');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

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
