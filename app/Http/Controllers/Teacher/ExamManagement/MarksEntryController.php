<?php

namespace App\Http\Controllers\Teacher\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ExamMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MarksEntryController extends Controller
{
    public function __construct(){ $this->middleware('auth:teacher'); }

    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $examTypes = ExamType::where('institution_id', $institutionId)->get(['id','title']);
        $assignedClassIds = \App\Models\AssignClassTeacher::where('institution_id', $institutionId)
            ->where('teacher_id', $teacher->id)
            ->where('status', 1)
            ->pluck('class_id')->filter()->unique()->values();
        $classes = SchoolClass::whereIn('id', $assignedClassIds)->get(['id','name']);
        return view('teacher.exam-management.marks-entry.index', compact('examTypes','classes'));
    }

    public function getSectionsByClass($classId)
    {
        $class = SchoolClass::find($classId);
        if (!$class) return response()->json(['success'=>false],404);
        $sections = Section::where('class_id',$classId)->where('institution_id',$class->institution_id)->where('status',1)->get(['id','name']);
        return response()->json(['success'=>true,'data'=>$sections]);
    }

    public function getSubjectsByClass($classId)
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $subjectIds = \App\Models\AssignSubject::where('institution_id',$institutionId)->where('teacher_id',$teacher->id)->where('class_id',$classId)->where('status',1)->pluck('subject_id');
        $subjects = Subject::whereIn('id',$subjectIds)->get(['id','name','code']);
        return response()->json(['success'=>true,'data'=>$subjects]);
    }

    public function getStudentsForEntry(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $v = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type_id' => 'nullable|exists:exam_types,id',
            'month' => 'nullable|integer|min:1|max:12',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $students = Student::where('institution_id',$institutionId)->where('class_id',$request->class_id)->where('section_id',$request->section_id)->orderBy('first_name')->get(['id','first_name','last_name','roll_number']);

        $existing = ExamMark::where('institution_id',$institutionId)->where('class_id',$request->class_id)->where('section_id',$request->section_id)->where('subject_id',$request->subject_id)->where('teacher_id',$teacher->id)->get()->keyBy('student_id');

        $rows = $students->map(function($s) use ($existing){
            $m = $existing->get($s->id);
            return [
                'student_id' => $s->id,
                'name' => trim(($s->first_name ?? '').' '.($s->last_name ?? '')),
                'roll_number' => $s->roll_number,
                'marks_obtained' => $m ? (float)$m->marks_obtained : null,
            ];
        });

        return response()->json(['success'=>true,'students'=>$rows]);
    }

    public function store(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $institutionId = $teacher->institution_id;
        $v = Validator::make($request->all(), [
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'total_marks' => 'required|numeric|min:1',
            'pass_marks' => 'required|numeric|min:0',
            'entries' => 'required|array',
            'entries.*.student_id' => 'required|exists:students,id',
            'entries.*.marks_obtained' => 'nullable|numeric|min:0',
        ]);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);

        $examId = null;
        if ($request->filled('exam_type_id') || $request->filled('month')) {
            $examId = Exam::where('institution_id',$institutionId)
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
                'institution_id' => $institutionId,
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

        return response()->json(['success'=>true,'summary'=>['pass'=>$passCount,'fail'=>$failCount]]);
    }
}
