<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{

    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        
        return view('student.index', compact('student'));
    }

    public function printIdCard()
    {
        $authStudent = Auth::guard('student')->user();

        $student = Student::with([
            'institution:id,name,logo,address,email,phone,website,board,district,state,pincode',
            'schoolClass:id,name',
            'section:id,name'
        ])->findOrFail($authStudent->id);

        $primaryColor = '#6366f1';
        $secondaryColor = '#0d6efd';

        $pdf = Pdf::loadView('admin.administration.students.id-card', [
            'student' => $student,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
        ])->setPaper('a4');

        $fileName = 'My_ID_Card_' . ($student->student_id ?? $student->id) . '.pdf';
        return $pdf->stream($fileName);
    }
}
