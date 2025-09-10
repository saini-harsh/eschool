<?php

namespace App\Http\Controllers\Institution\Academic;

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
        $this->middleware('auth:institution');
    }

    public function Index()
    {
        $currentInstitution = auth('institution')->user();
        
        // Logic to retrieve and display subjects for current institution only
        $lists = Subject::with(['institution', 'schoolClass'])
            ->where('institution_id', $currentInstitution->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $institutions = collect([$currentInstitution]); // Only show current institution
        $classes = SchoolClass::where('institution_id', $currentInstitution->id)
            ->where('status', 1)
            ->get(); // Get only active classes for current institution
        
        return view('institution.academic.subject.index', compact('lists', 'institutions', 'classes'));
    }

    public function store(Request $request)
    {
        try {
            $currentInstitution = auth('institution')->user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:subjects,name',
                'code' => 'required|string|max:255|unique:subjects,code',
                'type' => 'required|string|max:255',
                'status' => 'nullable|boolean',
                'class_id' => 'nullable|exists:classes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that class belongs to current institution if provided
            if ($request->class_id) {
                $class = SchoolClass::where('id', $request->class_id)
                    ->where('institution_id', $currentInstitution->id)
                    ->first();
                if (!$class) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected class does not belong to your institution'
                    ], 422);
                }
            }

            $subject = Subject::create([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status ?? true,
                'institution_id' => $currentInstitution->id, // Force current institution
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
            $currentInstitution = auth('institution')->user();
            $subjects = Subject::with(['institution', 'schoolClass'])
                ->where('institution_id', $currentInstitution->id)
                ->orderBy('created_at', 'desc')
                ->get();

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
            $currentInstitution = auth('institution')->user();
            $subject = Subject::with(['institution', 'schoolClass'])
                ->where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
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
            $currentInstitution = auth('institution')->user();
            $subject = Subject::with(['institution', 'schoolClass'])
                ->where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
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
            $currentInstitution = auth('institution')->user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:subjects,name,' . $id,
                'code' => 'required|string|max:255|unique:subjects,code,' . $id,
                'type' => 'required|string|max:255',
                'status' => 'nullable|boolean',
                'class_id' => 'nullable|exists:classes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that class belongs to current institution if provided
            if ($request->class_id) {
                $class = SchoolClass::where('id', $request->class_id)
                    ->where('institution_id', $currentInstitution->id)
                    ->first();
                if (!$class) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected class does not belong to your institution'
                    ], 422);
                }
            }

            $subject = Subject::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
                'type' => $request->type,
                'status' => $request->status ?? true,
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
            $currentInstitution = auth('institution')->user();
            $subject = Subject::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
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
}
