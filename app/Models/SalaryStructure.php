<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the institution that owns this salary structure
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the components of this salary structure
     */
    public function components()
    {
        return $this->hasMany(SalaryComponent::class);
    }

    /**
     * Get earning components
     */
    public function earnings()
    {
        return $this->hasMany(SalaryComponent::class)->where('type', 'earning');
    }

    /**
     * Get deduction components
     */
    public function deductions()
    {
        return $this->hasMany(SalaryComponent::class)->where('type', 'deduction');
    }

    /**
     * Get salary payments using this structure
     */
    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    /**
     * Get teachers using this salary structure
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get non-working staff using this salary structure
     */
    public function nonWorkingStaff()
    {
        return $this->hasMany(NonWorkingStaff::class);
    }

    /**
     * Calculate total earnings for a given base salary
     */
    public function calculateEarnings($baseSalary)
    {
        $total = 0;
        foreach ($this->earnings as $component) {
            if ($component->is_percentage) {
                $total += ($baseSalary * $component->amount / 100);
            } else {
                $total += $component->amount;
            }
        }
        return $total;
    }

    /**
     * Calculate total deductions for a given base salary
     */
    public function calculateDeductions($baseSalary)
    {
        $total = 0;
        foreach ($this->deductions as $component) {
            if ($component->is_percentage) {
                $total += ($baseSalary * $component->amount / 100);
            } else {
                $total += $component->amount;
            }
        }
        return $total;
    }

    /**
     * Calculate net salary
     */
    public function calculateNetSalary($baseSalary)
    {
        $earnings = $this->calculateEarnings($baseSalary);
        $deductions = $this->calculateDeductions($baseSalary);
        return $baseSalary + $earnings - $deductions;
    }

    /**
     * Get detailed salary breakdown
     */
    public function getSalaryBreakdown($baseSalary)
    {
        $breakdown = [
            'base_salary' => $baseSalary,
            'earnings' => [],
            'deductions' => [],
            'total_earnings' => 0,
            'total_deductions' => 0,
            'net_salary' => 0,
        ];

        foreach ($this->earnings as $component) {
            $amount = $component->is_percentage 
                ? ($baseSalary * $component->amount / 100) 
                : $component->amount;
            $breakdown['earnings'][] = [
                'name' => $component->name,
                'amount' => round($amount, 2),
                'is_percentage' => $component->is_percentage,
                'percentage' => $component->is_percentage ? $component->amount : null,
            ];
            $breakdown['total_earnings'] += $amount;
        }

        foreach ($this->deductions as $component) {
            $amount = $component->is_percentage 
                ? ($baseSalary * $component->amount / 100) 
                : $component->amount;
            $breakdown['deductions'][] = [
                'name' => $component->name,
                'amount' => round($amount, 2),
                'is_percentage' => $component->is_percentage,
                'percentage' => $component->is_percentage ? $component->amount : null,
            ];
            $breakdown['total_deductions'] += $amount;
        }

        $breakdown['total_earnings'] = round($breakdown['total_earnings'], 2);
        $breakdown['total_deductions'] = round($breakdown['total_deductions'], 2);
        $breakdown['net_salary'] = round($baseSalary + $breakdown['total_earnings'] - $breakdown['total_deductions'], 2);

        return $breakdown;
    }

    /**
     * Scope for active structures
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for institution
     */
    public function scopeByInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }
}
