<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\AssignClassTeacher;
use App\Models\AssignSubject;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\AcademicEvent;
use App\Models\Attendance;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $teacherId = $teacher->id;

        $assignClassRows = AssignClassTeacher::where('institution_id', $institutionId)
            ->where('teacher_id', $teacherId)
            ->where('status', 1)
            ->get(['class_id','section_id']);
        $classIds = $assignClassRows->pluck('class_id')->filter()->unique()->values();
        $sectionIds = $assignClassRows->pluck('section_id')->filter()->unique()->values();

        $assignSubjectRows = AssignSubject::where('institution_id', $institutionId)
            ->where('teacher_id', $teacherId)
            ->where('status', 1)
            ->get(['subject_id']);
        $subjectIds = $assignSubjectRows->pluck('subject_id')->filter()->unique()->values();

        $studentsCountQuery = Student::where('institution_id', $institutionId)
            ->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })
            ->when($sectionIds->isNotEmpty(), function($q) use ($sectionIds) { $q->whereIn('section_id', $sectionIds); });

        $stats = [
            'students' => $studentsCountQuery->count(),
            'classes' => $classIds->count(),
            'sections' => $sectionIds->count(),
            'subjects' => $subjectIds->count(),
            'assignments' => Assignment::where('institution_id', $institutionId)->where('teacher_id', $teacherId)->count(),
            'dateToday' => Carbon::now()->format('d M Y'),
        ];

        return view('teacher.index', compact('teacher','stats'));
    }

    public function dashboardData()
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $teacherId = $teacher->id;
        $today = Carbon::today();
        $now = Carbon::now();

        $assignClassRows = AssignClassTeacher::where('institution_id', $institutionId)
            ->where('teacher_id', $teacherId)
            ->where('status', 1)
            ->get(['class_id','section_id']);
        $classIds = $assignClassRows->pluck('class_id')->filter()->unique()->values();
        $sectionIds = $assignClassRows->pluck('section_id')->filter()->unique()->values();

        $assignSubjectRows = AssignSubject::where('institution_id', $institutionId)
            ->where('teacher_id', $teacherId)
            ->where('status', 1)
            ->get(['subject_id']);
        $subjectIds = $assignSubjectRows->pluck('subject_id')->filter()->unique()->values();

        $studentsCountQuery = Student::where('institution_id', $institutionId)
            ->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })
            ->when($sectionIds->isNotEmpty(), function($q) use ($sectionIds) { $q->whereIn('section_id', $sectionIds); });

        $stats = [
            'students' => $studentsCountQuery->count(),
            'classes' => $classIds->count(),
            'sections' => $sectionIds->count(),
            'subjects' => $subjectIds->count(),
            'assignments' => Assignment::where('institution_id', $institutionId)->where('teacher_id', $teacherId)->count(),
        ];

        $studentsByClass = Student::where('institution_id', $institutionId)
            ->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })
            ->selectRaw('class_id, COUNT(*) as total')
            ->groupBy('class_id')
            ->get();
        $classNames = SchoolClass::whereIn('id', $studentsByClass->pluck('class_id')->filter()->unique()->values())
            ->get(['id','name'])->keyBy('id');
        $studentsPerClass = [
            'labels' => $studentsByClass->map(function ($row) use ($classNames) { return optional($classNames->get($row->class_id))->name ?? 'Unknown'; })->toArray(),
            'series' => $studentsByClass->pluck('total')->toArray(),
        ];

        $start = $now->copy()->subDays(6)->startOfDay();
        $dateKeys = collect(range(0,6))->map(function ($i) use ($start) { return $start->copy()->addDays($i)->format('Y-m-d'); });
        $assignmentRows = Assignment::where('institution_id', $institutionId)
            ->where('teacher_id', $teacherId)
            ->whereBetween('created_at', [$start, $now])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->get()
            ->pluck('c', 'd');
        $assignmentsTrend = [
            'labels' => $dateKeys->map(function ($d) { return Carbon::parse($d)->format('d M'); })->toArray(),
            'series' => $dateKeys->map(function ($d) use ($assignmentRows) { return (int) ($assignmentRows[$d] ?? 0); })->toArray(),
        ];

        $attendanceToday = [
            'teacher' => [
                'present' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'teacher')->where('status', 'present')->count(),
                'absent' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'teacher')->where('status', 'absent')->count(),
                'late' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'teacher')->where('status', 'late')->count(),
            ],
            'student' => [
                'present' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'student')->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })->where('status', 'present')->count(),
                'absent' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'student')->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })->where('status', 'absent')->count(),
                'late' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', 'student')->when($classIds->isNotEmpty(), function($q) use ($classIds) { $q->whereIn('class_id', $classIds); })->where('status', 'late')->count(),
            ],
        ];

        $upcomingEvents = AcademicEvent::where('institution_id', $institutionId)
            ->where('status', 1)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get(['id','title','start_date'])
            ->map(function ($e) { return ['id'=>$e->id,'title'=>$e->title ?? 'Event','start_date'=>$e->start_date,'start_date_formatted'=>$e->start_date ? Carbon::parse($e->start_date)->format('d M Y') : '' ]; });

        $recentStudents = Student::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->limit(5)->get(['id','first_name','last_name','created_at']);
        $recentAssignments = Assignment::where('institution_id', $institutionId)->where('teacher_id',$teacherId)->orderBy('created_at', 'desc')->limit(5)->get(['id','title','created_at']);

        $activities = collect([])
            ->merge($recentStudents->map(function ($s) { return ['type' => 'Student', 'text' => trim(($s->first_name ?? '').' '.($s->last_name ?? '')), 'time' => $s->created_at]; }))
            ->merge($recentAssignments->map(function ($a) { return ['type' => 'Assignment', 'text' => $a->title ?? ('#'.$a->id), 'time' => $a->created_at]; }))
            ->merge($upcomingEvents->map(function ($e) { return ['type' => 'Event', 'text' => $e['title'], 'time' => $e['start_date']]; }))
            ->sortByDesc('time')->values()
            ->map(function ($row) { return ['type'=>$row['type'],'text'=>$row['text'],'time'=>$row['time'],'time_formatted'=>$row['time'] ? Carbon::parse($row['time'])->diffForHumans() : '' ]; })
            ->take(10);

        return response()->json([
            'stats' => $stats,
            'studentsPerClass' => $studentsPerClass,
            'assignmentsTrend' => $assignmentsTrend,
            'attendanceToday' => $attendanceToday,
            'upcomingEvents' => $upcomingEvents,
            'recentActivities' => $activities,
        ]);
    }
}
