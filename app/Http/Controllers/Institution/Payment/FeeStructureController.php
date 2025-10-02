<?php

namespace App\Http\Controllers\Institution\Payment;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeeStructureController extends Controller
{
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        $feeStructures = FeeStructure::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('institution.payment.fee-structure.index', compact('feeStructures'));
    }

    public function create()
    {
        $institution = Auth::guard('institution')->user();
        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', true)
            ->get();
        
        return view('institution.payment.fee-structure.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:monthly,quarterly,yearly,one_time',
            'payment_frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'due_date' => 'nullable|string',
            'is_mandatory' => 'boolean',
        ]);

        $institution = Auth::guard('institution')->user();

        // Convert flatpickr date format to database format
        $dueDate = null;
        if ($request->due_date) {
            try {
                $dueDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->due_date)->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->withErrors(['due_date' => 'Invalid date format. Please use the date picker.']);
            }
        }

        FeeStructure::create([
            'institution_id' => $institution->id,
            'fee_name' => $request->fee_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'fee_type' => $request->fee_type,
            'payment_frequency' => $request->payment_frequency,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'due_date' => $dueDate,
            'is_mandatory' => $request->has('is_mandatory'),
            'status' => true,
        ]);

        return redirect()->route('institution.fee-structure.index')
            ->with('success', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the fee structure belongs to this institution
        if ($feeStructure->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', true)
            ->get();

        // Get sections from the class's section_ids array
        $class = SchoolClass::find($feeStructure->class_id);
        $sectionIds = $class ? ($class->section_ids ?? []) : [];
        $sections = Section::whereIn('id', $sectionIds)
            ->where('status', true)
            ->get();

        return view('institution.payment.fee-structure.edit', compact('feeStructure', 'classes', 'sections'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the fee structure belongs to this institution
        if ($feeStructure->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        // Debug: Log the request data
        \Log::info('Fee Structure Update Request:', $request->all());

        $request->validate([
            'fee_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:monthly,quarterly,yearly,one_time',
            'payment_frequency' => 'required|in:monthly,quarterly,yearly,one_time',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'due_date' => 'nullable|string',
            'is_mandatory' => 'boolean',
        ]);

        // Handle empty section_id
        $sectionId = $request->section_id ?: null;

        // Convert flatpickr date format to database format
        $dueDate = null;
        if ($request->due_date) {
            try {
                $dueDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->due_date)->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->withErrors(['due_date' => 'Invalid date format. Please use the date picker.']);
            }
        }

        $feeStructure->update([
            'fee_name' => $request->fee_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'fee_type' => $request->fee_type,
            'payment_frequency' => $request->payment_frequency,
            'class_id' => $request->class_id,
            'section_id' => $sectionId,
            'due_date' => $dueDate,
            'is_mandatory' => $request->has('is_mandatory'),
        ]);

        // Debug: Log successful update
        \Log::info('Fee Structure Updated Successfully:', ['id' => $feeStructure->id]);

        return redirect()->route('institution.fee-structure.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the fee structure belongs to this institution
        if ($feeStructure->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $feeStructure->delete();

        return redirect()->route('institution.fee-structure.index')
            ->with('success', 'Fee structure deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::where('institution_id', $institution->id)->findOrFail($id);
        
        $feeStructure->update(['status' => $request->status]);
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function getSectionsByClass($classId)
    {
        $class = SchoolClass::find($classId);
        if (!$class) {
            return response()->json([]);
        }
        
        // Get sections from the class's section_ids array
        $sectionIds = $class->section_ids ?? [];
        $sections = Section::whereIn('id', $sectionIds)
            ->where('status', true)
            ->get();
        
        return response()->json($sections);
    }

    public function generateInvoice(FeeStructure $feeStructure)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the fee structure belongs to this institution
        if ($feeStructure->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $feeStructure->load(['institution', 'schoolClass', 'section']);

        return view('institution.payment.fee-structure.invoice', compact('feeStructure'));
    }

    public function downloadInvoice(FeeStructure $feeStructure)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the fee structure belongs to this institution
        if ($feeStructure->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $feeStructure->load(['institution', 'schoolClass', 'section']);

        // Generate PDF invoice
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institution.payment.fee-structure.invoice-pdf', compact('feeStructure'));
        
        $filename = 'Fee_Invoice_' . $feeStructure->fee_name . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
