<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $lists = Section::orderBy('created_at', 'desc')->get();
        return view('admin.academic.section.index', compact('lists'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:sections,name',
                'status' => 'nullable|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $section = Section::create([
                'name' => $request->name,
                'status' => $request->status ?? 1 // Default to 1 if not provided
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'data' => $section
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSections()
    {
        try {
            $sections = Section::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $section = Section::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:sections,name,' . $id,
                'status' => 'nullable|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $section = Section::findOrFail($id);
            $section->update([
                'name' => $request->name,
                'status' => $request->status ?? 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'data' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the section',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $section = Section::findOrFail($id);
            
            // Check if section is used in any classes
            $classesUsingSection = \App\Models\SchoolClass::whereJsonContains('section_ids', $id)->count();
            if ($classesUsingSection > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete section. It is being used by one or more classes.'
                ], 422);
            }
            
            $sectionName = $section->name;
            $section->delete();

            return response()->json([
                'success' => true,
                'message' => "Section '{$sectionName}' deleted successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the section'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->update(['status' => $request->status]);

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
