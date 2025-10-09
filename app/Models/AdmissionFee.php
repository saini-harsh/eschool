<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionFee extends Model
{
    protected $fillable = [
        'name',
        'description',
        'institution_id',
        'class_id',
        'section_id',
        'amount',
        'effective_from',
        'effective_until',
        'is_mandatory',
        'status',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_until' => 'date',
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the institution that owns the admission fee.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the class that owns the admission fee.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the section that owns the admission fee.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Scope to get active admission fees.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope to get mandatory admission fees.
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', 1);
    }

    /**
     * Scope to get admission fees effective on a specific date.
     */
    public function scopeEffectiveOn($query, $date)
    {
        return $query->where('effective_from', '<=', $date)
                    ->where(function($q) use ($date) {
                        $q->whereNull('effective_until')
                          ->orWhere('effective_until', '>=', $date);
                    });
    }
}
