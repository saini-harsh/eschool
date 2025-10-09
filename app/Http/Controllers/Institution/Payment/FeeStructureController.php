<?php

namespace App\Http\Controllers\Institution\Payment;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        $feeStructures = FeeStructure::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('institution.payment.fee-structure.index', compact('feeStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $institution = Auth::guard('institution')->user();
        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('institution.payment.fee-structure.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        $feeStructure = FeeStructure::create([
            'name' => $request->name,
            'description' => $request->description,
            'institution_id' => $institution->id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'amount' => $request->amount,
            'fee_type' => $request->fee_type,
            'start_date' => $request->start_date,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fee structure created successfully',
            'data' => $feeStructure
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->findOrFail($id);

        return view('institution.payment.fee-structure.show', compact('feeStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $sections = Section::where('class_id', $feeStructure->class_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('institution.payment.fee-structure.edit', compact('feeStructure', 'classes', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        $feeStructure->update([
            'name' => $request->name,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'amount' => $request->amount,
            'fee_type' => $request->fee_type,
            'start_date' => $request->start_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fee structure updated successfully',
            'data' => $feeStructure
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        $feeStructure->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fee structure deleted successfully'
        ]);
    }

    /**
     * Update the status of the fee structure.
     */
    public function updateStatus(Request $request, $id)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::where('institution_id', $institution->id)
            ->findOrFail($id);

        $feeStructure->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Fee structure status updated successfully'
        ]);
    }

    /**
     * Get sections by class ID (AJAX)
     */
    public function getSectionsByClass($classId)
    {
        $sections = Section::where('class_id', $classId)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return response()->json($sections);
    }
}
