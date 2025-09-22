<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Institution;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Logic to list exams
        // $lists = Institution::with('exams.classes', 'exams.sections')->get();

        $institutions = Institution::all();
        return view('admin.examination.exam.index', compact('institutions'));
    }

    public function getClassesSections($institutionId)
    {
        $institution = Institution::with('classes.sec')->find($institutionId);

        if (!$institution) {
            return response()->json(['classes' => [], 'sections' => []]);
        }

        $classes = $institution->classes->map(function($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'sections' => $class->sections->map(function($section) {
                    return [
                        'id' => $section->id,
                        'name' => $section->name,
                    ];
                }),
            ];
        });

        // Optionally, flatten all sections if you want a single list
        $sections = $institution->classes->flatMap(function($class) {
            return $class->sections;
        })->unique('id')->values()->map(function($section) {
            return [
                'id' => $section->id,
                'name' => $section->name,
            ];
        });

        return response()->json([
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }
}
