<?php

namespace App\Http\Controllers\Teacher\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Invigilator;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamRoutineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;

        $examTypes = ExamType::where('institution_id', $institutionId)->get(['id','title']);

        $assignedClassIds = \App\Models\AssignClassTeacher::where('institution_id', $institutionId)
            ->where('teacher_id', $teacher->id)
            ->where('status', 1)
            ->pluck('class_id')
            ->filter()
            ->unique()
            ->values();
        $classes = SchoolClass::whereIn('id', $assignedClassIds)->get(['id','name']);

        return view('teacher.exam-management.exam-routine.index', compact('examTypes','classes'));
    }

    public function getSectionsByClass($classId)
    {
        try {
            $class = SchoolClass::find($classId);
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class not found'], 404);
            }
            $sections = Section::where('class_id', $classId)
                ->where('institution_id', $class->institution_id)
                ->where('status', 1)
                ->get(['id','name']);
            return response()->json(['success' => true, 'data' => $sections]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching sections'], 500);
        }
    }

    public function getExamRoutineReport(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'exam_type_id' => 'nullable|exists:exam_types,id',
            'month' => 'nullable|integer|min:1|max:12',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $query = Exam::with(['examType'])
            ->where('institution_id', $institutionId);
        if ($request->filled('exam_type_id')) {
            $query->where('exam_type_id', $request->exam_type_id);
        }
        if ($request->filled('month')) {
            $query->where('month', (int)$request->month);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $exams = $query->orderBy('start_date','asc')->get();

        $subjectIds = [];
        foreach ($exams as $exam) {
            $morning = is_array($exam->morning_subjects) ? $exam->morning_subjects : (json_decode($exam->morning_subjects, true) ?: []);
            $evening = is_array($exam->evening_subjects) ? $exam->evening_subjects : (json_decode($exam->evening_subjects, true) ?: []);
            foreach ($morning as $sid) { if ($sid) $subjectIds[] = (int)$sid; }
            foreach ($evening as $sid) { if ($sid) $subjectIds[] = (int)$sid; }
        }
        $subjects = Subject::whereIn('id', array_unique($subjectIds))->get(['id','name'])->keyBy('id');

        $schedules = [];
        foreach ($exams as $exam) {
            $dates = is_array($exam->subject_dates) ? $exam->subject_dates : (json_decode($exam->subject_dates, true) ?: []);
            $morning = is_array($exam->morning_subjects) ? $exam->morning_subjects : (json_decode($exam->morning_subjects, true) ?: []);
            $evening = is_array($exam->evening_subjects) ? $exam->evening_subjects : (json_decode($exam->evening_subjects, true) ?: []);

            foreach ($dates as $idx => $dateStr) {
                $mSubId = $morning[$idx] ?? null;
                $eSubId = $evening[$idx] ?? null;
                $mName = $mSubId ? ($subjects[$mSubId]->name ?? 'Subject') : '';
                $eName = $eSubId ? ($subjects[$eSubId]->name ?? 'Subject') : '';

                $invigs = Invigilator::with(['classRoom'])
                    ->where('institution_id', $institutionId)
                    ->where('exam_id', $exam->id)
                    ->where('date', $dateStr)
                    ->where('teacher_id', $teacher->id)
                    ->get(['id','class_room_id']);

                $rooms = $invigs->map(function($row){
                    $room = $row->classRoom;
                    return [
                        'room_no' => $room->room_no ?? '',
                        'room_name' => $room->room_name ?? '',
                    ];
                });

                $schedules[] = [
                    'exam' => [
                        'id' => $exam->id,
                        'title' => $exam->title ?? '',
                        'type' => optional($exam->examType)->title,
                    ],
                    'date' => $dateStr,
                    'morning_time' => $exam->morning_time,
                    'evening_time' => $exam->evening_time,
                    'morning_subject' => $mName,
                    'evening_subject' => $eName,
                    'invigilator_rooms' => $rooms,
                ];
            }
        }

        return response()->json(['success' => true, 'data' => $schedules]);
    }
}
