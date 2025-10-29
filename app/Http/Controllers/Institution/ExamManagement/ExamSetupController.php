<?php

namespace App\Http\Controllers\Institution\ExamManagement;

use App\Models\Exam;
use App\Models\Section;
use App\Models\Subject;
use App\Models\ExamType;
use App\Models\Institution;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ExamSetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        $institutions = Institution::all();
        $data   = $this->fetchData(auth()->user()->id);

        return view('institution.examination.exam.setup', compact('institutions', 'data'));
    }

    public function fetchData($institutionId)
    {
        $examTypes = ExamType::where('institution_id', $institutionId)->get(['id', 'title']);
        $classes = SchoolClass::where('institution_id', $institutionId)->get(['id', 'name']);

        return [
            'exam_types' => $examTypes,
            'classes' => $classes
        ];
    }

    public function fetchSubjects(Request $request)
    {
        // $institutionId = $request->input('institution_id');
        $institutionId = auth()->user()->id;
        $classId = $request->input('class_id');
        $subjects = Subject::where(['class_id' => $classId, 'institution_id' => $institutionId])->get(['id', 'name']);
        return response()->json(['subjects' => $subjects]);
    }

    public function fetchSections($classId)
    {
        try {
            $class = SchoolClass::find($classId);
            if (!$class) {
                return response()->json(['sections' => []]);
            }

            $sections = $class->sections()->get(['id', 'name']);
            return response()->json(['sections' => $sections]);
        } catch (\Exception $e) {
            \Log::error('Error in fetchSections: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch sections'], 500);
        }
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'institution_id' => 'required|exists:institutions,id',
        //     'exam_type' => 'required|exists:exam_types,id',
        //     'title' => 'required|string|max:255',
        //     'code' => 'required|string|max:255',
        //     'class_id' => 'nullable|exists:classes,id',
        //     'section_id' => 'nullable|exists:sections,id',
        //     'start_date' => 'required|date',
        //     'end_date' => 'required|date|after_or_equal:start_date',
        //     'morning_time' => 'nullable|date_format:H:i',
        //     'evening_time' => 'nullable|date_format:H:i',
        //     'subject_dates' => 'required|array',
        //     'morning_subjects' => 'required|array',
        //     'evening_subjects' => 'required|array',
        // ]);

        Exam::create([
            'institution_id' => auth('institution')->user()->id,
            'exam_type_id' => $request->exam_type,
            'title' => $request->title,
            'code' => $request->code,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'morning_time' => $request->morning_time,
            'evening_time' => $request->evening_time,
            'subject_dates' => json_encode($request->subject_dates),
            'morning_subjects' => json_encode($request->morning_subjects),
            'evening_subjects' => json_encode($request->evening_subjects),
        ]);

        return redirect()->back()->with('success', 'Exam and schedule saved successfully!');
    }
}
