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
            $student->photo = $student->photo ? URL::to($student->photo) : null;
            $student->father_photo = $student->father_photo ? URL::to($student->father_photo) : null;
            $student->mother_photo = $student->mother_photo ? URL::to($student->mother_photo) : null;
            $student->guardian_photo = $student->guardian_photo ? URL::to($student->guardian_photo) : null;
            $student->aadhaar_front = $student->aadhaar_front ? URL::to($student->aadhaar_front) : null;
            $student->aadhaar_back = $student->aadhaar_back ? URL::to($student->aadhaar_back) : null;
            $student->pan_front = $student->pan_front ? URL::to($student->pan_front) : null;
            $student->pan_back = $student->pan_back ? URL::to($student->pan_back) : null;
            $student->document_01_file = $student->document_01_file ? URL::to($student->document_01_file) : null;
            $student->document_02_file = $student->document_02_file ? URL::to($student->document_02_file) : null;
            $student->document_03_file = $student->document_03_file ? URL::to($student->document_03_file) : null;
            $student->document_04_file = $student->document_04_file ? URL::to($student->document_04_file) : null;
            $student->profile_image = $student->profile_image ? URL::to($student->profile_image) : null;

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
