<?php

namespace App\Http\Controllers\Admin\Salary;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalaryStructureController extends Controller
{
    /**
     * Display a listing of salary structures.
     */
    public function index(Request $request)
    {
        $institutions = Institution::where('status', true)->orderBy('name')->get();

        $query = SalaryStructure::with(['institution', 'components'])
            ->orderBy('created_at', 'desc');

        // Filter by institution
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status == '1');
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $structures = $query->paginate(15)->withQueryString();

        return view('admin.salary.structures.index', compact('structures', 'institutions'));
    }

    /**
     * Show the form for creating a new salary structure.
     */
    public function create()
    {
        $institutions = Institution::where('status', true)->orderBy('name')->get();
        return view('admin.salary.structures.create', compact('institutions'));
    }

    /**
     * Store a newly created salary structure.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string|max:255',
            'components.*.type' => 'required_with:components|in:earning,deduction',
            'components.*.amount' => 'required_with:components|numeric|min:0',
            'components.*.is_percentage' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $structure = SalaryStructure::create([
                'institution_id' => $request->institution_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->has('status'),
            ]);

            // Add components
            if ($request->has('components')) {
                foreach ($request->components as $component) {
                    if (!empty($component['name'])) {
                        SalaryComponent::create([
                            'salary_structure_id' => $structure->id,
                            'name' => $component['name'],
                            'type' => $component['type'],
                            'amount' => $component['amount'],
                            'is_percentage' => isset($component['is_percentage']),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.salary.structures.index')
                ->with('success', 'Salary structure created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create salary structure: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing a salary structure.
     */
    public function edit($id)
    {
        $structure = SalaryStructure::with('components')->findOrFail($id);
        $institutions = Institution::where('status', true)->orderBy('name')->get();
        return view('admin.salary.structures.edit', compact('structure', 'institutions'));
    }

    /**
     * Update the specified salary structure.
     */
    public function update(Request $request, $id)
    {
        $structure = SalaryStructure::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'institution_id' => 'required|exists:institutions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string|max:255',
            'components.*.type' => 'required_with:components|in:earning,deduction',
            'components.*.amount' => 'required_with:components|numeric|min:0',
            'components.*.is_percentage' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $structure->update([
                'institution_id' => $request->institution_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->has('status'),
            ]);

            // Delete existing components and recreate
            $structure->components()->delete();

            if ($request->has('components')) {
                foreach ($request->components as $component) {
                    if (!empty($component['name'])) {
                        SalaryComponent::create([
                            'salary_structure_id' => $structure->id,
                            'name' => $component['name'],
                            'type' => $component['type'],
                            'amount' => $component['amount'],
                            'is_percentage' => isset($component['is_percentage']),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.salary.structures.index')
                ->with('success', 'Salary structure updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update salary structure: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified salary structure.
     */
    public function destroy($id)
    {
        $structure = SalaryStructure::findOrFail($id);

        try {
            $structure->components()->delete();
            $structure->delete();
            return redirect()->route('admin.salary.structures.index')
                ->with('success', 'Salary structure deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete salary structure: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status of salary structure.
     */
    public function toggleStatus($id)
    {
        $structure = SalaryStructure::findOrFail($id);
        $structure->status = !$structure->status;
        $structure->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $structure->status
        ]);
    }
}
