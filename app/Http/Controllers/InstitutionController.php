<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\AcademicEvent;
use App\Models\Attendance;
use Carbon\Carbon;

class InstitutionController extends Controller
{
    public function dashboard()
    {
        $institution = Auth::guard('institution')->user();
        $institutionId = $institution->id;

        $stats = [
            'students' => \App\Models\Student::where('institution_id', $institutionId)->count(),
            'teachers' => \App\Models\Teacher::where('institution_id', $institutionId)->count(),
            'classes' => \App\Models\SchoolClass::where('institution_id', $institutionId)->count(),
            'sections' => \App\Models\Section::where('institution_id', $institutionId)->count(),
            'subjects' => \App\Models\Subject::where('institution_id', $institutionId)->count(),
            'assignments' => \App\Models\Assignment::where('institution_id', $institutionId)->count(),
            'dateToday' => \Carbon\Carbon::now()->format('d M Y'),
        ];

        return view('institution.index', compact('institution', 'stats'));
    }

    public function dashboardData()
    {
        $institution = Auth::guard('institution')->user();
        $institutionId = $institution->id;
        $today = Carbon::today();
        $now = Carbon::now();

        $stats = [
            'students' => Student::where('institution_id', $institutionId)->count(),
            'teachers' => Teacher::where('institution_id', $institutionId)->count(),
            'classes' => SchoolClass::where('institution_id', $institutionId)->count(),
            'sections' => Section::where('institution_id', $institutionId)->count(),
            'subjects' => Subject::where('institution_id', $institutionId)->count(),
            'assignments' => Assignment::where('institution_id', $institutionId)->count(),
        ];

        $studentsByClass = Student::where('institution_id', $institutionId)
            ->selectRaw('class_id, COUNT(*) as total')
            ->groupBy('class_id')
            ->get();
        $classIds = $studentsByClass->pluck('class_id')->filter()->unique();
        $classes = SchoolClass::whereIn('id', $classIds)->get(['id','name'])->keyBy('id');
        $studentsPerClass = [
            'labels' => $studentsByClass->map(function ($row) use ($classes) {
                return optional($classes->get($row->class_id))->name ?? 'Unknown';
            })->toArray(),
            'series' => $studentsByClass->pluck('total')->toArray(),
        ];

        $start = $now->copy()->subDays(6)->startOfDay();
        $dateKeys = collect(range(0,6))->map(function ($i) use ($start) {
            return $start->copy()->addDays($i)->format('Y-m-d');
        });
        $assignmentRows = Assignment::where('institution_id', $institutionId)
            ->whereBetween('created_at', [$start, $now])
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->get()
            ->pluck('c', 'd');
        $assignmentsTrend = [
            'labels' => $dateKeys->map(function ($d) { return Carbon::parse($d)->format('d M'); })->toArray(),
            'series' => $dateKeys->map(function ($d) use ($assignmentRows) { return (int) ($assignmentRows[$d] ?? 0); })->toArray(),
        ];

        $roles = ['student','teacher','nonworkingstaff'];
        $attendanceToday = [];
        foreach ($roles as $role) {
            $attendanceToday[$role] = [
                'present' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', $role)->where('status', 'present')->count(),
                'absent' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', $role)->where('status', 'absent')->count(),
                'late' => Attendance::where('institution_id', $institutionId)->whereDate('date', $today)->where('role', $role)->where('status', 'late')->count(),
            ];
        }

        $upcomingEvents = AcademicEvent::where('institution_id', $institutionId)
            ->where('status', 1)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get(['id','title','start_date'])
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title ?? 'Event',
                    'start_date' => $e->start_date,
                    'start_date_formatted' => $e->start_date ? Carbon::parse($e->start_date)->format('d M Y') : '',
                ];
            });

        $recentStudents = Student::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->limit(5)->get(['id','first_name','last_name','created_at']);
        $recentTeachers = Teacher::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->limit(5)->get(['id','first_name','last_name','created_at']);
        $recentAssignments = Assignment::where('institution_id', $institutionId)->orderBy('created_at', 'desc')->limit(5)->get(['id','title','created_at']);

        $activities = collect([])
            ->merge($recentStudents->map(function ($s) { return ['type' => 'Student', 'text' => trim(($s->first_name ?? '').' '.($s->last_name ?? '')), 'time' => $s->created_at]; }))
            ->merge($recentTeachers->map(function ($t) { return ['type' => 'Teacher', 'text' => trim(($t->first_name ?? '').' '.($t->last_name ?? '')), 'time' => $t->created_at]; }))
            ->merge($recentAssignments->map(function ($a) { return ['type' => 'Assignment', 'text' => $a->title ?? ('#'.$a->id), 'time' => $a->created_at]; }))
            ->merge($upcomingEvents->map(function ($e) { return ['type' => 'Event', 'text' => $e['title'], 'time' => $e['start_date']]; }))
            ->sortByDesc('time')
            ->values()
            ->map(function ($row) {
                return [
                    'type' => $row['type'],
                    'text' => $row['text'],
                    'time' => $row['time'],
                    'time_formatted' => $row['time'] ? Carbon::parse($row['time'])->diffForHumans() : '',
                ];
            })
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
