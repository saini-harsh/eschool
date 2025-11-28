<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Models\Exam;
use App\Models\Section;
use App\Models\Subject;
use App\Models\ExamType;
use App\Models\Institution;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $institutions = Institution::all();
        return view('admin.examination.exam.setup', compact('institutions'));
    }

    public function fetchData(Request $request)
    {
        $institutionId = $request->input('institution_id');
        $examTypes = ExamType::where('institution_id', $institutionId)->get(['id', 'title']);
        $classes = SchoolClass::where('institution_id', $institutionId)->get(['id', 'name']);
        $sections = Section::all();

        return response()->json([
            'exam_types' => $examTypes,
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }

    public function fetchSubjects(Request $request)
    {
        $institutionId = $request->input('institution_id');
        $classId = $request->input('class_id');
        $subjects = Subject::where(['class_id'=> $classId,'institution_id'=>$institutionId])->get(['id', 'name']);
        return response()->json(['subjects' => $subjects]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'exam_type' => 'required|exists:exam_types,id',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exam_month' => 'required|integer|between:1,12',
            'morning_time' => 'nullable|date_format:H:i',
            'evening_time' => 'nullable|date_format:H:i',
            'subject_dates' => 'required|array',
            'morning_subjects' => 'required|array',
            'evening_subjects' => 'required|array',
        ]);

        Exam::create([
            'institution_id' => $request->institution_id,
            'exam_type_id' => $request->exam_type,
            'title' => $request->title,
            'code' => $request->code,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'month' => $request->exam_month,
            'morning_time' => $request->morning_time,
            'evening_time' => $request->evening_time,
            'subject_dates' => json_encode($request->subject_dates),
            'morning_subjects' => json_encode($request->morning_subjects),
            'evening_subjects' => json_encode($request->evening_subjects),
        ]);

        return redirect()->back()->with('success', 'Exam and schedule saved successfully!');
    }
}
