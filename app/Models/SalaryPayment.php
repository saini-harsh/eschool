<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'payee_type',
        'payee_id',
        'salary_structure_id',
        'month',
        'year',
        'base_salary',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'payment_method',
        'razorpay_payout_id',
        'razorpay_fund_account_id',
        'transaction_id',
        'status',
        'payment_date',
        'failure_reason',
        'notes',
        'salary_breakdown',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'salary_breakdown' => 'array',
    ];

    /**
     * Get the institution
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the salary structure
     */
    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    /**
     * Get the payee (teacher or non-working staff)
     */
    public function payee()
    {
        if ($this->payee_type === 'teacher') {
            return $this->belongsTo(Teacher::class, 'payee_id');
        }
        return $this->belongsTo(NonWorkingStaff::class, 'payee_id');
    }

    /**
     * Get teacher if payee is teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'payee_id');
    }

    /**
     * Get staff if payee is staff
     */
    public function staff()
    {
        return $this->belongsTo(NonWorkingStaff::class, 'payee_id');
    }

    /**
     * Get the payee model
     */
    public function getPayeeAttribute()
    {
        if ($this->payee_type === 'teacher') {
            return Teacher::find($this->payee_id);
        }
        return NonWorkingStaff::find($this->payee_id);
    }

    /**
     * Get payee name
     */
    public function getPayeeNameAttribute()
    {
        $payee = $this->payee;
        if ($payee) {
            return $payee->first_name . ' ' . $payee->last_name;
        }
        return 'Unknown';
    }

    /**
     * Get payee type display name
     */
    public function getPayeeTypeDisplayAttribute()
    {
        return $this->payee_type === 'teacher' ? 'Teacher' : 'Staff';
    }

    /**
     * Get month name
     */
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        return $months[$this->month] ?? 'Unknown';
    }

    /**
     * Get period display (Month Year)
     */
    public function getPeriodAttribute()
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'paid' => 'bg-success',
            'failed' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return match($this->payment_method) {
            'razorpay' => 'RazorpayX',
            'bank_transfer' => 'Bank Transfer',
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            default => ucfirst($this->payment_method),
        };
    }

    /**
     * Check if payment can be processed
     */
    public function canProcess()
    {
        return in_array($this->status, ['pending', 'failed']);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted()
    {
        return $this->status === 'paid';
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId($institutionId)
    {
        $institution = Institution::find($institutionId);
        $prefix = $institution->institution_code ?? 'SAL';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "{$prefix}/SAL/{$date}/{$random}";
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for processing payments
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope by month and year
     */
    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope by payee type
     */
    public function scopeForPayeeType($query, $type)
    {
        return $query->where('payee_type', $type);
    }

    /**
     * Scope by institution
     */
    public function scopeByInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }
}
