<?php

namespace App\Http\Controllers\Admin\Academic;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolClassController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $classes = SchoolClass::orderBy('created_at', 'desc')->get();
        $sections = Section::where('status', 1)->get();
        $institutions = Institution::where('status', 1)->get();

        return view('admin.academic.classes.index', compact('classes', 'sections','institutions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:classes,name',
            'section_ids'   => 'required|array',
            'section_ids.*' => 'exists:sections,id',
            'institution_id'=> 'required|exists:institutions,id',
            'status'        => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $class = SchoolClass::create([
            'name'           => $request->name,
            'section_ids'    => $request->section_ids, // will be auto-casted to JSON
            'institution_id' => $request->institution_id,
            'admin_id'       => auth()->id(),
            'status'         => $request->status ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Class created successfully',
            'data'    => $class
        ], 201);
    }

    public function getSchoolClasses()
    {
        $classes = SchoolClass::orderBy('created_at','desc')->get();

        return response()->json([
            'success' => true,
            'data'    => $classes
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}
