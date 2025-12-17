<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_structure_id',
        'name',
        'type',
        'amount',
        'is_percentage',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
    ];

    /**
     * Get the salary structure this component belongs to
     */
    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    /**
     * Scope for earnings
     */
    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    /**
     * Scope for deductions
     */
    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }

    /**
     * Get the calculated amount for a given base salary
     */
    public function getCalculatedAmount($baseSalary)
    {
        if ($this->is_percentage) {
            return round($baseSalary * $this->amount / 100, 2);
        }
        return $this->amount;
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClass()
    {
        return $this->type === 'earning' ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayName()
    {
        return $this->type === 'earning' ? 'Earning' : 'Deduction';
    }
}
