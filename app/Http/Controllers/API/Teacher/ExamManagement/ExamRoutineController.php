<?php

namespace App\Http\Controllers\API\Teacher\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\AssignClassTeacher;
use App\Models\ExamType;
use App\Models\Invigilator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamRoutineController extends Controller
{
    public function examTypes(Request $request)
    {
        $teacher = Teacher::where('email',$request->email)->first();
        if (!$teacher) return response()->json(['success'=>false,'message'=>'Teacher not found','data'=>[]],404);
        $types = ExamType::where('institution_id',$teacher->institution_id)->orderBy('title')->get(['id','title','code']);
        return response()->json(['success'=>true,'message'=>'Exam types fetched successfully','data'=>$types]); 
    }
    public function report(Request $request)
    {

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) return response()->json(['success'=>false,'message'=>'Teacher not found','data'=>[]],404);

        $assigned = AssignClassTeacher::where('teacher_id',$teacher->id)->where('status',1)->get();
        $assignedClassIds = $assigned->pluck('class_id')->unique()->values();
        $assignedSectionIds = $assigned->pluck('section_id')->unique()->values();

        $query = Exam::with(['examType'])
            ->where('institution_id',$teacher->institution_id)
            ->whereIn('class_id',$assignedClassIds)
            ->whereIn('section_id',$assignedSectionIds);
        if ($request->filled('exam_type_id')) $query->where('exam_type_id',$request->exam_type_id);
        if ($request->filled('month')) $query->where('month',(int)$request->month);
        if ($request->filled('class_id')) $query->where('class_id',(int)$request->class_id);
        if ($request->filled('section_id')) $query->where('section_id',(int)$request->section_id);

        $exams = $query->orderBy('start_date','asc')->get();
        $schedules = [];
        foreach ($exams as $exam) {
            $dates = is_array($exam->subject_dates) ? $exam->subject_dates : (json_decode($exam->subject_dates, true) ?: []);
            $morning = is_array($exam->morning_subjects) ? $exam->morning_subjects : (json_decode($exam->morning_subjects, true) ?: []);
            $evening = is_array($exam->evening_subjects) ? $exam->evening_subjects : (json_decode($exam->evening_subjects, true) ?: []);
            $subjectIds = array_values(array_unique(array_filter(array_merge($morning, $evening))));
            $subjects = $subjectIds ? Subject::whereIn('id', $subjectIds)->get(['id','name'])->keyBy('id') : collect();
            $invigs = Invigilator::where('exam_id',$exam->id)->where('teacher_id',$teacher->id)->get();
            $rooms = $invigs->map(function($r){
                return ['room_no'=>$r->classRoom->room_no ?? '', 'room_name'=>$r->classRoom->room_name ?? ''];
            })->values();
            foreach ($dates as $i => $dateStr) {
                $mId = $morning[$i] ?? null; $eId = $evening[$i] ?? null;
                $mName = $mId && $subjects->has($mId) ? $subjects[$mId]->name : null;
                $eName = $eId && $subjects->has($eId) ? $subjects[$eId]->name : null;
                $schedules[] = [
                    'exam' => [ 'id'=>$exam->id, 'title'=>$exam->title ?? '', 'type' => optional($exam->examType)->title ],
                    'date' => $dateStr,
                    'morning_time' => $exam->morning_time,
                    'evening_time' => $exam->evening_time,
                    'morning_subject' => $mName,
                    'evening_subject' => $eName,
                    'invigilator_rooms' => $rooms,
                ];
            }
        }

        return response()->json(['success'=>true,'message'=>'Exam routine fetched successfully','data'=>$schedules]);
    }
}
