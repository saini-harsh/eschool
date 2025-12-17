<?php

namespace App\Http\Controllers\Admin\Salary;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\SalaryPayment;
use App\Models\SalaryStructure;
use App\Models\Teacher;
use App\Models\NonWorkingStaff;
use App\Services\RazorpayXService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryPaymentController extends Controller
{
    /**
     * Display a listing of all salary payments across institutions.
     */
    public function index(Request $request)
    {
        $institutions = Institution::where('status', true)->orderBy('name')->get();

        $query = SalaryPayment::with(['institution', 'salaryStructure'])
            ->orderBy('created_at', 'desc');

        // Filter by institution
        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payee type
        if ($request->filled('payee_type')) {
            $query->where('payee_type', $request->payee_type);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Search by transaction ID
        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', '%' . $request->search . '%');
        }

        $payments = $query->paginate(20)->withQueryString();

        // Get summary stats
        $statsQuery = SalaryPayment::query();
        if ($request->filled('institution_id')) {
            $statsQuery->where('institution_id', $request->institution_id);
        }

        $stats = [
            'total_paid' => (clone $statsQuery)->where('status', 'paid')->sum('net_salary'),
            'total_pending' => (clone $statsQuery)->where('status', 'pending')->sum('net_salary'),
            'total_processing' => (clone $statsQuery)->where('status', 'processing')->sum('net_salary'),
            'total_failed' => (clone $statsQuery)->where('status', 'failed')->sum('net_salary'),
            'count_paid' => (clone $statsQuery)->where('status', 'paid')->count(),
            'count_pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'count_processing' => (clone $statsQuery)->where('status', 'processing')->count(),
            'count_failed' => (clone $statsQuery)->where('status', 'failed')->count(),
        ];

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(date('Y') - 2, date('Y') + 1);

        return view('admin.salary.payments.index', compact('payments', 'institutions', 'stats', 'months', 'years'));
    }

    /**
     * Display the specified salary payment.
     */
    public function show($id)
    {
        $payment = SalaryPayment::with(['institution', 'salaryStructure'])->findOrFail($id);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('admin.salary.payments.show', compact('payment', 'months'));
    }

    /**
     * Process a salary payment (manual mark as paid).
     */
    public function processPayment(Request $request, $id)
    {
        $payment = SalaryPayment::with('institution')->findOrFail($id);

        if (!in_array($payment->status, ['pending', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This payment cannot be processed in its current state'
            ], 422);
        }

        $payee = $payment->payee;
        if (!$payee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        // For manual payment methods, just mark as paid
        if (in_array($payment->payment_method, ['cash', 'cheque', 'bank_transfer'])) {
            $payment->status = 'paid';
            $payment->payment_date = now();
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as completed'
            ]);
        }

        // For Razorpay
        $institution = $payment->institution;
        if (!$institution || !$institution->hasRazorpayConfig()) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay is not configured for this institution'
            ], 422);
        }

        if (!$payee->hasBankDetails()) {
            return response()->json([
                'success' => false,
                'message' => 'Bank details not configured for this employee'
            ], 422);
        }

        try {
            $razorpay = new RazorpayXService($institution);
            $payout = $razorpay->processSalaryPayment($payment);

            return response()->json([
                'success' => true,
                'message' => 'Payment is being processed via RazorpayX',
                'payout_id' => $payout['id'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status manually.
     */
    public function updateStatus(Request $request, $id)
    {
        $payment = SalaryPayment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,paid,failed',
            'failure_reason' => 'nullable|string|max:500',
        ]);

        $payment->status = $request->status;
        
        if ($request->status === 'paid') {
            $payment->payment_date = now();
        }

        if ($request->status === 'failed' && $request->filled('failure_reason')) {
            $payment->failure_reason = $request->failure_reason;
        }

        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }

    /**
     * Get institution statistics.
     */
    public function getInstitutionStats($institutionId)
    {
        $institution = Institution::findOrFail($institutionId);

        $stats = [
            'total_teachers' => Teacher::where('institution_id', $institutionId)->count(),
            'total_staff' => NonWorkingStaff::where('institution_id', $institutionId)->count(),
            'teachers_with_salary' => Teacher::where('institution_id', $institutionId)
                ->whereNotNull('salary')
                ->where('salary', '>', 0)
                ->count(),
            'staff_with_salary' => NonWorkingStaff::where('institution_id', $institutionId)
                ->whereNotNull('salary')
                ->where('salary', '>', 0)
                ->count(),
            'salary_structures' => SalaryStructure::where('institution_id', $institutionId)->count(),
            'razorpay_configured' => $institution->hasRazorpayConfig(),
            'total_paid_this_month' => SalaryPayment::where('institution_id', $institutionId)
                ->where('month', date('n'))
                ->where('year', date('Y'))
                ->where('status', 'paid')
                ->sum('net_salary'),
            'pending_this_month' => SalaryPayment::where('institution_id', $institutionId)
                ->where('month', date('n'))
                ->where('year', date('Y'))
                ->where('status', 'pending')
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export salary payments to CSV.
     */
    public function export(Request $request)
    {
        $query = SalaryPayment::with(['institution'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('institution_id')) {
            $query->where('institution_id', $request->institution_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $payments = $query->get();

        $filename = 'salary_payments_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $callback = function() use ($payments, $months) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Transaction ID',
                'Institution',
                'Employee Type',
                'Employee Name',
                'Period',
                'Base Salary',
                'Earnings',
                'Deductions',
                'Net Salary',
                'Payment Method',
                'Status',
                'Payment Date',
                'Created At'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->transaction_id,
                    $payment->institution->name ?? '-',
                    ucfirst($payment->payee_type),
                    $payment->payeeName,
                    ($months[$payment->month] ?? '') . ' ' . $payment->year,
                    $payment->base_salary,
                    $payment->total_earnings,
                    $payment->total_deductions,
                    $payment->net_salary,
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    ucfirst($payment->status),
                    $payment->payment_date ? $payment->payment_date->format('Y-m-d H:i:s') : '-',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
