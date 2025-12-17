<?php

namespace App\Http\Controllers\Institution\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use App\Models\SalaryStructure;
use App\Models\Teacher;
use App\Models\NonWorkingStaff;
use App\Services\RazorpayXService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalaryPaymentController extends Controller
{
    /**
     * Display a listing of salary payments.
     */
    public function index(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $query = SalaryPayment::where('institution_id', $institution->id)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payee_type')) {
            $query->where('payee_type', $request->payee_type);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        // Get summary stats
        $stats = [
            'total_pending' => SalaryPayment::byInstitution($institution->id)->pending()->sum('net_salary'),
            'total_paid' => SalaryPayment::byInstitution($institution->id)->paid()->sum('net_salary'),
            'total_failed' => SalaryPayment::byInstitution($institution->id)->failed()->sum('net_salary'),
            'count_pending' => SalaryPayment::byInstitution($institution->id)->pending()->count(),
            'count_paid' => SalaryPayment::byInstitution($institution->id)->paid()->count(),
            'count_failed' => SalaryPayment::byInstitution($institution->id)->failed()->count(),
        ];

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $years = range(date('Y') - 2, date('Y') + 1);

        return view('institution.salary.payments.index', compact('payments', 'stats', 'months', 'years'));
    }

    /**
     * Show the form for creating a new salary payment.
     */
    public function create()
    {
        $institution = Auth::guard('institution')->user();

        $teachers = Teacher::where('institution_id', $institution->id)
            ->where('status', true)
            ->get();

        $staff = NonWorkingStaff::where('institution_id', $institution->id)
            ->where('status', true)
            ->get();

        $salaryStructures = SalaryStructure::where('institution_id', $institution->id)
            ->where('status', true)
            ->with('components')
            ->get();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('institution.salary.payments.create', compact('teachers', 'staff', 'salaryStructures', 'months'));
    }

    /**
     * Store a newly created salary payment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payee_type' => 'required|in:teacher,staff',
            'payee_id' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'payment_method' => 'required|in:razorpay,bank_transfer,cash,cheque',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();

        // Get payee
        if ($request->payee_type === 'teacher') {
            $payee = Teacher::where('institution_id', $institution->id)->find($request->payee_id);
        } else {
            $payee = NonWorkingStaff::where('institution_id', $institution->id)->find($request->payee_id);
        }

        if (!$payee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        if (!$payee->salary || $payee->salary <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Salary not configured for this employee'
            ], 422);
        }

        // Check for duplicate payment
        $exists = SalaryPayment::where('institution_id', $institution->id)
            ->where('payee_type', $request->payee_type)
            ->where('payee_id', $request->payee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->whereIn('status', ['pending', 'processing', 'paid'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Salary payment already exists for this employee for the selected month'
            ], 422);
        }

        // Calculate salary
        $baseSalary = $payee->salary;
        $breakdown = null;
        $totalEarnings = 0;
        $totalDeductions = 0;

        if ($payee->salary_structure_id) {
            $structure = SalaryStructure::with('components')->find($payee->salary_structure_id);
            if ($structure) {
                $breakdown = $structure->getSalaryBreakdown($baseSalary);
                $totalEarnings = $breakdown['total_earnings'];
                $totalDeductions = $breakdown['total_deductions'];
            }
        }

        $netSalary = $baseSalary + $totalEarnings - $totalDeductions;

        try {
            $payment = SalaryPayment::create([
                'institution_id' => $institution->id,
                'payee_type' => $request->payee_type,
                'payee_id' => $request->payee_id,
                'salary_structure_id' => $payee->salary_structure_id,
                'month' => $request->month,
                'year' => $request->year,
                'base_salary' => $baseSalary,
                'total_earnings' => $totalEarnings,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'payment_method' => $request->payment_method,
                'transaction_id' => SalaryPayment::generateTransactionId($institution->id),
                'status' => 'pending',
                'notes' => $request->notes,
                'salary_breakdown' => $breakdown,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Salary payment created successfully',
                'payment_id' => $payment->id,
                'redirect_url' => route('institution.salary.payments.show', $payment->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified salary payment.
     */
    public function show($id)
    {
        $institution = Auth::guard('institution')->user();

        $payment = SalaryPayment::where('institution_id', $institution->id)
            ->findOrFail($id);

        return view('institution.salary.payments.show', compact('payment'));
    }

    /**
     * Process a salary payment via Razorpay.
     */
    public function processPayment($id)
    {
        $institution = Auth::guard('institution')->user();

        $payment = SalaryPayment::where('institution_id', $institution->id)
            ->findOrFail($id);

        if (!$payment->canProcess()) {
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

        // For manual payment methods
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
        if (!$institution->hasRazorpayConfig()) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay is not configured. Please configure Razorpay in settings.'
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
     * Show bulk salary processing form.
     */
    public function bulkCreate()
    {
        $institution = Auth::guard('institution')->user();

        $teachers = Teacher::where('institution_id', $institution->id)
            ->where('status', true)
            ->whereNotNull('salary')
            ->where('salary', '>', 0)
            ->get();

        $staff = NonWorkingStaff::where('institution_id', $institution->id)
            ->where('status', true)
            ->whereNotNull('salary')
            ->where('salary', '>', 0)
            ->get();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('institution.salary.payments.bulk', compact('teachers', 'staff', 'months'));
    }

    /**
     * Process bulk salary payments.
     */
    public function bulkProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'payment_method' => 'required|in:razorpay,bank_transfer,cash,cheque',
            'include_teachers' => 'boolean',
            'include_staff' => 'boolean',
            'selected_ids' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();
        $created = 0;
        $skipped = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            // Process teachers
            if ($request->include_teachers) {
                $teacherQuery = Teacher::where('institution_id', $institution->id)
                    ->where('status', true)
                    ->whereNotNull('salary')
                    ->where('salary', '>', 0);

                if ($request->filled('selected_ids.teachers')) {
                    $teacherQuery->whereIn('id', $request->selected_ids['teachers']);
                }

                foreach ($teacherQuery->get() as $teacher) {
                    $result = $this->createPaymentForPayee($teacher, 'teacher', $request->month, $request->year, $request->payment_method, $institution);
                    if ($result['success']) {
                        $created++;
                    } else {
                        $skipped++;
                        if ($result['reason'] !== 'exists') {
                            $errors[] = $teacher->first_name . ' ' . $teacher->last_name . ': ' . $result['message'];
                        }
                    }
                }
            }

            // Process staff
            if ($request->include_staff) {
                $staffQuery = NonWorkingStaff::where('institution_id', $institution->id)
                    ->where('status', true)
                    ->whereNotNull('salary')
                    ->where('salary', '>', 0);

                if ($request->filled('selected_ids.staff')) {
                    $staffQuery->whereIn('id', $request->selected_ids['staff']);
                }

                foreach ($staffQuery->get() as $staff) {
                    $result = $this->createPaymentForPayee($staff, 'staff', $request->month, $request->year, $request->payment_method, $institution);
                    if ($result['success']) {
                        $created++;
                    } else {
                        $skipped++;
                        if ($result['reason'] !== 'exists') {
                            $errors[] = $staff->first_name . ' ' . $staff->last_name . ': ' . $result['message'];
                        }
                    }
                }
            }

            DB::commit();

            $message = "Created {$created} salary payments.";
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} (already exist or have errors).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'created' => $created,
                'skipped' => $skipped,
                'errors' => $errors,
                'redirect_url' => route('institution.salary.payments.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create bulk payments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to create payment for a payee.
     */
    private function createPaymentForPayee($payee, $type, $month, $year, $paymentMethod, $institution)
    {
        // Check for duplicate
        $exists = SalaryPayment::where('institution_id', $institution->id)
            ->where('payee_type', $type)
            ->where('payee_id', $payee->id)
            ->where('month', $month)
            ->where('year', $year)
            ->whereIn('status', ['pending', 'processing', 'paid'])
            ->exists();

        if ($exists) {
            return ['success' => false, 'reason' => 'exists', 'message' => 'Already exists'];
        }

        // Calculate salary
        $baseSalary = $payee->salary;
        $breakdown = null;
        $totalEarnings = 0;
        $totalDeductions = 0;

        if ($payee->salary_structure_id) {
            $structure = SalaryStructure::with('components')->find($payee->salary_structure_id);
            if ($structure) {
                $breakdown = $structure->getSalaryBreakdown($baseSalary);
                $totalEarnings = $breakdown['total_earnings'];
                $totalDeductions = $breakdown['total_deductions'];
            }
        }

        $netSalary = $baseSalary + $totalEarnings - $totalDeductions;

        SalaryPayment::create([
            'institution_id' => $institution->id,
            'payee_type' => $type,
            'payee_id' => $payee->id,
            'salary_structure_id' => $payee->salary_structure_id,
            'month' => $month,
            'year' => $year,
            'base_salary' => $baseSalary,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'payment_method' => $paymentMethod,
            'transaction_id' => SalaryPayment::generateTransactionId($institution->id),
            'status' => 'pending',
            'salary_breakdown' => $breakdown,
        ]);

        return ['success' => true];
    }

    /**
     * Bulk process pending payments.
     */
    public function bulkProcessPayments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ids' => 'required|array|min:1',
            'payment_ids.*' => 'required|integer|exists:salary_payments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $institution = Auth::guard('institution')->user();
        $processed = 0;
        $failed = 0;
        $errors = [];

        foreach ($request->payment_ids as $paymentId) {
            $payment = SalaryPayment::where('institution_id', $institution->id)
                ->where('id', $paymentId)
                ->first();

            if (!$payment || !$payment->canProcess()) {
                $failed++;
                continue;
            }

            // For manual payment methods, just mark as paid
            if (in_array($payment->payment_method, ['cash', 'cheque', 'bank_transfer'])) {
                $payment->status = 'paid';
                $payment->payment_date = now();
                $payment->save();
                $processed++;
                continue;
            }

            // For Razorpay, try to process
            if ($payment->payment_method === 'razorpay') {
                $payee = $payment->payee;
                if (!$payee || !$payee->hasBankDetails()) {
                    $failed++;
                    $errors[] = "Payment #{$payment->id}: Bank details missing";
                    continue;
                }

                try {
                    $razorpay = new RazorpayXService($institution);
                    $razorpay->processSalaryPayment($payment);
                    $processed++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Payment #{$payment->id}: " . $e->getMessage();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Processed {$processed} payments. Failed: {$failed}",
            'processed' => $processed,
            'failed' => $failed,
            'errors' => $errors
        ]);
    }

    /**
     * Get teachers for AJAX request.
     */
    public function getTeachers()
    {
        $institution = Auth::guard('institution')->user();

        $teachers = Teacher::where('institution_id', $institution->id)
            ->where('status', true)
            ->whereNotNull('salary')
            ->where('salary', '>', 0)
            ->get(['id', 'first_name', 'last_name', 'salary', 'salary_structure_id', 'bank_account_number']);

        return response()->json($teachers);
    }

    /**
     * Get staff for AJAX request.
     */
    public function getStaff()
    {
        $institution = Auth::guard('institution')->user();

        $staff = NonWorkingStaff::where('institution_id', $institution->id)
            ->where('status', true)
            ->whereNotNull('salary')
            ->where('salary', '>', 0)
            ->get(['id', 'first_name', 'last_name', 'salary', 'salary_structure_id', 'bank_account_number']);

        return response()->json($staff);
    }

    /**
     * Get salary preview for a payee.
     */
    public function getSalaryPreview(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $payeeType = $request->get('payee_type');
        $payeeId = $request->get('payee_id');

        if ($payeeType === 'teacher') {
            $payee = Teacher::where('institution_id', $institution->id)->find($payeeId);
        } else {
            $payee = NonWorkingStaff::where('institution_id', $institution->id)->find($payeeId);
        }

        if (!$payee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        $baseSalary = $payee->salary ?? 0;
        $breakdown = [
            'base_salary' => $baseSalary,
            'earnings' => [],
            'deductions' => [],
            'total_earnings' => 0,
            'total_deductions' => 0,
            'net_salary' => $baseSalary,
        ];

        if ($payee->salary_structure_id) {
            $structure = SalaryStructure::with('components')->find($payee->salary_structure_id);
            if ($structure) {
                $breakdown = $structure->getSalaryBreakdown($baseSalary);
            }
        }

        return response()->json([
            'success' => true,
            'breakdown' => $breakdown,
            'has_bank_details' => $payee->hasBankDetails(),
        ]);
    }
}
