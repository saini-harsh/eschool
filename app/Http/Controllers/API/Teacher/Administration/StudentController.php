<?php

namespace App\Http\Controllers\API\Teacher\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class StudentController extends Controller
{
    public function classWithSections(Request $request)
    {
        try {
            $teacher = Teacher::where('email', $request->email)->first();

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }

            $institutionId = $teacher->institution_id;

            $classes = SchoolClass::where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'name', 'institution_id', 'section_ids'])
                ->map(function ($class) {
                    $sectionIds = is_string($class->section_ids)
                        ? json_decode($class->section_ids, true)
                        : $class->section_ids;

                    $sections = [];
                    if (!empty($sectionIds)) {
                        $sections = Section::whereIn('id', $sectionIds)
                            ->get(['id', 'name'])
                            ->unique('name') // ✅ remove duplicates by name
                            ->values()
                            ->map(function ($section) {
                                return [
                                    'id' => $section->id,
                                    'name' => $section->name
                                ];
                            })
                            ->toArray();
                    }

                    $class->sections = $sections;
                    unset($class->section_ids);

                    return $class;
                });

            return response()->json([
                'success' => true,
                'message' => 'Classes with sections fetched successfully',
                'data' => $classes
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function students(Request $request)
    {
        try {
            // Find the teacher by email
            $teacher = Teacher::where('email', $request->email)->first();
    
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }
    
            $institutionId = $teacher->institution_id;
    
            // Base query for students in this institution
            $studentsQuery = Student::where('institution_id', $institutionId)
                ->with(['institution:id,name', 'schoolClass:id,name', 'section:id,name']);
    
            // Optional filtering by class_id
            if ($request->filled('class_id')) {
                $studentsQuery->where('class_id', $request->class_id);
            }
    
            // Optional filtering by section_id
            if ($request->filled('section_id')) {
                $studentsQuery->where('section_id', $request->section_id);
            }
    
            // Fetch students
            $students = $studentsQuery->get(['id', 'first_name', 'last_name', 'email', 'phone', 'class_id', 'section_id', 'institution_id'])
                ->map(function ($student) {
                    $student->name = trim($student->first_name . ' ' . $student->last_name);
                    return $student;
                });
    
            return response()->json([
                'success' => true,
                'message' => 'Students fetched successfully',
                'data' => [
                    'students' => $students,
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    public function studentDetail(Request $request)
    {
        try {
            // Validate required input
            $request->validate([
                'email' => 'required|email'
            ]);

            // Find student by email with related info
            $student = Student::with([
                'institution:id,name',
                'schoolClass:id,name',
                'section:id,name'
            ])->where('email', $request->email)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                    'data' => []
                ], 404);
            }

            // Add full name
            $student->name = trim($student->first_name . ' ' . $student->last_name);

            // List of file/photo fields to prepend URL if not null
            $fileFields = [
                'photo',
                'father_photo',
                'mother_photo',
                'guardian_photo',
                'pan_front',
                'pan_back',
                'document_01_file',
                'document_02_file',
                'document_03_file',
                'document_04_file'
            ];

            foreach ($fileFields as $field) {
                if (!empty($student->$field)) {
                    $student->$field = URL::to($student->$field);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Student fetched successfully',
                'data' => $student
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    

    


  

}
