<?php

namespace App\Http\Controllers\Admin\Academic;
use App\Models\Teacher;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignClassTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $institutions = Institution::all();
        $teachers = Teacher::all();
        $classes = SchoolClass::all();
        $lists = AssignClassTeacher::with(['institution', 'class', 'section', 'teacher'])->get();
        return view('admin.academic.assign-teacher', compact('teachers', 'classes', 'institutions','lists'));
    }

    // Fetch classes by institution
    public function getClassesByInstitution($institutionId)
    {
        $classes = SchoolClass::where('institution_id', $institutionId)->get(['id', 'name']);
        return response()->json(['classes' => $classes]);
    }

    // Fetch teachers by institution
    public function getTeachersByInstitution($institutionId)
    {
        $teachers = Teacher::where('institution_id', $institutionId)->get(['id', 'first_name', 'last_name']);
        return response()->json(['teachers' => $teachers]);
    }

    // Fetch sections by class
    public function getSectionsByClass($classId)
    {
        $class = SchoolClass::find($classId);
        if (!$class) {
            return response()->json(['sections' => []]);
        }
        
        
        $sectionIds = json_decode($class->section_ids) ?? [];

        $sections = empty($sectionIds) ? [] : Section::whereIn('id', $sectionIds)->get(['id', 'name']);
        return response()->json(['sections' => $sections]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'nullable|boolean',
        ]);

        AssignClassTeacher::create([
            'institution_id' => $request->institution_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
            'status' => $request->status ? 1 : 0,
        ]);

        return response()->json(['success' => true]);
    }
}
