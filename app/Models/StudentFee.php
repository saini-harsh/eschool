<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'institution_id',
        'amount',
        'paid_amount',
        'balance_amount',
        'due_date',
        'billing_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'due_date' => 'date',
        'billing_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif (now()->isAfter($this->due_date)) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
        
        $this->balance_amount = $this->amount - $this->paid_amount;
        $this->save();
    }

    // Date helper methods
    public function getFormattedDueDate()
    {
        return $this->due_date ? Carbon::parse($this->due_date)->format('M d, Y') : 'N/A';
    }

    public function getFormattedBillingDate()
    {
        return $this->billing_date ? Carbon::parse($this->billing_date)->format('M d, Y') : 'N/A';
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d M, Y') : 'N/A';
    }

    public function isOverdue()
    {
        return $this->due_date && Carbon::parse($this->due_date)->isPast() && $this->status !== 'paid';
    }

    public function getDaysUntilDue()
    {
        if (!$this->due_date) {
            return null;
        }
        $dueDate = Carbon::parse($this->due_date);
        $now = Carbon::now();
        
        // If due date is in the past, return negative days
        if ($dueDate->isPast()) {
            return -$dueDate->diffInDays($now);
        }
        
        // If due date is in the future, return positive days (rounded to whole number)
        return round($now->diffInDays($dueDate));
    }

    public function getDaysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return Carbon::parse($this->due_date)->diffInDays(now());
    }

    public function getDueDateStatus()
    {
        if (!$this->due_date) {
            return 'not_set';
        }
        
        $dueDate = Carbon::parse($this->due_date);
        
        if ($dueDate->isToday()) {
            return 'due_today';
        } elseif ($dueDate->isPast()) {
            return 'overdue';
        } elseif ($dueDate->diffInDays(now()) <= 7) {
            return 'due_soon';
        } else {
            return 'upcoming';
        }
    }

    public function getPaymentProgressPercentage()
    {
        if ($this->amount <= 0) {
            return 0;
        }
        return round(($this->paid_amount / $this->amount) * 100, 1);
    }
}
