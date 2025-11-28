<?php

namespace App\Http\Controllers\Admin\ExamManagement;
use App\Models\ExamType;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Exam;

class ExamTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // Code to list invigilators
        $institutions = Institution::all();
        $lists = ExamType::with('institution')->get();
        return view('admin.examination.exam.type',compact('institutions','lists'));
    }

    public function store(Request $request)
    {

        // Code to store invigilator
        $request->validate([
            'institution_id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:55|unique:exam_types,code',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ExamType;
        $classRoom->institution_id = $request->institution_id;
        $classRoom->title = $request->title;
        $classRoom->code = $request->code;
        $classRoom->description = $request->description;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json(['success' => 'Exam Type created successfully.']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:exam_types,id',
            'institution_id' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:55|unique:exam_types,code,' . $request->id,
            'status' => 'required|boolean',
        ]);

        $examType = ExamType::findOrFail($request->id);
        $examType->institution_id = $request->institution_id;
        $examType->title = $request->title;
        $examType->code = $request->code;
        $examType->description = $request->description;
        $examType->status = $request->status;
        $examType->save();

        return response()->json(['success' => 'Exam Type updated successfully.']);
    }
}
