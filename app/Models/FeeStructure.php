<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'class_id',
        'section_id',
        'fee_name',
        'description',
        'amount',
        'fee_type',
        'payment_frequency',
        'due_date',
        'is_mandatory',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_mandatory' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    // Date helper methods
    public function getFormattedDueDate()
    {
        return $this->due_date ? Carbon::parse($this->due_date)->format('M d, Y') : 'Not set';
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d M, Y') : 'N/A';
    }

    public function getFormattedUpdatedAt()
    {
        return $this->updated_at ? Carbon::parse($this->updated_at)->format('d M, Y') : 'N/A';
    }

    public function isDueDateSet()
    {
        return $this->due_date !== null;
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

    public function getFormattedAmount()
    {
        return '₹' . number_format($this->amount, 2);
    }
}
