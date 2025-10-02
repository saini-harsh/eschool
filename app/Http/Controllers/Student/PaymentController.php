<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get all student fees with their status
        $studentFees = StudentFee::with('feeStructure')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get payment history
        $payments = Payment::with('studentFee.feeStructure')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate summary statistics
        $totalFees = $studentFees->sum('amount');
        $totalPaid = $studentFees->sum('paid_amount');
        $totalBalance = $studentFees->sum('balance_amount');
        $pendingFees = $studentFees->where('status', 'pending')->count();
        $overdueFees = $studentFees->where('status', 'overdue')->count();

        return view('student.payment.index', compact(
            'studentFees', 
            'payments', 
            'totalFees', 
            'totalPaid', 
            'totalBalance', 
            'pendingFees', 
            'overdueFees'
        ));
    }

    public function pendingPayments()
    {
        $student = Auth::guard('student')->user();
        
        $pendingFees = StudentFee::with('feeStructure')
            ->where('student_id', $student->id)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('student.payment.pending', compact('pendingFees'));
    }

    public function paymentHistory()
    {
        $student = Auth::guard('student')->user();
        
        $payments = Payment::with('studentFee.feeStructure')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.payment.history', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if the payment belongs to this student
        if ($payment->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['studentFee.feeStructure']);

        return view('student.payment.show', compact('payment'));
    }

    public function showFee(StudentFee $studentFee)
    {
        $student = Auth::guard('student')->user();
        
        // Check if the fee belongs to this student
        if ($studentFee->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        $studentFee->load(['feeStructure', 'payments']);

        return view('student.payment.fee-details', compact('studentFee'));
    }

    public function generateReceipt(Payment $payment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if the payment belongs to this student
        if ($payment->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['student', 'studentFee.feeStructure', 'institution']);

        return view('student.payment.receipt', compact('payment'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $student = Auth::guard('student')->user();
        
        // Check if the payment belongs to this student
        if ($payment->student_id !== $student->id) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['student', 'studentFee.feeStructure', 'institution']);

        // Generate PDF receipt
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('student.payment.receipt-pdf', compact('payment'));
        
        $filename = 'Receipt_' . $payment->receipt_number . '_' . $payment->payment_date->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
