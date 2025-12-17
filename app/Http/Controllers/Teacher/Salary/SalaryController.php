<?php

namespace App\Http\Controllers\Teacher\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    /**
     * Display teacher's salary history.
     */
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $query = SalaryPayment::where('payee_type', 'teacher')
            ->where('payee_id', $teacher->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $payments = $query->paginate(12)->withQueryString();

        // Get summary stats
        $stats = [
            'total_received' => SalaryPayment::where('payee_type', 'teacher')
                ->where('payee_id', $teacher->id)
                ->where('status', 'paid')
                ->sum('net_salary'),
            'total_pending' => SalaryPayment::where('payee_type', 'teacher')
                ->where('payee_id', $teacher->id)
                ->where('status', 'pending')
                ->sum('net_salary'),
            'count_paid' => SalaryPayment::where('payee_type', 'teacher')
                ->where('payee_id', $teacher->id)
                ->where('status', 'paid')
                ->count(),
            'count_pending' => SalaryPayment::where('payee_type', 'teacher')
                ->where('payee_id', $teacher->id)
                ->where('status', 'pending')
                ->count(),
            'current_salary' => $teacher->salary ?? 0,
        ];

        $years = range(date('Y') - 2, date('Y'));

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('teacher.salary.index', compact('payments', 'stats', 'years', 'months', 'teacher'));
    }

    /**
     * Show salary payment details.
     */
    public function show($id)
    {
        $teacher = Auth::guard('teacher')->user();

        $payment = SalaryPayment::where('payee_type', 'teacher')
            ->where('payee_id', $teacher->id)
            ->findOrFail($id);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('teacher.salary.show', compact('payment', 'months', 'teacher'));
    }
}
