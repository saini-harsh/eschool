<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        // Start building the query
        $query = Exam::with(['institution', 'examType', 'class', 'section']);

        // Apply filters if provided
        if ($request->filled('institution')) {
            $query->where('institution_id', $request->institution);
        }

        if ($request->filled('class')) {
            $query->where('class_id', $request->class);
        }

        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        // Get filtered results
        $lists = $query->orderBy('created_at', 'desc')->get();

        $institutions = Institution::all();
        return view('admin.examination.exam.index', compact('lists', 'institutions'));
    }

    public function getClassesSections($institutionId)
    {
        try {
            // Get institution with classes and sections
            $institution = Institution::with(['classes', 'sections'])->find($institutionId);

            if (!$institution) {
                return response()->json(['classes' => [], 'sections' => []]);
            }

            // Get classes for this institution
            $classes = $institution->classes->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                ];
            });

            // Get all sections for this institution
            $sections = $institution->sections->map(function($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                ];
            });

            return response()->json([
                'classes' => $classes,
                'sections' => $sections,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getClassesSections: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch classes and sections'], 500);
        }
    }

    public function getExamType($institutionId){
        try {
            // Get institution with classes and sections
            $institution = Institution::with(['examTypes'])->find($institutionId);

            if (!$institution) {
                return response()->json(['types' => []]);
            }

            // Get classes for this institution
            $types = $institution->examTypes->map(function($type) {
                return [
                    'id' => $type->id,
                    'title' => $type->title,
                    'code' => $type->code,
                ];
            });

            return response()->json([
                'types' => $types,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getExamTypes: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch ExamTypes'], 500);
        }
    }
}
