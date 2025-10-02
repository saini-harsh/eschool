<?php

namespace App\Http\Controllers\Institution\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\StudentFee;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $institution = Auth::guard('institution')->user();
        $payments = Payment::with(['student', 'studentFee.feeStructure'])
            ->where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('institution.payment.payments.index', compact('payments'));
    }

    public function create()
    {
        $institution = Auth::guard('institution')->user();
        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', true)
            ->get();

        return view('institution.payment.payments.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'student_fee_id' => 'required|exists:student_fees,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,online,cheque,card',
            'payment_date' => 'required|string',
            'payment_notes' => 'nullable|string',
            'transaction_id' => 'nullable|string',
        ]);

        $institution = Auth::guard('institution')->user();
        $studentFee = StudentFee::findOrFail($request->student_fee_id);

        // Check if the student fee belongs to this institution
        if ($studentFee->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if payment amount doesn't exceed balance
        if ($request->amount > $studentFee->balance_amount) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed the balance amount.']);
        }

        // Convert flatpickr date format to database format
        try {
            $paymentDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->payment_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return back()->withErrors(['payment_date' => 'Invalid date format. Please use the date picker.']);
        }

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'student_id' => $request->student_id,
                'student_fee_id' => $request->student_fee_id,
                'institution_id' => $institution->id,
                'payment_reference' => Payment::generatePaymentReference(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'completed',
                'payment_date' => $paymentDate,
                'payment_notes' => $request->payment_notes,
                'transaction_id' => $request->transaction_id,
                'receipt_number' => Payment::generateReceiptNumber(),
            ]);

            // Update student fee
            $studentFee->paid_amount += $request->amount;
            $studentFee->updateStatus();

            DB::commit();

            return redirect()->route('institution.payments.index')
                ->with('success', 'Payment recorded successfully. Receipt Number: ' . $payment->receipt_number);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred while processing the payment.']);
        }
    }

    public function show(Payment $payment)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the payment belongs to this institution
        if ($payment->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['student', 'studentFee.feeStructure']);

        return view('institution.payment.payments.show', compact('payment'));
    }

    public function getStudentsByClass($classId)
    {
        $institution = Auth::guard('institution')->user();
        $students = Student::where('institution_id', $institution->id)
            ->where('class_id', $classId)
            ->where('status', true)
            ->get();

        return response()->json($students);
    }

    public function getStudentsByClassSection($classId, $sectionId = null)
    {
        $institution = Auth::guard('institution')->user();
        $query = Student::where('institution_id', $institution->id)
            ->where('class_id', $classId)
            ->where('status', true);

        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $students = $query->get();

        return response()->json($students);
    }

    public function getStudentFees($studentId)
    {
        $institution = Auth::guard('institution')->user();
        $studentFees = StudentFee::with('feeStructure')
            ->where('student_id', $studentId)
            ->where('institution_id', $institution->id)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->get();

        return response()->json($studentFees);
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

    public function generateBills()
    {
        $institution = Auth::guard('institution')->user();
        
        // This method will be called by a scheduled job to generate monthly bills
        $feeStructures = FeeStructure::where('institution_id', $institution->id)
            ->where('status', true)
            ->where('payment_frequency', 'monthly')
            ->get();

        $billsGenerated = 0;

        foreach ($feeStructures as $feeStructure) {
            $students = Student::where('institution_id', $institution->id)
                ->where('class_id', $feeStructure->class_id)
                ->where('status', true);

            if ($feeStructure->section_id) {
                $students->where('section_id', $feeStructure->section_id);
            }

            $students = $students->get();

            foreach ($students as $student) {
                // Check if bill already exists for this month
                $existingBill = StudentFee::where('student_id', $student->id)
                    ->where('fee_structure_id', $feeStructure->id)
                    ->whereMonth('billing_date', now()->month)
                    ->whereYear('billing_date', now()->year)
                    ->first();

                if (!$existingBill) {
                    StudentFee::create([
                        'student_id' => $student->id,
                        'fee_structure_id' => $feeStructure->id,
                        'institution_id' => $institution->id,
                        'amount' => $feeStructure->amount,
                        'paid_amount' => 0,
                        'balance_amount' => $feeStructure->amount,
                        'due_date' => $feeStructure->due_date ?: now()->addDays(30),
                        'billing_date' => now(),
                        'status' => 'pending',
                    ]);
                    $billsGenerated++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Generated {$billsGenerated} new bills for the current month."
        ]);
    }

    public function generateReceipt(Payment $payment)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the payment belongs to this institution
        if ($payment->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['student', 'studentFee.feeStructure', 'institution']);

        return view('institution.payment.payments.receipt', compact('payment'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $institution = Auth::guard('institution')->user();
        
        // Check if the payment belongs to this institution
        if ($payment->institution_id !== $institution->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['student', 'studentFee.feeStructure', 'institution']);

        // Generate PDF receipt
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('institution.payment.payments.receipt-pdf', compact('payment'));
        
        $filename = 'Receipt_' . $payment->receipt_number . '_' . $payment->payment_date->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
