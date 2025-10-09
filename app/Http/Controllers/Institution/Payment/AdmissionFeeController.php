<?php

namespace App\Http\Controllers\Institution\Payment;

use App\Http\Controllers\Controller;
use App\Models\AdmissionFee;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdmissionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        $admissionFees = AdmissionFee::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('institution.payment.admission-fee.index', compact('admissionFees'));
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

        return view('institution.payment.admission-fee.create', compact('classes'));
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
            'effective_from' => 'required|string|date_format:Y-m-d',
            'effective_until' => 'nullable|string|date_format:Y-m-d|after:effective_from',
            'is_mandatory' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        $admissionFee = AdmissionFee::create([
            'name' => $request->name,
            'description' => $request->description,
            'institution_id' => $institution->id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'amount' => $request->amount,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_mandatory' => $request->has('is_mandatory') ? 1 : 0,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admission fee created successfully',
            'data' => $admissionFee
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $admissionFee = AdmissionFee::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->findOrFail($id);

        return view('institution.payment.admission-fee.show', compact('admissionFee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $admissionFee = AdmissionFee::where('institution_id', $institution->id)
            ->findOrFail($id);

        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $sections = Section::where('class_id', $admissionFee->class_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('institution.payment.admission-fee.edit', compact('admissionFee', 'classes', 'sections'));
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
            'effective_from' => 'required|string|date_format:Y-m-d',
            'effective_until' => 'nullable|string|date_format:Y-m-d|after:effective_from',
            'is_mandatory' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();
        $admissionFee = AdmissionFee::where('institution_id', $institution->id)
            ->findOrFail($id);

        $admissionFee->update([
            'name' => $request->name,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'amount' => $request->amount,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_mandatory' => $request->has('is_mandatory') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admission fee updated successfully',
            'data' => $admissionFee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $institution = Auth::guard('institution')->user();
        $admissionFee = AdmissionFee::where('institution_id', $institution->id)
            ->findOrFail($id);

        $admissionFee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admission fee deleted successfully'
        ]);
    }

    /**
     * Update the status of the admission fee.
     */
    public function updateStatus(Request $request, $id)
    {
        $institution = Auth::guard('institution')->user();
        $admissionFee = AdmissionFee::where('institution_id', $institution->id)
            ->findOrFail($id);

        $admissionFee->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Admission fee status updated successfully'
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
