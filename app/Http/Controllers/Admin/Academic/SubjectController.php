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

    public function Index(Request $request)
    {

        // Build query with filters
        $query = Subject::with(['institution', 'schoolClass']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('type', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('institution', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('schoolClass', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('institution_ids')) {
            $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
            $query->whereIn('institution_id', $institutionIds);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('class_ids')) {
            $classIds = is_array($request->class_ids) ? $request->class_ids : [$request->class_ids];
            $query->whereIn('class_id', $classIds);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Get filtered results
        $lists = $query->orderBy('created_at', 'desc')->get();
        $institutions = Institution::where('status', 1)->get();
        $classes = collect(); // Empty collection - classes will be loaded via AJAX
dd($lists,$classes);
        return view('admin.academic.subject.index', compact('lists', 'institutions', 'classes'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
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
     * Filter subjects (AJAX)
     */
    public function filter(Request $request)
    {
        try {
            // Log the request for debugging
            \Log::info('Filter request received:', $request->all());

            // Build query with filters
            $query = Subject::with(['institution', 'schoolClass']);

            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('code', 'like', '%' . $searchTerm . '%')
                      ->orWhere('type', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('institution', function($subQ) use ($searchTerm) {
                          $subQ->where('name', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('schoolClass', function($subQ) use ($searchTerm) {
                          $subQ->where('name', 'like', '%' . $searchTerm . '%');
                      });
                });
            }

            if ($request->filled('institution_ids')) {
                $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
                $query->whereIn('institution_id', $institutionIds);
            }

            if ($request->filled('status')) {
                $status = $request->status;
                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', $status);
                }
            }

            if ($request->filled('class_ids')) {
                $classIds = is_array($request->class_ids) ? $request->class_ids : [$request->class_ids];
                $query->whereIn('class_id', $classIds);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Get filtered results
            $subjects = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering subjects'
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
