<?php

namespace App\Http\Controllers\Institution\Routine;

use App\Http\Controllers\Controller;
use App\Models\LessonPlan;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AssignClassTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LessonPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    /**
     * Display lesson plans list page with create form
     */
    public function index()
    {
        // Get the current authenticated institution
        $currentInstitution = Auth::guard('institution')->user();
        
        $lessonPlans = LessonPlan::with(['institution', 'teacher', 'schoolClass', 'subject', 'createdBy'])
            ->where('institution_id', $currentInstitution->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Only show current institution
        $institutions = collect([$currentInstitution]);
        
        return view('institution.routines.lesson-plans.index', compact('lessonPlans', 'institutions'));
    }


    /**
     * Store lesson plan data
     */
    public function store(Request $request)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'teacher_id' => 'required|exists:teachers,id',
                'class_id' => 'required|exists:classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'lesson_plan_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
                'status' => 'nullable|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('lesson_plan_file')) {
                $file = $request->file('lesson_plan_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('admin/uploads/lessonplan');
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $fileName);
                $filePath = 'admin/uploads/lessonplan/' . $fileName;
            }

            $lessonPlan = LessonPlan::create([
                'title' => $request->title,
                'description' => $request->description,
                'institution_id' => $currentInstitution->id,
                'teacher_id' => $request->teacher_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'lesson_plan_file' => $filePath,
                'status' => $request->status ?? 1,
                'created_by' => Auth::guard('admin')->id(),
            ]);

            // Load relationships for AJAX response
            $lessonPlan->load(['institution', 'teacher', 'schoolClass', 'subject', 'createdBy']);

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan created successfully',
                'data' => $lessonPlan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the lesson plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get lesson plan data for editing (AJAX)
     */
    public function edit($id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $lessonPlan = LessonPlan::with(['institution', 'teacher', 'schoolClass', 'subject'])
                ->where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $lessonPlan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson plan not found'
            ], 404);
        }
    }

    /**
     * Update lesson plan data
     */
    public function update(Request $request, $id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $lessonPlan = LessonPlan::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);

            // Validate the request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'teacher_id' => 'required|exists:teachers,id',
                'class_id' => 'required|exists:classes,id',
                'subject_id' => 'required|exists:subjects,id',
                'lesson_plan_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
                'status' => 'nullable|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload if new file is provided
            $filePath = $lessonPlan->lesson_plan_file;
            if ($request->hasFile('lesson_plan_file')) {
                // Delete old file if exists
                if ($lessonPlan->lesson_plan_file && File::exists(public_path($lessonPlan->lesson_plan_file))) {
                    File::delete(public_path($lessonPlan->lesson_plan_file));
                }

                $file = $request->file('lesson_plan_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('admin/uploads/lessonplan');
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $fileName);
                $filePath = 'admin/uploads/lessonplan/' . $fileName;
            }

            // Update lesson plan
            $lessonPlan->title = $request->title;
            $lessonPlan->description = $request->description;
            $lessonPlan->institution_id = $currentInstitution->id;
            $lessonPlan->teacher_id = $request->teacher_id;
            $lessonPlan->class_id = $request->class_id;
            $lessonPlan->subject_id = $request->subject_id;
            $lessonPlan->lesson_plan_file = $filePath;
            $lessonPlan->status = $request->has('status') ? (int)$request->status : $lessonPlan->status;
            $lessonPlan->save();

            // Load relationships for AJAX response
            $lessonPlan->load(['institution', 'teacher', 'schoolClass', 'subject', 'createdBy']);

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan updated successfully',
                'data' => $lessonPlan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the lesson plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete lesson plan
     */
    public function destroy($id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $lessonPlan = LessonPlan::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);

            // Delete file if exists
            if ($lessonPlan->lesson_plan_file && File::exists(public_path($lessonPlan->lesson_plan_file))) {
                File::delete(public_path($lessonPlan->lesson_plan_file));
            }

            $lessonPlan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the lesson plan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update lesson plan status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $lessonPlan = LessonPlan::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            
            // Validate status parameter
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update status
            $lessonPlan->status = (int)$request->status;
            $lessonPlan->save();

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan status updated successfully',
                'data' => [
                    'id' => $lessonPlan->id,
                    'status' => $lessonPlan->status,
                    'status_text' => $lessonPlan->status ? 'Active' : 'Inactive'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the lesson plan status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teachers by institution (only those assigned to classes)
     */
    public function getTeachersByInstitution($institutionId)
    {
        try {
            // Get teachers who are assigned to classes in this institution
            $teachers = Teacher::where('institution_id', $institutionId)
                              ->where('status', 1)
                              ->whereHas('assignClassTeachers', function($query) use ($institutionId) {
                                  $query->where('institution_id', $institutionId)
                                        ->where('status', 1);
                              })
                              ->get(['id', 'first_name', 'middle_name', 'last_name']);

            return response()->json(['teachers' => $teachers]);
        } catch (\Exception $e) {
            return response()->json(['teachers' => []]);
        }
    }

    /**
     * Get classes by teacher (from assign_class_teachers table)
     */
    public function getClassesByTeacher($institutionId, $teacherId)
    {
        try {
            // Get classes assigned to this teacher in this institution
            $classes = SchoolClass::where('institution_id', $institutionId)
                                 ->where('status', 1)
                                 ->whereHas('assignClassTeachers', function($query) use ($institutionId, $teacherId) {
                                     $query->where('institution_id', $institutionId)
                                           ->where('teacher_id', $teacherId)
                                           ->where('status', 1);
                                 })
                                 ->get(['id', 'name']);

            return response()->json(['classes' => $classes]);
        } catch (\Exception $e) {
            return response()->json(['classes' => []]);
        }
    }

    /**
     * Get classes by institution (fallback method)
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

    /**
     * Get subjects by institution and class
     */
    public function getSubjectsByInstitutionClass($institutionId, $classId)
    {
        try {
            $subjects = Subject::where('institution_id', $institutionId)
                              ->where('class_id', $classId)
                              ->where('status', 1)
                              ->get(['id', 'name', 'code']);

            return response()->json(['subjects' => $subjects]);
        } catch (\Exception $e) {
            return response()->json(['subjects' => []]);
        }
    }

    /**
     * Download lesson plan file
     */
    public function download($id)
    {
        try {
            // Get the current authenticated institution
            $currentInstitution = Auth::guard('institution')->user();
            
            $lessonPlan = LessonPlan::where('institution_id', $currentInstitution->id)
                ->findOrFail($id);
            
            if (!$lessonPlan->lesson_plan_file || !File::exists(public_path($lessonPlan->lesson_plan_file))) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            $filePath = public_path($lessonPlan->lesson_plan_file);
            $fileName = $lessonPlan->title . '.pdf';
            
            return response()->download($filePath, $fileName);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while downloading the file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}