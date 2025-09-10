<?php

namespace App\Http\Controllers\Institution\Academic;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SchoolClassController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:institution');
    }
    // Removed duplicate middleware since it's already applied in the route group

    public function index()
    {
        $currentInstitution = auth('institution')->user();
        
        $classes = SchoolClass::where('institution_id', $currentInstitution->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $sections = Section::where('status', 1)->get();
        $institutions = collect([$currentInstitution]); // Only show current institution

        return view('institution.academic.classes.index', compact('classes', 'sections','institutions'));
    }

    public function store(Request $request)
    {
        $currentInstitution = auth('institution')->user();
        
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:classes,name',
            'section_ids'   => 'required|array',
            'section_ids.*' => 'exists:sections,id',
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
            'institution_id' => $currentInstitution->id, // Force current institution
            'admin_id'       => $currentInstitution->admin_id,
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
        $currentInstitution = auth('institution')->user();
        
        $classes = SchoolClass::where('institution_id', $currentInstitution->id)
            ->orderBy('created_at','desc')
            ->get();
        
        // Load section names for each class
        $classes->each(function($class) {
            if ($class->section_ids) {
                $sectionIds = is_array($class->section_ids) ? $class->section_ids : json_decode($class->section_ids, true);
                $sections = Section::whereIn('id', $sectionIds ?: [])->get();
                $class->sections = $sections;
            }
        });

        return response()->json([
            'success' => true,
            'data'    => $classes
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        Log::info('Status update request received', ['id' => $id, 'status' => $request->status]);
        
        try {
            $currentInstitution = auth('institution')->user();
            $class = SchoolClass::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
            $class->update(['status' => $request->status]);

            Log::info('Status updated successfully', ['id' => $id, 'new_status' => $class->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'redirect_url' => route('institution.classes.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating status', ['id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            $class = SchoolClass::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
            
            // Load sections for this class
            if ($class->section_ids) {
                $sectionIds = is_array($class->section_ids) ? $class->section_ids : json_decode($class->section_ids, true);
                $sections = Section::whereIn('id', $sectionIds ?: [])->get();
                $class->sections = $sections;
            }

            return response()->json([
                'success' => true,
                'data' => $class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $currentInstitution = auth('institution')->user();
        
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:classes,name,' . $id,
            'section_ids'   => 'required|array',
            'section_ids.*' => 'exists:sections,id',
            'status'        => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $class = SchoolClass::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
                
            $class->update([
                'name'           => $request->name,
                'section_ids'    => $request->section_ids,
                'status'         => $request->status ?? 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'data'    => $class
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the class',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $currentInstitution = auth('institution')->user();
            $class = SchoolClass::where('id', $id)
                ->where('institution_id', $currentInstitution->id)
                ->firstOrFail();
            
            // Check if class has any students (you can add this validation if needed)
            // if ($class->students()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot delete class. It has associated students.'
            //     ], 422);
            // }
            
            $className = $class->name;
            $class->delete();

            return response()->json([
                'success' => true,
                'message' => "Class '{$className}' deleted successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting class', ['id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the class'
            ], 500);
        }
    }
}
