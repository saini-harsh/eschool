<?php

namespace App\Http\Controllers\Institution\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Institution;
use App\Models\ExamMark;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class MarksheetController extends Controller
{
    public function index()
    {
        $institutionId = Auth::guard('institution')->user()->id;
        // Fetch exams
        $exams = Exam::where('institution_id', $institutionId)->get();
        // Fetch classes
        $classes = SchoolClass::where('institution_id', $institutionId)->get();

        return view('institution.examination.marksheet.index', compact('exams', 'classes'));
    }

    public function search(Request $request)
    {
        $institutionId = Auth::guard('institution')->user()->id;
        
        $request->validate([
            'exam_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $students = Student::where('institution_id', $institutionId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get();

        $exam = Exam::find($request->exam_id);
        $class = SchoolClass::find($request->class_id);
        $section = Section::find($request->section_id);

        return view('institution.examination.marksheet.index', compact('students', 'exam', 'class', 'section', 'request'));
    }

    public function generatePdf($studentId, $examId)
    {
        $institutionId = Auth::guard('institution')->user()->id;
        $student = Student::findOrFail($studentId);
        $exam = Exam::findOrFail($examId);
        $institution = Institution::find($institutionId);

        // Fetch marks for this student and exam
        $marks = ExamMark::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->with(['subject'])
            ->get();

        $pdf = Pdf::loadView('institution.examination.marksheet.print', compact('student', 'exam', 'marks', 'institution'));
        
        return $pdf->download('Marksheet_' . $student->first_name . '_' . $exam->title . '.pdf');
    }
}
