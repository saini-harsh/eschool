<?php

namespace App\Http\Controllers\API\Teacher\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Institution;
use App\Models\Teacher;
use App\Models\LessonPlan;
use App\Models\SchoolClass;
use App\Models\Subject;

class LessonPlanController extends Controller
{
    /**
     * List lesson plans for the logged-in teacher (identified by email).
     * Optional filters: class_id, subject_id, status, search.
     */
    public function lessonPlanList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(),'data'=>[]], 422);
        }

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found for provided email','data'=>[]], 404);
        }

        $plans = LessonPlan::where('institution_id', $teacher->institution_id)
            ->where('teacher_id', $teacher->id)
            ->with(['schoolClass', 'subject'])->orderBy('id', 'desc')->get();

       
        

        return response()->json([
            'success' => true,
            'message' => 'Lesson plans retrieved successfully',
            'data' => $plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'title' => $plan->title,
                    'description' => $plan->description,
                    'class' => optional($plan->schoolClass)->name ?? null,
                    'class_id' => $plan->class_id,
                    'subject' => optional($plan->subject)->name ?? null,
                    'subject_id' => $plan->subject_id,
                    'file' => $plan->lesson_plan_file ? url($plan->lesson_plan_file) : null,
                    'status' => $plan->status,
                ];
            }),
        ], 200);
    }

    /**
     * Add a lesson plan for the teacher identified by email.
     * Supports multipart file upload (lesson_plan_file PDF), optional.
     */
    public function addLessonPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'lesson_plan_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(),'data'=>[]], 422);
        }

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found for provided email','data'=>[]], 404);
        }

        // Ensure class and subject belong to the teacher's institution (optional strictness)
        $class = SchoolClass::where('id', $request->class_id)
            ->where('institution_id', $teacher->institution_id)
            ->first();
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Class not found in teacher institution','data'=>[]], 422);
        }

        $subject = Subject::where('id', $request->subject_id)
            ->where('institution_id', $teacher->institution_id)
            ->first();
        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found in teacher institution','data'=>[]], 422);
        }

        $filePath = null;
        if ($request->hasFile('lesson_plan_file')) {
            $file = $request->file('lesson_plan_file');
            $name = uniqid('lp_') . '.' . $file->getClientOriginalExtension();
            $destination = public_path('admin/uploads/lessonplan');
            if (!is_dir($destination)) {
                @mkdir($destination, 0755, true);
            }
            $file->move($destination, $name);
            $filePath = 'admin/uploads/lessonplan/' . $name;
        }

        $plan = LessonPlan::create([
            'institution_id' => $teacher->institution_id,
            'teacher_id' => $teacher->id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'lesson_plan_file' => $filePath,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lesson plan created successfully',
            'data' => [
                'id' => $plan->id,
                'title' => $plan->title,
                'class_id' => $plan->class_id,
                'subject_id' => $plan->subject_id,
                'file' => $plan->lesson_plan_file ? url($plan->lesson_plan_file) : null,
                'status' => $plan->status,
            ],
        ], 201);
    }

    /**
     * Edit/update a lesson plan by id, for the teacher identified by email.
     * Allows replacing the PDF file.
     */
    public function editLessonPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'id' => 'required|integer|exists:lesson_plans,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
            'lesson_plan_file' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'nullable|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(),'data'=>[]], 422);
        }

        $teacher = Teacher::where('email', $request->email)->first();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Teacher not found for provided email','data'=>[]], 404);
        }

        $plan = LessonPlan::where('id', $request->id)
            ->where('institution_id', $teacher->institution_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Lesson plan not found for this teacher','data'=>[]], 404);
        }

        // Optional integrity checks for class and subject if provided
        if ($request->filled('class_id')) {
            $class = SchoolClass::where('id', $request->class_id)
                ->where('institution_id', $teacher->institution_id)
                ->first();
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Class not found in teacher institution','data'=>[]], 422);
            }
            $plan->class_id = $request->class_id;
        }

        if ($request->filled('subject_id')) {
            $subject = Subject::where('id', $request->subject_id)
                ->where('institution_id', $teacher->institution_id)
                ->first();
            if (!$subject) {
                return response()->json(['success' => false, 'message' => 'Subject not found in teacher institution','data'=>[]], 422);
            }
            $plan->subject_id = $request->subject_id;
        }

        if ($request->filled('title')) {
            $plan->title = $request->title;
        }
        if ($request->filled('description')) {
            $plan->description = $request->description;
        }
        if ($request->filled('status')) {
            $plan->status = $request->status;
        }

        if ($request->hasFile('lesson_plan_file')) {
            // delete old file if exists
            if ($plan->lesson_plan_file && file_exists(public_path($plan->lesson_plan_file))) {
                @unlink(public_path($plan->lesson_plan_file));
            }
            $file = $request->file('lesson_plan_file');
            $name = uniqid('lp_') . '.' . $file->getClientOriginalExtension();
            $destination = public_path('admin/uploads/lessonplan');
            if (!is_dir($destination)) {
                @mkdir($destination, 0755, true);
            }
            $file->move($destination, $name);
            $plan->lesson_plan_file = 'admin/uploads/lessonplan/' . $name;
        }

        $plan->save();

        return response()->json([
            'success' => true,
            'message' => 'Lesson plan updated successfully',
            'data' => [
                'id' => $plan->id,
                'title' => $plan->title,
                'class_id' => $plan->class_id,
                'subject_id' => $plan->subject_id,
                'file' => $plan->lesson_plan_file ? url($plan->lesson_plan_file) : null,
                'status' => $plan->status,
            ],
        ], 200);
    }
}

