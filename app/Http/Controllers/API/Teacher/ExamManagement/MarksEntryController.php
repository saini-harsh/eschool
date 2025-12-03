<?php

namespace App\Http\Controllers\API\Teacher\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\AssignSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AssignClassTeacher;

class MarksEntryController extends Controller
{
    public function subjects(Request $request)
    {
        $teacher = Teacher::where('email',$request->email)->first();
        if (!$teacher) return response()->json(['success'=>false,'message'=>'Teacher not found','data'=>[]],404);
        $assclass = AssignClassTeacher::where('id',$teacher->id)->first();
        if (!$assclass) return response()->json(['success'=>false,'message'=>'Class not found','data'=>[]],404);
        
        $subjectIds = AssignSubject::where('institution_id',$teacher->institution_id)->where('teacher_id',$teacher->id)->where('class_id',$assclass->class_id)->where('status',1)->pluck('subject_id');
        $subjects = Subject::whereIn('id',$subjectIds)->orderBy('name')->get(['id','name','code']);
        return response()->json(['success'=>true,'message'=>'Subjects fetched successfully','data'=>$subjects]);
    }
    public function students(Request $request)
    {
        
        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) return response()->json(['success'=>false,'message'=>'Teacher not found','data'=>[]],404);

        $students = Student::where('institution_id',$teacher->institution_id)
            ->where('class_id',$request->class_id)->where('section_id',$request->section_id)
            ->orderBy('first_name')->get(['id','first_name','last_name','roll_number']);

        $existing = ExamMark::where('institution_id',$teacher->institution_id)
            ->where('class_id',$request->class_id)->where('section_id',$request->section_id)
            ->where('subject_id',$request->subject_id)->where('teacher_id',$teacher->id)
            ->get()->keyBy('student_id');

        $rows = $students->map(function($s) use ($existing){
            $m = $existing->get($s->id);
            return [
                'student_id' => $s->id,
                'name' => trim(($s->first_name ?? '').' '.($s->last_name ?? '')),
                'roll_number' => $s->roll_number,
                'marks_obtained' => $m ? (float)$m->marks_obtained : null,
            ];
        })->values();

        return response()->json(['success'=>true,'message'=>'Students fetched successfully','data'=>$rows]);
    }

    public function store(Request $request)
    {
       
        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) return response()->json(['success'=>false,'message'=>'Teacher not found','data'=>[]],404);

        $examId = null;
        if ($request->filled('exam_type_id') || $request->filled('month')) {
            $examId = Exam::where('institution_id',$teacher->institution_id)
                ->when($request->filled('exam_type_id'), function($q) use ($request){ $q->where('exam_type_id',$request->exam_type_id); })
                ->when($request->filled('month'), function($q) use ($request){ $q->where('month',(int)$request->month); })
                ->where('class_id',$request->class_id)
                ->where('section_id',$request->section_id)
                ->orderBy('start_date','desc')
                ->value('id');
        }

        $passCount = 0; $failCount = 0;
        foreach ($request->entries as $row) {
            $obt = isset($row['marks_obtained']) ? (float)$row['marks_obtained'] : 0.0;
            if ($obt >= (float)$request->pass_marks) $passCount++; else $failCount++;
            ExamMark::updateOrCreate([
                'institution_id' => $teacher->institution_id,
                'exam_id' => $examId,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'student_id' => $row['student_id'],
                'teacher_id' => $teacher->id,
            ],[
                'marks_obtained' => $obt,
                'total_marks' => (float)$request->total_marks,
                'pass_marks' => (float)$request->pass_marks,
            ]);
        }

        return response()->json(['success'=>true,'message'=>'Marks updated successfully','data'=>['pass'=>$passCount,'fail'=>$failCount]]);
    }
}
