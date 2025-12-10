<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Institution;
use App\Models\ExamMark;
use App\Models\ExamType;
use Barryvdh\DomPDF\Facade\Pdf;

class MarksheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $institutions = Institution::all();
        $examTypes = collect();
        $classes = collect();
        $exams = collect();

        // If institution is selected, fetch exams, classes, and exam types with optional filters
        if ($request->filled('institution_id')) {
            $institutionId = $request->institution_id;

            $examQuery = Exam::where('institution_id', $institutionId);

            if ($request->filled('exam_type_id')) {
                $examQuery->where('exam_type_id', $request->exam_type_id);
            }

            if ($request->filled('month')) {
                $examQuery->where('month', (int) $request->month);
            }

            $exams = $examQuery->get();
            $classes = SchoolClass::where('institution_id', $institutionId)->get(['id', 'name']);
            $examTypes = ExamType::where('institution_id', $institutionId)->get(['id', 'title']);
        }

        return view('admin.examination.marksheet.index', compact('institutions', 'examTypes', 'classes', 'exams', 'request'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'institution_id' => 'required',
            'exam_type_id' => 'nullable',
            'month' => 'nullable|integer|min:1|max:12',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $institutionId = $request->institution_id;

        // Resolve marks from ExamMark (exam_id column stores exam_type_id)
        $examMarkQuery = ExamMark::query()
            ->where('institution_id', $institutionId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id);

        // Filter by exam type
        if ($request->filled('exam_type_id')) {
            $examMarkQuery->where('exam_id', $request->exam_type_id);
        }

        // Filter by month via matching exams (exam_type_id = exam_marks.exam_id)
        if ($request->filled('month')) {
            $examMarkQuery->whereExists(function ($sub) use ($request, $institutionId) {
                $sub->selectRaw(1)
                    ->from('exams')
                    ->whereColumn('exams.exam_type_id', 'exam_marks.exam_id')
                    ->where('exams.institution_id', $institutionId)
                    ->where('exams.class_id', $request->class_id)
                    ->where('exams.section_id', $request->section_id)
                    ->where('exams.month', (int) $request->month);
            });
        }

        $examMark = $examMarkQuery->orderByDesc('created_at')->first();

        if (!$examMark) {
            return back()
                ->withErrors(['exam_type_id' => 'No exam marks found for the selected filters.'])
                ->withInput();
        }

        // Get the exam using the exam_type_id stored in exam_marks.exam_id
        $exam = Exam::where('institution_id', $institutionId)
            ->where('exam_type_id', $examMark->exam_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->when($request->filled('month'), function ($q) use ($request) {
                $q->where('month', (int) $request->month);
            })
            ->orderByDesc('created_at')
            ->first();

        if (!$exam) {
            return back()
                ->withErrors(['exam_type_id' => 'No exam found for the selected filters.'])
                ->withInput();
        }

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get();

        $class = SchoolClass::find($request->class_id);
        $section = Section::find($request->section_id);
        $institutions = Institution::all();

        // Also fetch exams and classes for the form
        $examQuery = Exam::where('institution_id', $institutionId);

        if ($request->filled('exam_type_id')) {
            $examQuery->where('exam_type_id', $request->exam_type_id);
        }

        if ($request->filled('month')) {
            $examQuery->where('month', (int) $request->month);
        }

        $exams = $examQuery->get();
        $classes = SchoolClass::where('institution_id', $institutionId)->get();
        $examTypes = ExamType::where('institution_id', $institutionId)->get(['id', 'title']);

        return view('admin.examination.marksheet.index', compact('students', 'exam', 'class', 'section', 'request', 'institutions', 'exams', 'classes', 'examTypes'));
    }

    public function generatePdf($studentId, $examId)
    {
        $student = Student::findOrFail($studentId);
        $exam = Exam::findOrFail($examId);
        $institution = Institution::find($student->institution_id);

        // Fetch marks for this student and exam
        $marks = ExamMark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject'])
            ->get();

        $pdf = Pdf::loadView('admin.examination.marksheet.print', compact('student', 'exam', 'marks', 'institution'));

        return $pdf->download('Marksheet_' . $student->first_name . '_' . $exam->title . '.pdf');
    }


}

