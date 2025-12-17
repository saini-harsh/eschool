<?php

namespace App\Http\Controllers\Institution\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalaryStructureController extends Controller
{
    /**
     * Display a listing of salary structures.
     */
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        
        $structures = SalaryStructure::with('components')
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('institution.salary.structures.index', compact('structures'));
    }

    /**
     * Show the form for creating a new salary structure.
     */
    public function create()
    {
        return view('institution.salary.structures.create');
    }

    /**
     * Store a newly created salary structure.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.type' => 'required|in:earning,deduction',
            'components.*.amount' => 'required|numeric|min:0',
            'components.*.is_percentage' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        try {
            DB::beginTransaction();

            $structure = SalaryStructure::create([
                'institution_id' => $institution->id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => true,
            ]);

            foreach ($request->components as $component) {
                SalaryComponent::create([
                    'salary_structure_id' => $structure->id,
                    'name' => $component['name'],
                    'type' => $component['type'],
                    'amount' => $component['amount'],
                    'is_percentage' => $component['is_percentage'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Salary structure created successfully',
                'redirect_url' => route('institution.salary.structures.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create salary structure: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing a salary structure.
     */
    public function edit($id)
    {
        $institution = Auth::guard('institution')->user();
        
        $structure = SalaryStructure::with('components')
            ->where('institution_id', $institution->id)
            ->findOrFail($id);

        return view('institution.salary.structures.edit', compact('structure'));
    }

    /**
     * Update the specified salary structure.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'required|array|min:1',
            'components.*.id' => 'nullable|exists:salary_components,id',
            'components.*.name' => 'required|string|max:255',
            'components.*.type' => 'required|in:earning,deduction',
            'components.*.amount' => 'required|numeric|min:0',
            'components.*.is_percentage' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        $structure = SalaryStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        try {
            DB::beginTransaction();

            $structure->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Get existing component IDs
            $existingIds = $structure->components->pluck('id')->toArray();
            $updatedIds = [];

            foreach ($request->components as $componentData) {
                if (!empty($componentData['id'])) {
                    // Update existing component
                    $component = SalaryComponent::find($componentData['id']);
                    if ($component && $component->salary_structure_id == $structure->id) {
                        $component->update([
                            'name' => $componentData['name'],
                            'type' => $componentData['type'],
                            'amount' => $componentData['amount'],
                            'is_percentage' => $componentData['is_percentage'],
                        ]);
                        $updatedIds[] = $component->id;
                    }
                } else {
                    // Create new component
                    $component = SalaryComponent::create([
                        'salary_structure_id' => $structure->id,
                        'name' => $componentData['name'],
                        'type' => $componentData['type'],
                        'amount' => $componentData['amount'],
                        'is_percentage' => $componentData['is_percentage'],
                    ]);
                    $updatedIds[] = $component->id;
                }
            }

            // Delete removed components
            $toDelete = array_diff($existingIds, $updatedIds);
            SalaryComponent::whereIn('id', $toDelete)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Salary structure updated successfully',
                'redirect_url' => route('institution.salary.structures.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update salary structure: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified salary structure.
     */
    public function destroy($id)
    {
        $institution = Auth::guard('institution')->user();

        $structure = SalaryStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        // Check if structure is in use
        $teachersCount = $structure->teachers()->count();
        $staffCount = $structure->nonWorkingStaff()->count();

        if ($teachersCount > 0 || $staffCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete salary structure. It is assigned to ' . 
                    ($teachersCount + $staffCount) . ' employees.'
            ], 422);
        }

        try {
            $structure->delete();

            return response()->json([
                'success' => true,
                'message' => 'Salary structure deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete salary structure'
            ], 500);
        }
    }

    /**
     * Toggle status of salary structure.
     */
    public function updateStatus($id)
    {
        $institution = Auth::guard('institution')->user();

        $structure = SalaryStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        $structure->status = !$structure->status;
        $structure->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $structure->status
        ]);
    }

    /**
     * Get salary structures for dropdown (AJAX).
     */
    public function getStructures()
    {
        $institution = Auth::guard('institution')->user();

        $structures = SalaryStructure::where('institution_id', $institution->id)
            ->where('status', true)
            ->get(['id', 'name']);

        return response()->json($structures);
    }

    /**
     * Preview salary calculation for a structure.
     */
    public function preview(Request $request, $id)
    {
        $institution = Auth::guard('institution')->user();

        $structure = SalaryStructure::with('components')
            ->where('institution_id', $institution->id)
            ->findOrFail($id);

        $baseSalary = $request->get('base_salary', 0);
        $breakdown = $structure->getSalaryBreakdown($baseSalary);

        return response()->json([
            'success' => true,
            'breakdown' => $breakdown
        ]);
    }
}
