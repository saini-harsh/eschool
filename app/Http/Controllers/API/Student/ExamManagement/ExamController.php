<?php

namespace App\Http\Controllers\API\Student\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function report(Request $request)
    {
        
        $student = Student::where('email',$request->email)->first();
        if (!$student) return response()->json(['success'=>false,'message'=>'Student not found','data'=>[]],404);

        $query = Exam::with('examType')
            ->where('institution_id',$student->institution_id)
            ->where('class_id',$student->class_id)
            ->where('section_id',$student->section_id);
        if ($request->filled('month')) $query->where('month',(int)$request->month);

        $exams = $query->orderBy('start_date','asc')->get();
        $schedule = [];
        foreach ($exams as $exam) {
            $dates = is_array($exam->subject_dates) ? $exam->subject_dates : (json_decode($exam->subject_dates, true) ?: []);
            $morning = is_array($exam->morning_subjects) ? $exam->morning_subjects : (json_decode($exam->morning_subjects, true) ?: []);
            $evening = is_array($exam->evening_subjects) ? $exam->evening_subjects : (json_decode($exam->evening_subjects, true) ?: []);

            $subjectIds = array_values(array_unique(array_filter(array_merge($morning, $evening))));
            $subjects = $subjectIds ? Subject::whereIn('id', $subjectIds)->get(['id','name'])->keyBy('id') : collect();

            foreach ($dates as $i => $dateStr) {
                $mId = $morning[$i] ?? null; $eId = $evening[$i] ?? null;
                $mName = $mId && $subjects->has($mId) ? $subjects[$mId]->name : null;
                $eName = $eId && $subjects->has($eId) ? $subjects[$eId]->name : null;
                $schedule[] = [
                    'exam' => [ 'title' => $exam->title ?? '', 'type' => optional($exam->examType)->title ],
                    'date' => $dateStr,
                    'morning_time' => $exam->morning_time,
                    'evening_time' => $exam->evening_time,
                    'morning_subject' => $mName,
                    'evening_subject' => $eName,
                ];
            }
        }

        return response()->json(['success'=>true,'message'=>'Exams schedule fetched successfully','data'=>$schedule]);
    }
}

