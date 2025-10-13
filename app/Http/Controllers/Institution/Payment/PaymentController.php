<?php

namespace App\Http\Controllers\Institution\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        $payments = Payment::with(['student', 'feeStructure'])
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('institution.payment.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create($feeStructureId)
    {
        $institution = Auth::guard('institution')->user();
        $feeStructure = FeeStructure::with(['schoolClass', 'section'])
            ->where('institution_id', $institution->id)
            ->findOrFail($feeStructureId);

        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('institution.payment.payments.create', compact('feeStructure', 'classes'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,online,bank_transfer,other',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        // Verify the fee structure belongs to this institution
        $feeStructure = FeeStructure::where('institution_id', $institution->id)
            ->findOrFail($request->fee_structure_id);

        // Verify the student belongs to this institution
        $student = Student::where('institution_id', $institution->id)
            ->findOrFail($request->student_id);

        $payment = Payment::create([
            'institution_id' => $institution->id,
            'student_id' => $request->student_id,
            'fee_structure_id' => $request->fee_structure_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
            'payment_date' => $request->payment_date,
            'receipt_number' => Payment::generateReceiptNumber(),
            'status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully',
            'data' => $payment,
            'redirect_url' => route('institution.payments.show', $payment->id)
        ]);
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $institution = Auth::guard('institution')->user();
        $payment = Payment::with(['student', 'feeStructure', 'feeStructure.schoolClass', 'feeStructure.section'])
            ->where('institution_id', $institution->id)
            ->findOrFail($id);

        return view('institution.payment.payments.show', compact('payment'));
    }

    /**
     * Get students by class ID (AJAX)
     */
    public function getStudentsByClass($classId)
    {
        $institution = Auth::guard('institution')->user();
        $students = Student::where('institution_id', $institution->id)
            ->where('class_id', $classId)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'admission_number']);

        return response()->json($students);
    }
}
