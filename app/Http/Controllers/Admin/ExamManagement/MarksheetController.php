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
        $exams = collect();
        $classes = collect();

        // If institution is selected, fetch exams and classes
        if ($request->filled('institution_id')) {
            $institutionId = $request->institution_id;
            $exams = Exam::where('institution_id', $institutionId)->get();
            $classes = SchoolClass::where('institution_id', $institutionId)->get();
        }

        return view('admin.examination.marksheet.index', compact('institutions', 'exams', 'classes'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'institution_id' => 'required',
            'exam_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $institutionId = $request->institution_id;

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get();

        $exam = Exam::find($request->exam_id);
        $class = SchoolClass::find($request->class_id);
        $section = Section::find($request->section_id);
        $institutions = Institution::all();

        // Also fetch exams and classes for the form
        $exams = Exam::where('institution_id', $institutionId)->get();
        $classes = SchoolClass::where('institution_id', $institutionId)->get();

        return view('admin.examination.marksheet.index', compact('students', 'exam', 'class', 'section', 'request', 'institutions', 'exams', 'classes'));
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

