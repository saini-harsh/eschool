<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\StudentAssignment;
use App\Models\Routine;
use App\Models\AcademicEvent;
use App\Models\Attendance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        return view('student.index', compact('student'));
    }

    public function dashboardData()
    {
        $student = Auth::guard('student')->user();
        $institutionId = $student->institution_id;
        $classId = (int) $student->class_id;
        $sectionId = (int) $student->section_id;
        $today = Carbon::today();
        $now = Carbon::now();

        $assignmentsTotal = Assignment::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 1)
            ->count();
        $submissionsDone = StudentAssignment::where('student_id', $student->id)->count();
        $pendingAssignments = max($assignmentsTotal - $submissionsDone, 0);

        $subjectsCount = Subject::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('status', 1)
            ->count();

        $routineCount = Routine::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 1)
            ->count();

        $stats = [
            'assignmentsTotal' => $assignmentsTotal,
            'pendingAssignments' => $pendingAssignments,
            'subjects' => $subjectsCount,
            'routines' => $routineCount,
        ];

        $sessionsBySubject = Routine::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', 1)
            ->selectRaw('subject_id, COUNT(*) as total')
            ->groupBy('subject_id')
            ->get();
        $subjectNames = \App\Models\Subject::whereIn('id', $sessionsBySubject->pluck('subject_id')->filter()->unique()->values())
            ->get(['id','name'])
            ->keyBy('id');
        $schedulePolar = [
            'labels' => $sessionsBySubject->map(function ($row) use ($subjectNames) { return optional($subjectNames->get($row->subject_id))->name ?? 'Unknown'; })->toArray(),
            'series' => $sessionsBySubject->pluck('total')->toArray(),
        ];

        $start = $now->copy()->subDays(6)->startOfDay();
        $dateKeys = collect(range(0,6))->map(function ($i) use ($start) { return $start->copy()->addDays($i)->format('Y-m-d'); });
        $assignmentRows = Assignment::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereBetween('created_at', [$start, $now])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->get()
            ->pluck('c', 'd');
        $assignmentsTrend = [
            'labels' => $dateKeys->map(function ($d) { return Carbon::parse($d)->format('d M'); })->toArray(),
            'series' => $dateKeys->map(function ($d) use ($assignmentRows) { return (int) ($assignmentRows[$d] ?? 0); })->toArray(),
        ];

        $startAtt = $now->copy()->subDays(29)->startOfDay();
        $attRows = Attendance::where('institution_id', $institutionId)
            ->where('role', 'student')
            ->where('user_id', $student->id)
            ->whereBetween('date', [$startAtt, $today])
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->get()
            ->pluck('c','status');
        $attendanceSummary = [
            'present' => (int) ($attRows['present'] ?? 0),
            'absent' => (int) ($attRows['absent'] ?? 0),
            'late' => (int) ($attRows['late'] ?? 0),
        ];

        $upcomingEvents = AcademicEvent::where('institution_id', $institutionId)
            ->where('status', 1)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get(['id','title','start_date'])
            ->map(function ($e) { return ['id'=>$e->id,'title'=>$e->title ?? 'Event','start_date'=>$e->start_date,'start_date_formatted'=>$e->start_date ? Carbon::parse($e->start_date)->format('d M Y') : '' ]; });

        $recentSubmissions = StudentAssignment::where('student_id', $student->id)
            ->orderBy('submission_date','desc')
            ->limit(5)
            ->get(['id','assignment_id','submission_date','status']);

        $activities = collect([])
            ->merge($recentSubmissions->map(function ($s) { return ['type' => 'Submission', 'text' => '#'.$s->assignment_id, 'time' => $s->submission_date]; }))
            ->merge($upcomingEvents->map(function ($e) { return ['type' => 'Event', 'text' => $e['title'], 'time' => $e['start_date']]; }))
            ->sortByDesc('time')->values()
            ->map(function ($row) { return ['type'=>$row['type'],'text'=>$row['text'],'time'=>$row['time'],'time_formatted'=>$row['time'] ? Carbon::parse($row['time'])->diffForHumans() : '' ]; })
            ->take(10);

        return response()->json([
            'stats' => $stats,
            'schedulePolar' => $schedulePolar,
            'assignmentsTrend' => $assignmentsTrend,
            'attendanceSummary' => $attendanceSummary,
            'upcomingEvents' => $upcomingEvents,
            'recentActivities' => $activities,
        ]);
    }

    public function printIdCard()
    {
        $authStudent = Auth::guard('student')->user();

        $student = Student::with([
            'institution:id,name,logo,address,email,phone,website,board,district,state,pincode',
            'schoolClass:id,name',
            'section:id,name'
        ])->findOrFail($authStudent->id);

        $primaryColor = '#6366f1';
        $secondaryColor = '#0d6efd';

        $pdf = Pdf::loadView('admin.administration.students.id-card', [
            'student' => $student,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
        ])->setPaper('a4');

        $fileName = 'My_ID_Card_' . ($student->student_id ?? $student->id) . '.pdf';
        return $pdf->stream($fileName);
    }
}
