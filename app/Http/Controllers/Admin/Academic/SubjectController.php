<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Models\Subject;
use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function Index()
    {
        // Logic to retrieve and display subjects
        $lists = Subject::with('institution')->orderBy('created_at', 'desc')->get();
        $institutions = Institution::all(); // Assuming you have an Institution model
        $classes = [
            ['id' => 1, 'name' => 'Class 1'],
            ['id' => 2, 'name' => 'Class 2'],
            ['id' => 3, 'name' => 'Class 3'],
        ]; // Placeholder for classes if needed
        return view('admin.academic.subject.index',compact('lists', 'institutions', 'classes'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:subjects,name',
                'code' => 'required|string|max:255|unique:subjects,code',
                'type' => 'required|string|max:255',
                'status' => 'nullable|boolean',
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'nullable', // Assuming you have a
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $Subject = Subject::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status ?? true, // Default to true if not provided
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id, // Assuming class_id is optional
            ])->fresh(); // Fresh to get the newly created instance with ID

            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'data' => $Subject
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the Subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubjects()
    {
        try {
            $Subjects = Subject::with('institution')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $Subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Subjects'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $Subject = Subject::with('institution')->findOrFail($id);
            $Subject->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }
}
