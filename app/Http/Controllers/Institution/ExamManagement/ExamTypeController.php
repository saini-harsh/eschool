<?php

namespace App\Http\Controllers\Institution\ExamManagement;
use App\Models\ExamType;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index(Request $request)
    {
        $currentInstitutionId = Auth::guard('institution')->id();
        $institutions = Institution::where('id', $currentInstitutionId)->get(['id','name']);

        $query = ExamType::with('institution')
            ->where('institution_id', $currentInstitutionId);

        if ($request->filled('status')) {
            $statusVals = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', array_map('intval', $statusVals));
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', '%'.$s.'%')
                  ->orWhere('code', 'like', '%'.$s.'%')
                  ->orWhere('description', 'like', '%'.$s.'%');
            });
        }

        $lists = $query->orderBy('title')->get();
        return view('institution.examination.exam.type', compact('institutions', 'lists'));
    }

    public function store(Request $request)
    {

        // Code to store invigilator
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:55|unique:exam_types,code',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ExamType;
        $classRoom->institution_id = Auth::guard('institution')->user()->id;
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
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:55|unique:exam_types,code,' . $request->id,
            'status' => 'required|boolean',
        ]);

        $id = Auth::guard('institution')->user()->id;
        $examType = ExamType::findOrFail($request->id);
        $examType->institution_id = $id;
        $examType->title = $request->title;
        $examType->code = $request->code;
        $examType->description = $request->description;
        $examType->status = $request->status;
        $examType->save();

        return response()->json(['success' => 'Exam Type updated successfully.']);
    }
}
