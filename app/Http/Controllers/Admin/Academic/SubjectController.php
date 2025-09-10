<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Models\Subject;
use App\Models\Institution;
use App\Models\SchoolClass;
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
        $lists = Subject::with(['institution', 'schoolClass'])->orderBy('created_at', 'desc')->get();
        $institutions = Institution::where('status', 1)->get();
        $classes = collect(); // Empty collection - classes will be loaded via AJAX
        
        return view('admin.academic.subject.index', compact('lists', 'institutions', 'classes'));
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
                'class_id' => 'nullable|exists:classes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subject = Subject::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status ?? true,
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
            ])->fresh();

            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'data' => $subject
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
            $subjects = Subject::with(['institution', 'schoolClass'])->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $subjects
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
            $subject = Subject::with(['institution', 'schoolClass'])->findOrFail($id);
            $subject->update(['status' => $request->status]);

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

    public function edit($id)
    {
        try {
            $subject = Subject::with(['institution', 'schoolClass'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $subject
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Subject'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:subjects,name,' . $id,
                'code' => 'required|string|max:255|unique:subjects,code,' . $id,
                'type' => 'required|string|max:255',
                'status' => 'nullable|boolean',
                'institution_id' => 'required|exists:institutions,id',
                'class_id' => 'nullable|exists:classes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subject = Subject::findOrFail($id);
            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status ?? true,
                'institution_id' => $request->institution_id,
                'class_id' => $request->class_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'data' => $subject
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the Subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting subject'
            ], 500);
        }
    }

    /**
     * Get classes by institution (AJAX)
     */
    public function getClassesByInstitution($institutionId)
    {
        try {
            $classes = SchoolClass::where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'name']);

            return response()->json(['classes' => $classes]);
        } catch (\Exception $e) {
            return response()->json(['classes' => []]);
        }
    }
}
